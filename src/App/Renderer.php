<?php

namespace App;

use Psr\Container\ContainerInterface;
use Action;

class Renderer {

    private $router;
    private $match;

	private App $app;
    private Action\ForumAction $forum;
    private Parsing $parsing;
    private Pagination $pagination;
	private Parameters $parameters;
    
    /**
     * container
     * @var ContainerInterface
     * @var mixed
     */
    private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->app          = new App;
        $this->forum        = new Action\ForumAction;
        $this->parsing      = new Parsing;
        $this->pagination   = new Pagination;
		$this->parameters   = new Parameters;
        $this->container    = $container;
        $this->router       = $this->container->get(\Framework\Router::class);
        $this->match        = $this->container->get(\Framework\Router::class)->matchRoute();

	}

    public function isNotExistPage(): self
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
        return $this;
    }

    public function render(): self
    {
        if(is_array($this->container->get(\Framework\Router::class)->matchRoute()))
        {
            global $db;

            $app              = $this->app;
            $router           = $this->router;
            $match            = $this->match;
            $pagination       = $this->pagination;
            $Parsing          = $this->parsing;
            $GetParams        = $this->parameters;
            $forum            = $this->forum;
            $folderLayout     = $this->parameters->themeForLayout();
            $index            = $this;


            $getUri = explode('/', $_SERVER['REQUEST_URI']);
            if($getUri[1] == 'admin'){
              $this->app->isAdmin();
            }

            ob_start();
            //on supprimera cette condition dans le futur
            $fileLogic = RACINE.DS.'lib'.DS.'modules'.DS.$match['target'].'.func.php';
            if(preg_match("#\.(php)$#",strtolower($fileLogic)) && file_exists($fileLogic) && is_file($fileLogic)){
                require_once $fileLogic;
            }
            ////on supprimera cette condition dans le futur
            $filesTargetCheck = RACINE.DS.'public'.DS.'templates'.DS.$folderLayout.DS.'parts'.DS.$match['target'].'.php';

            //on inclu les modules
            if(preg_match("#\.(php)$#",strtolower($filesTargetCheck)) && file_exists($filesTargetCheck) && is_file($filesTargetCheck)){
              require_once $filesTargetCheck;
            }

            $contentForLayout = ob_get_clean();
            $fileThemeCheck = RACINE.DS.'public'.DS.'templates'.DS.$folderLayout.DS.$folderLayout.'.php';
            //conteneur des modules
            if(preg_match("#\.(php)$#",strtolower($fileThemeCheck)) && file_exists($fileThemeCheck) && is_file($fileThemeCheck)){
              require_once $fileThemeCheck;
            }
        }else{
          $this->app->setFlash("Cette page n'éxiste pas redirection sur la page d'erreur",'orange');
          http_response_code(404);
          $this->app->redirect($this->router->routeGenerate('error'));
        }
        return $this;
    }

    /**
     * widget retourne des widgets 
     *
     * @return mixed
     */
    public function widget()
    {
        $match              = $this->router->matchRoute();
        $router             = $this->router;
        $App                = $this->app;
        $folderLayout       = $this->parameters->themeForLayout();
        $fileUrl = RACINE.DS.'public'.DS.'templates'.DS.$folderLayout.DS.'parts'.DS.'widgets';
        $scandir = scandir($fileUrl);
        $activeWidget = "oui";
        $inpage = in_array($match['target'], ['home','forum','viewtopic','viewforums','survey']);
        if($activeWidget == "oui" && $inpage){
            echo '<div class="col-md-3">';
            echo '<div class="section-title-nav">';
            echo '<h5>Widget</h5>';
            echo '</div>';
            foreach($scandir as $fichier)
            {
                if(preg_match("#\.(php)$#",strtolower($fichier)) && !is_null($scandir)){
                    require RACINE.DS.'public'.DS.'templates'.DS.$folderLayout.DS.'parts'.DS.'widgets'.DS.$fichier;
                }
            }
            echo '</div>';
        }
    }
}
