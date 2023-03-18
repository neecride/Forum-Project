<?php
if(session_status() === PHP_SESSION_NONE)
{
    session_start();
}
session_regenerate_id();

define            ('DS', DIRECTORY_SEPARATOR);
define            ('RACINE', dirname(__DIR__));
require           RACINE.DS.'vendor'.DS.'autoload.php';
$generator        = new Ausi\SlugGenerator\SlugGenerator;
$App              = new App\App;
$forum            = new App\Forum;
$router           = new Framework\Router;
$Parsing          = new App\Parsing;
$pagination       = new App\Pagination;
$GetParams        = new App\Parameters;
$index            = new App\Renderer;

require_once (RACINE.DS.'lib'.DS.'libs-includes.php');

$index->isNotExistPage();

//$index->Render($db);

if(is_array($router->matchRoute())){

        $themeForLayout   = $GetParams->themeForLayout();
        $match            = $router->matchRoute();

        $getUri = explode('/', $_SERVER['REQUEST_URI']);

        ob_start();
        if($getUri[1] == 'admin'){
            $App->isAdmin();
        }

        $fileLogic = RACINE.DS.'lib'.DS.'modules'.DS.$match['target'].'.func.php';
        //si le fichier existe on l'inclu on supprimera cette condition dans le futur
        if(preg_match("#\.(php)$#",strtolower($fileLogic)) && file_exists($fileLogic) && is_file($fileLogic)){
            require_once $fileLogic;
        }
        $filesTargetCheck = RACINE.DS.'public'.DS.'templates'.DS.$themeForLayout.DS.$match['target'].'.php';
        //si un fichier de personalisation exist on l'inclu sinon on inclu le fichier par defaut
        if(preg_match("#\.(php)$#",strtolower($filesTargetCheck)) && file_exists($filesTargetCheck) && is_file($filesTargetCheck)){
            require_once $filesTargetCheck;
        }else{
            require_once RACINE.DS.'public'.DS.'parts'.DS.$match['target'].'.php';
        }
        $contentForLayout = ob_get_clean();
        $fileThemeCheck = RACINE.DS.'public'.DS.'templates'.DS.$themeForLayout.DS.$themeForLayout.'.php';
        //si un fichier de personalisation theme exist on l'inclu sinon on inclu le fichier par defaut
        if(preg_match("#\.(php)$#",strtolower($fileThemeCheck)) && file_exists($fileThemeCheck) && is_file($fileThemeCheck)){
            require_once $fileThemeCheck;
        }else{
            require_once RACINE.DS.'public'.DS.'templates'.DS.$themeForLayout.'.php';
        }

}else{
    $App->setFlash('Cette page n\'éxiste pas redirection sur la page d\'erreur','orange');
    http_response_code(404);
    $App->redirect($router->routeGenerate('error'));
}
