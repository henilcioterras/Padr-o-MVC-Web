<?php
    namespace app\routes;
    use app\helpers\Request;
    use app\helpers\Uri;
    use Exception;

    class Router{

        const CONTROLLER_NAMESPACE = 'app\\controllers';
        public static function load(string $controller, string $method){
            try{
                $controllerNamespace= self::CONTROLLER_NAMESPACE.'\\'.$controller;
                //verificar se o controller existe
                if(!class_exists($controllerNamespace)){
                    throw new Exception("O controller {$controller} nao existe");
                }
                //se chegou ate aqui entao instancia essa classe
                $controllerInstance = new $controllerNamespace; 
                  //se chegou ate aqui entao ve se metodo existe
                if(!method_exists($controllerNamespace,$method)){
                    throw new Exception("O metodo {$method} nao existe nao existe no {$controller} controller " );
                }
                //se chegou ate aqui entao chama o metodo no controller
                $controllerInstance-> $method();


            }catch(\Throwable $th){
                echo $th->getMessage();
            }

        }
        public static function routes():array{
             return [
                'get'=>[
                    '/' => fn()=> self::load('HomeController','index'),
                    '/contact' => fn()=> self::load('ContactController','index'),
                    '/product' => fn()=> self::load('ProductController','index'),
                ],
                'post'=>[
                    '/contact' => fn()=>  self::load('ContactController','store'),
                ],
                'put'=>[
                    '/contact' => fn()=>  self::load('ProductController','update'),
                ],
                'delete'=>[

                ]
                ];

        }
        public static function execute(){
            try{
                $routes =self::routes();
                $request =Request::get();
                $uri = Uri::get('path');

                //ver se nao existe isset no get que é $request
                if(!isset($routes[$request])){
                    throw new Exception("A rota nao existe");
                }
                //ver se  a uri existe no array do tipo se e get post ou etc

                if(!array_key_exists($uri,$routes[$request])){
                    throw new Exception("A rota nao existe");
                }
                $router = $routes[$request][$uri];

                if(!is_callable($router)){
                    throw new Exception("A rota {$uri} nao é chamavel");
                }

                //$router();

            }catch(\Throwable $th){
                echo $th->getMessage();
            }
        }
    }