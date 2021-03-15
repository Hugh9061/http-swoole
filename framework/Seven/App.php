<?php

    namespace Seven;

    class App{

        static public function run(){

            //实例化拓展
            $http = new \Swoole\Http\Server('0.0.0.0', 9500); //1-65535


            //绑定事件
            $http->on('Request', function ($request, $response) {

                $uri = $request->server['request_uri'];  //请求的uri

                $completeUri = str_replace('framework/','',dirname(__DIR__).$uri);  //该uri在服务器中完整的路径

                $responseContent = '<h1>Hello Seven. #' . rand(1000, 9999) . '</h1>'; // 返回的内容

                if(file_exists($completeUri)){   //判断文件是否存在

                    $ext = pathinfo($completeUri)['extension'];   //获取拓展名

                    $responseContent = file_get_contents(str_replace('framework/','',dirname(__DIR__).$uri));  //获取文件内容

                    if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg'){   //图片文件

                        $contentType = $ext == 'jpg' ? 'jpeg' : $ext;

                        $response->header('Content-Type', "image/$contentType");   //返回类型设置为请求的文件类型

                    }

                    if($ext == 'txt' || $ext == 'exe'){   //可下载文件

                        $response->header('Content-Disposition','attachment;filename=' . basename($uri)); //返回类型设置为请求的类型

                    }

                    if($ext == 'php'){  //可执行文件

                        

                    }
                }

                //路由分发  得到请求地址
                $response->end($responseContent);
            });


            //启动服务
            $http->start();

        }


        //处理图片
        function resolveImg($request,$response,$uri){

            $uri = $request->server['request_uri'];

            $response->header('Content-Type', 'image/png');

            $content = file_get_contents(str_replace('framework/','',dirname(__DIR__).$uri));

            $response->end($content);
        }

    }