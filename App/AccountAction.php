<?php

namespace App;

Class AccountAction{
	
	private $count;
	private App $app;
	private Database $cnx;
	private Router $router;
	private Session $session;
	private $validator;
	public $errors;
	private Parameters $parameters;

	public function __construct()
	{
		$this->app = new App;
		$this->cnx = new Database;
		$this->router = new Router;
		$this->session = new Session;
		$this->parameters = new Parameters;
 	}

	public function checkError()
	{
		if(!is_null($this->errors)){
			return "<div class=\"notify notify-rouge\"><div class=\"notify-box-content\"><li class=\"errmode\">". implode("</li><li class=\"errmode\">",$this->errors) ."</li></div></div>";
		}
	}

	public function test(): self
	{
		if(isset($_POST['go'])){

			$this->session->checkCsrf();
			var_dump($_POST);
			$premier = $_POST['premier'];
			$deux = $_POST['deux'];
			$trois = $_POST['trois'];
		
			$this->validator = $this->getValidator(
				[
					$premier,
					$deux,
					$trois
				]
				)
				->required($premier,$deux,$trois)
				->isDifferent($deux,$trois)
				->validName($premier)
				->validName($trois);
			
			if($this->validator->isValid())
			{
				var_dump($this->validator);
				var_dump('ça fonctionne pas erreur');
			}
			$this->errors = $this->validator->getErrors();
			var_dump($this->errors);
		}
		return $this;
	}

	public function delAvatar(string $field): self
	{
		if(isset($_POST[$field])){

			$this->session->checkCsrf();
			
			$file = $_SESSION['auth']->avatar;
		
			$this->validator = $this->getValidator([$field => $file])
				->fileExist($file);
			
			if($this->validator->isValid())
			{
				$profil_id = intval($_SESSION['auth']->id);	
				unlink('inc/img/avatars/' . $file);
				$this->cnx->Update("UPDATE users SET avatar = ? WHERE id = ?",[null,$profil_id]);
				$this->app->setFlash('L\'avatar a bien été supprimer');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}
			$this->errors = $this->validator->getErrors()[$file];
		}
		return $this;
	}

	private function getValidator($request)
	{
		return new Validator($request);
	}

	//check les utilisateur
	public function userAccount(){

		$user_id = (int) $_SESSION['auth']->id;

		return $this->cnx->Request('SELECT
			users.id as userid,
			users.username,
			users.email,
			users.slug,
			users.avatar,
			users.description,
			users.date_inscription,
			users.userurl,
			users_themes.user_theme
			
			FROM users

			LEFT JOIN users_themes ON users.id = users_themes.user_id

			WHERE users.id = ?

		',[$user_id],1);
	}


}
