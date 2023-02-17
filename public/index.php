<?php
if(session_status() === PHP_SESSION_NONE){ //on verifie si les session ne sont pas déjà démmarer
    session_start(); //et on demarre les sessions
}
//session_regenerate_id(); //Remplace l'identifiant de session courant par un nouveau
//phpinfo();
//on inclu les diferente librairie fonction etc...
define('DS', DIRECTORY_SEPARATOR);
define('RACINE', dirname(__DIR__));
require RACINE.DS.'vendor'.DS.'autoload.php';
use Ausi\SlugGenerator\SlugGenerator;
$generator = new SlugGenerator;
$router = new AltoRouter;
$Parsing = new App\Parsing;
$GetParams = new App\Parameters;
$themeForLayout = $GetParams->themeForLayout($GetParams->GetParam(3));
/* del after dev */
//define('DEBUG_TIME' , microtime(true));
/*$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();*/

require_once (RACINE.DS.'lib'.DS.'libs-includes.php');

if(isset($_GET['page']) && $_GET['page'] === '1'){

  $uri = explode('?',$_SERVER['REQUEST_URI'])[0];
  $get = $_GET;
  unset($get['page']);
  $query = http_build_query($get);
  if(!empty($query)){
    $uri = $uri . '?' . $query;
  }
  header('Location:' . $uri);
  http_response_code(301);
  exit();
}

if(is_array($match)){

  $params = $match['params'];
  $getUri = explode('/', $_SERVER['REQUEST_URI']);

  ob_start();
  if($getUri[1] == 'admin'){
    is_admin();
    require RACINE.DS.'lib'.DS.'modules'.DS.$match['target'].'.func.php';
    require RACINE.DS.'public'.DS.'templates'.DS.'dashboard-default'.DS.$match['target'].'.php';
  }else{
    require RACINE.DS.'lib'.DS.'modules'.DS.$match['target'].'.func.php';
    require RACINE.DS.'public'.DS.'parts'.DS.$match['target'].'.php';
  }
  $contentForLayout = ob_get_clean();
  require_once RACINE.DS.'public'.DS.'templates'.DS.$themeForLayout.'.php';
  

}else{

    setFlash('<strong>Ho ho !</strong> cette page n\'éxiste pas redirection sur la page d\'erreur','orange');
    http_response_code(404);
    redirect($router->generate('error'));

}
