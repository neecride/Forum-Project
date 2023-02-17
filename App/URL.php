<?php

namespace App;

class URL{

    public static function getInt(string $name, ?int $default = null): ?int
    {
        global $router, $match;
        if(!isset($_GET[$name])) return $default;
        if($_GET[$name] === '0') return 0;

        if(!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
            if(isset($match['params']) && $match['params'] != null){
                if(isset($match['params']['slug']) && $match['params']['slug'] != null){
                    header('Location:' . $router->generate($match['name'], ['slug' => $match['params']['slug'], 'id' => $match['params']['id']]));
                }else{
                    header('Location:' . $router->generate($match['name'], ['id' => $match['params']['id']]));
                }
            }else{
                header('Location:' . $router->generate($match['name']));
            }
            setFlash("Le paramètre $name dans l'url n'est pas un entier",'orange');
            http_response_code(301);
            exit();
        }
        return (int)$_GET[$name];
    }

    public static function getPositiveInt(string $name, ?int $default = null): ?int
    {
        global $router,$match;
        $param = self::getInt($name, $default);
        if($param !== null && $param <= 0){
            if(isset($match['params']) && $match['params'] != null){
                if(isset($match['params']['slug']) && $match['params']['slug'] != null){
                    header('Location:' . $router->generate($match['name'], ['slug' => $match['params']['slug'], 'id' => $match['params']['id']]));
                }else{
                    header('Location:' . $router->generate($match['name'], ['id' => $match['params']['id']]));
                }
            }else{
                header('Location:' . $router->generate($match['name']));
            }
            setFlash("Le paramètre $name dans l'url n'est pas un entier positif",'orange');
            http_response_code(301);
            exit();
        }
        return $param;
    }

}