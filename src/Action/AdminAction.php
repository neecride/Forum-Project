<?php

namespace Action;

use App;
use Framework;

/**
 * AdminAction gère toutes les requete et formulaire de l'administration
 */
class AdminAction {

    public $errors;
	private App\App $app;
	private App\Database $cnx;
	private App\Validator $validator;
	private Framework\Router $router;
    private App\Parameters $parameters;
	private App\Session $session;

	public function __construct()
	{
		$this->app 			= new App\App;
		$this->cnx 			= new App\Database;
		$this->router 		= new Framework\Router;
		$this->session 		= new App\Session;
		$this->validator 	= new App\Validator;
        $this->parameters   = new App\Parameters;
 	}


    /**
	 * checkError affiche les erreurs dans la vue
	 *
	 * @return void
	 */
	public function checkError()
	{
		if(!is_null($this->errors))
		{
			return "<div class=\"notify notify-rouge\"><div class=\"notify-box-content\"><li class=\"errmode\">". implode("</li><li class=\"errmode\">",$this->errors) ."</li></div></div>";
		}
	}

    /**
     * fieldRequest affiche les valeurs de la bdd dans les inputs
     *
     * @return void
     */
    public function fieldRequest()
    {
        return $this->cnx->Request("SELECT * FROM parameters");
    }
    
    /**
     * slogan modifie le slogan du site
     *
     * @return self
     */
    public function slogan(): self
    {
        if(isset($_POST['btnSlogan']))
        {
            $this->app->methodPostValid('POST');
            $this->session->checkCsrf();
            $name = strip_tags(trim($_POST['slogan']));
            $edit = (int) trim(filter_var($this->parameters->GetParam(0,'param_id'),FILTER_SANITIZE_NUMBER_INT));
            $this->validator->validTtitle($name);
            if($this->validator->isValid())
            {
                $u = [$name, $edit];
                $this->cnx->Request("UPDATE parameters SET param_value = ? WHERE param_id = ?",$u);
                $this->app->setFlash('Le slogan a bien été modifier');
                $this->app->redirect($this->router->routeGenerate('admin'));
            }
            $this->errors = $this->validator->getErrors();
        }
        return $this;
    }
    
    /**
     * siteName modifie le nom du site
     *
     * @return self
     */
    public function siteName(): self
    {
        if(isset($_POST['btnNameSite']))
        {
            $this->app->methodPostValid('POST');
            $this->session->checkCsrf();
            $name = strip_tags(trim($_POST['sitename']));
            $edit = (int) trim(filter_var($this->parameters->GetParam(1,'param_id'),FILTER_SANITIZE_NUMBER_INT));
            $this->validator->validTtitle($name);
            if($this->validator->isValid())
            {
                $u = [$name, $edit];
                $this->cnx->Request("UPDATE parameters SET param_value = ? WHERE param_id = ?",$u);
                $this->app->setFlash('Le nom du site a bien été modifier');
                $this->app->redirect($this->router->routeGenerate('admin'));
            }
            $this->errors = $this->validator->getErrors();
        }
        return $this;
    }

    
    /**
     * paginationPerPage modifie le nombre de page pour la pagination
     *
     * @return self
     */
    public function paginationPerPage(): self
    {
        if(isset($_POST['btnTopicPerPage']))
        {
            $this->app->methodPostValid('POST');
            $this->session->checkCsrf();
            $name = (int) trim(filter_var($_POST['forumpager'],FILTER_SANITIZE_NUMBER_INT));
            $edit = (int) trim(filter_var($this->parameters->GetParam(2,'param_id'),FILTER_SANITIZE_NUMBER_INT));
            $this->validator->optionValidation($name,'10|15|20');
            if($this->validator->isValid())
            {
                $u = [$name, $edit];
                $this->cnx->Request("UPDATE parameters SET param_value = ? WHERE param_id = ?",$u);
                $this->app->setFlash('Le theme a bien été modifier');
                $this->app->redirect($this->router->routeGenerate('admin'));
            }
            $this->errors = $this->validator->getErrors();
        }
        return $this;
    }
    
    /**
     * themeUpdate change le template 
     *
     * @return self
     */
    public function themeUpdate(): self
    {
        if(isset($_POST['btnThemeName']))
        {
            $this->app->methodPostValid('POST');
            $this->session->checkCsrf();
            $name = strip_tags(trim($_POST['themeforlayout']));
            $edit = (int) trim(filter_var($this->parameters->GetParam(3,'param_id'),FILTER_SANITIZE_NUMBER_INT));
            $this->validator->validThemeName($name);

            if($this->validator->isValid())
            {
                $u = [$name, $edit];
                $this->cnx->Request("UPDATE parameters SET param_value = ? WHERE param_id = ?",$u);
                $this->app->setFlash('Le theme a bien été modifier');
                $this->app->redirect($this->router->routeGenerate('admin'));
            }
            $this->errors = $this->validator->getErrors();
        }
        return $this;
    }
    
    /**
     * alertForm modifie le contenu|titre de l'alert
     *
     * @return self
     */
    public function alertForm(): self
    {
        if(isset($_POST['btnAlertForm']))
        {
            $this->app->methodPostValid('POST');
            $this->session->checkCsrf();
            $name = strip_tags(trim($_POST['alertTitle']));
            $value = strip_tags(trim($_POST['alertContent']));
            $edit = (int) trim(filter_var($this->parameters->GetParam(4,'param_id'),FILTER_SANITIZE_NUMBER_INT));
            $this->validator->validTtitle($name)->betweenLength($value,30 , 500);
            if($this->validator->isValid())
            {
                $this->cnx->Request("UPDATE parameters SET param_name = ?, param_value = ? WHERE param_id = ?",[$name, $value, $edit]);
                $this->app->setFlash('Le titre et le contenue du widget alert a bien été modifier');
                $this->app->redirect($this->router->routeGenerate('admin'));
            }
            $this->errors = $this->validator->getErrors();
        }
        return $this;
    }

    /**
     * alerColor change la couleur de l'alert
     *
     * @return self
     */
    public function alerColor(): self
    {
        if(isset($_POST['btnAlertColor']))
        {
            $this->app->methodPostValid('POST');
            $this->session->checkCsrf();
            $name = strip_tags(trim($_POST['alertColor']));
            $edit = (int) trim(filter_var($this->parameters->GetParam(4,'param_id'),FILTER_SANITIZE_NUMBER_INT));
            $this->validator->optionValidation($name,'turquoise|jaune|gris|rouge|orange|marine|bleu|violet|vert');
            if($this->validator->isValid())
            {
                $u = [$name, $edit];
                $this->cnx->Request("UPDATE parameters SET param_color = ? WHERE param_id = ?",$u);
                $this->app->setFlash('La couleur a bien été pris en compte');
                $this->app->redirect($this->router->routeGenerate('admin'));
            }
            $this->errors = $this->validator->getErrors();
        }
        return $this;
    }

    /**
     * activWidget active|desactive le widget aler
     *
     * @return self
     */
    public function activWidget(): self
    {
        if(isset($_POST['btnActivAlert']))
        {
            $this->app->methodPostValid('POST');
            $this->session->checkCsrf();
            $name = strip_tags(trim($_POST['activAlert']));
            $edit = (int) trim(filter_var($this->parameters->GetParam(4,'param_id'),FILTER_SANITIZE_NUMBER_INT));
            $this->validator->optionValidation($name,'oui|non');
            if($this->validator->isValid())
            {
                $u = [$name, $edit];
                $this->cnx->Request("UPDATE parameters SET param_activ = ? WHERE param_id = ?",$u);
                $this->app->setFlash("Le widget Alert a bien été acitver|desactiver");
                $this->app->redirect($this->router->routeGenerate('admin'));
            }
            $this->errors = $this->validator->getErrors();
        }
        return $this;
    }

}
