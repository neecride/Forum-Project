<?php

namespace App;

use Ausi\SlugGenerator\SlugGenerator;
use Framework;
use Action;

class Renderer {

    const DEFAULT_NAMESPACE = '__MAIN';

    private $paths = [];

    /**
     * globals variables accessible pour toutes les vues
     *
     * @var array
     */
    private $globals = [];
	private App $app;
    private Forum $forum;
	private Framework\Router $router;
    private Parsing $parsing;
    private Pagination $pagination;
	private Parameters $parameters;
    private SlugGenerator $generator;

	public function __construct()
	{
		$this->app          = new App;
        $this->forum        = new Forum;
		$this->router       = new Framework\Router;
        $this->parsing      = new Parsing;
        $this->pagination   = new Pagination;
		$this->parameters   = new Parameters;
        $this->generator    = new SlugGenerator;
	}

    public function addPath(string $namespace, ?string $path = null): void
    {
        if(is_null($path)){
            $this->paths[self::DEFAULT_NAMESPACE] = $namespace;
        }else{
            $this->paths[$namespace] = $path;
        }
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
    
    /**
     * addGlobal retourne les variables global a l'application
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    public function render($db,array $params = []): self
    {
        if(is_array($this->router->matchRoute()))
        {

            extract($this->globals);
            extract($params);

            $App              = $this->app;
            $router           = $this->router;
            $generator        = $this->generator;
            $pagination       = $this->pagination;
            $Parsing          = $this->parsing;
            $GetParams        = $this->parameters;
            $forum            = $this->forum;
            $Account          = new Action\AccountAction;
            $themeForLayout   = $this->parameters->themeForLayout();
            $match            = $this->router->matchRoute();
        
            $getUri = explode('/', $_SERVER['REQUEST_URI']);
        
            ob_start();
            if($getUri[1] == 'admin'){
              $this->app->isAdmin();
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
          $this->app->setFlash('Cette page n\'éxiste pas redirection sur la page d\'erreur','orange');
          http_response_code(404);
          $this->app->redirect($router->routeGenerate('error'));
        }
        return $this;
    }
}