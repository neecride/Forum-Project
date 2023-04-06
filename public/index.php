<?php
if(session_status() === PHP_SESSION_NONE)
{
    session_start();
}
session_regenerate_id();

define            ('DS', DIRECTORY_SEPARATOR);
define            ('RACINE', dirname(__DIR__));
require           (RACINE.DS.'vendor'.DS.'autoload.php');
//require-dev
ini_set('SMTP', "localhost");
ini_set('smtp_port', "1025");
ini_set('sendmail_from', "admin@wampserver.com");
//require-dev
$builder          = new \DI\ContainerBuilder();
$router           = new Framework\Router;
$app              = new App\App;
$builder->addDefinitions(RACINE.DS.'config'.DS.'config.php');
$container = $builder->build();

$match = $router->matchRoute();

$app->reconnectFromCookie();
if(isset($_GET['page']) && $_GET['page'] === '1'):
    $uri = explode('?',$_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if(!empty($query)):
        $uri = $uri . '?' . $query;
    endif;
    header('Location:' . $uri);
    http_response_code(301);
    exit();
endif;

if(is_array($match)):

    $method = strtolower($match['target']);

    if(method_exists($router, $method)):
        $router->$method();
    else:
        $app->setFlash("Une erreur est survenue",'orange');
        http_response_code(404);
        $app->redirect($router->routeGenerate('error'));
    endif;

else:
    $app->setFlash("Cette page n'éxiste pas",'orange');
    http_response_code(404);
    $app->redirect($router->routeGenerate('error'));
endif;
