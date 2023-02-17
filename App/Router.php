<?php

namespace App;

class Router {

    /**
     * @var string
     */
    private $viewPath;

    /** 
     * @var AltoRouter
    */
    private $router;

    public function __construct(string $viewPath = null)
    {
        $this->viewPath = $viewPath;
        $this->router = new \AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null): self
    {   
        $this->router->map('GET|POST', $url, $view, $name);  
        return $this;
    }

    public function run(): self
    {
        
        $match = $this->router->match();

        if (is_array($match)){

            if(is_callable($match['target'])){
          
              call_user_func_array($match['target'], $match['params']);
          
            }else{
          
                  $params = $match['params'];
                  $getUri = explode('/', $_SERVER['REQUEST_URI']);
                  // if where get administration
                  if($getUri[1] == 'admin'){
                    // theme var dashboard
                    $themeForLayout = "dashboard-default";
                    // si on est pas admin
                    is_admin();
                    //buffer
                    ob_start();
                    //logic
                    require '..'.DS.'public'.DS.'templates'.DS.'dashboard-default'.DS.'modules'.DS.$match['target'].'.func.php';
                    //templates parts
                    require '..'.DS.'public'.DS.'templates'.DS.'dashboard-default'.DS.$match['target'].'.php';
                    $contentForLayout = ob_get_clean();
                    require_once '..'.DS.'public'.DS.'templates'.DS.$themeForLayout.DS.$themeForLayout.'.php';
                    
                  }else{
                    // theme var
                    $themeForLayout = "reup";
                    // buffer
                    ob_start();
                    //templates parts
                    //logic
                    require '..'.DS.'public'.DS.'modules'.DS.$match['target'].'.func.php';
                    require '..'.DS.'public'.DS.'templates'.DS.$themeForLayout.DS.$match['target'].'.php';
            
                    $contentForLayout = ob_get_clean();
                    require_once '..'.DS.'public'.DS.'templates'.DS.$themeForLayout.DS.$themeForLayout.'.php';
          
                  }
            }
          
          }else{
          
              setFlash('<strong>Ho ho !</strong> cette page n\'éxiste pas redirection sur la page d\'erreur','orange');
              redirect($match->generate('error'));
          
          }
          return $this;

    }

    /*public function run(): self
    {
        $match = $this->router->match();
        $view = $match['target'];
        var_dump($view);

        ob_start();
        require 'public'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$view.'.func.php';
        require 'public'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'reup'.DIRECTORY_SEPARATOR.$view.'.php';


        $content = ob_get_clean();
        require_once DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'reup'.DIRECTORY_SEPARATOR.'reup'.'.php';

        return $this;
    }*/
}