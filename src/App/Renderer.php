<?php

namespace App;

use Framework\Router;

class Renderer {

	public function __construct()
	{}

    /**
     * app 
     *
     * @return mixed
     */
    public function app()
    {
        return new App;
    }

    /**
     * PerPage
     *
     * @return mixed
     */
    public function Params()
    {
        return new Parameters;
    }

    /**
     * ThisRoute retourn une instance de routeur
     *
     * @return string
     */
    public function ThisRoute()
    {
        $router = new Router;
        return $router;
    }

    public function isNotExistPage()
    {
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
    }
  
  /**
   * render rend les vues utilisateurs
   *
   * @param  mixed $fichier
   * @param  mixed $data
   * @return void
   */
    public function render(string $fichier,array $data = [])
    {
        //bug avec les références a testé
        //call_user_func_array($match['target'], [&$match['params']]);
        $app              = $this->app();
        $router           = new Router;
        $match            = $router->matchRoute();
        $Parsing          = new Parsing;
        $GetParams        = $this->Params();
        $session          = new Session;

        // on extrait les données des contoller
        extract($data);

        ob_start();
        require_once (RACINE.DS.'public'.DS.'templates'.DS.$GetParams->themeForLayout().DS.'parts'.DS.$fichier.'.php');
        $contentForLayout = ob_get_clean();
        require_once (RACINE.DS.'public'.DS.'templates'.DS.$GetParams->themeForLayout().DS.'theme.php');
    }
  
  /**
   * renderAdmin rend les vues Administrateurs
   *
   * @param  mixed $fichier
   * @param  mixed $data
   * @return void
   */
    public function renderAdmin(string $fichier,array $data = [])
    {

        $app              = $this->app();
        $router           = new Router;
        $match            = $router->matchRoute();
        $Parsing          = new Parsing;
        $GetParams        = $this->Params();
        $session          = new Session;

        extract($data);

        $getUri = explode('/', $_SERVER['REQUEST_URI']);
        if($getUri[1] == 'admin'){
        $app->isAdmin();
        }
        ob_start();
        require_once (RACINE.DS.'public'.DS.'admin'.DS.'parts'.DS.$fichier.'.php');
        $contentForLayout = ob_get_clean();
        require_once (RACINE.DS.'public'.DS.'admin'.DS.'theme.php');
    }

}