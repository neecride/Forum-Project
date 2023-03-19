<?php

namespace Action;

use App;
use Framework;

class AdminUsersAction{


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
     * getUsers retourne tout les utlisateurs de la bdd
     *
     * @return void
     */
    public function getUsers()
    {
        return $this->cnx->Request("SELECT * FROM users ORDER BY date_inscription");
    }

    /**
     * getUser retourne 1 utilisateur
     *
     * @return mixed
     */
    public function getUser()
    {
        if(!empty($this->router->matchRoute()['params']['id']))
        {
            $id = (int) $this->router->matchRoute()['params']['id'];
            return $this->cnx->Request("SELECT * FROM users WHERE id = ?",[$id],1);
        }
    }

    /**
     * getId vérifie si on a bien un id en GET et qu'il correspond a la bdd
     *
     * @return self
     */
    public function getId(): self
    {
        if(isset($this->router->matchRoute()['params']['id']) && $this->router->matchRoute()['params']['id'] != $this->getUser()->id)
        {
            $this->app->setFlash('Un problème est survenue <strong> aucun utilisateurs avec cet ID </strong>','orange');
            $this->app->redirect($this->router->routeGenerate('user'));
        }
        return $this;
    }

    /**
     * userEdit edite un utilisateur 
     *
     * @return self
     */
    public function userEdit(): self
    {
        if(isset($_POST['users']) && isset($this->router->matchRoute()['params']['id']))
        {
            $this->app->methodPostValid('POST');
            $this->session->checkCsrf();
            $slug = strip_tags(trim($_POST['slug']));
            $username = strip_tags(trim($_POST['name']));
            $this->validator->validName($username)
                            ->optionValidation($slug,'admin|modo|membre');
            if($this->validator->isValid()){
                    if($slug === "admin"){
                        $authorization = (int) 3;
                    }elseif($slug === "modo"){
                        $authorization = (int) 2;
                    }else{
                        $authorization = (int) 1;
                    }
                    $id = (int) $this->router->matchRoute()['params']['id'];
                    $this->cnx->Request("UPDATE users SET username = ?, slug = ?, authorization = ? WHERE id = ?",[$username,$slug,$authorization,$id]);
                    $this->app->setFlash('Votre utilisateur a bien étais modifier');
                    $this->app->redirect($this->router->routeGenerate('user'));
            }
            $this->errors = $this->validator->getErrors();
        }
        return $this;
    }

    /**
     * activUser active un utilisateur après un ban par exemple
     *
     * @return self
     */
    public function activUser(): self
    {
        if(isset($this->router->matchRoute()['params']['activ']))
        {
            $this->app->methodPostValid('GET');
            $this->session->checkCsrf();
            if($this->router->matchRoute()['params']['rank'] == 3){
                $this->app->setFlash('On ne peut pas désactivé ou supprimé un admin','rouge');
                $this->app->redirect($this->router->routeGenerate('user'));
            }else{
                $id = (int) $this->router->matchRoute()['params']['activ'];
                $this->cnx->Request("UPDATE users 
                                    SET slug = 'membre', activation = '1', authorization = '1' ,confirmed_token = null, confirmed_at = NOW() 
                                    WHERE id = ?",[$id]);
                $this->app->setFlash("L'utilisateur a bien étais mis a jour");
                $this->app->redirect($this->router->routeGenerate('user'));
            }
        }
        return $this;
    }

    /**
     * unactivUser désactive un utilisateur avec un ban par exemple
     *
     * @return self
     */
    public function unactivUser(): self
    {
        if(isset($this->router->matchRoute()['params']['unactiv']))
        {
            $this->app->methodPostValid('GET');
            $this->session->checkCsrf();
            if($this->router->matchRoute()['params']['rank'] == 3){
                $this->app->setFlash('On ne peut pas désactivé ou supprimé un admin','rouge');
                $this->app->redirect($this->router->routeGenerate('user'));
            }else{
                $id = (int) $this->router->matchRoute()['params']['unactiv'];
                $this->cnx->Request("UPDATE users SET activation = 0 WHERE id = ?",[$id]);
                $this->app->setFlash("L'utilisateur a bien étais mis a jour");
                $this->app->redirect($this->router->routeGenerate('user'));
            }
        }
        return $this;
    }


}