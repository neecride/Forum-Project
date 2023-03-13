<?php

namespace App;

Class AccountAction{
	
	public $errors;
	private App $app;
	private Database $cnx;
	private Router $router;
	private Session $session;
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

	public function editMdp(): self
	{
		if(isset($_POST['pwd'])){

			$this->app->methodPostValid('POST');
			$this->session->checkCsrf();
		
			$pass = trim($_POST['password']);
			$password_confirm = trim($_POST['password_confirm']);
		
			
			$validator = $this->getValidator()
			->notEmpty($pass,$password_confirm)
			->isDifferent($pass,$password_confirm)
			->validMdp($pass);

			if($validator->isValid()){
				$user_id = (int) $_SESSION['auth']->id;
				$password = trim(password_hash($pass, PASSWORD_BCRYPT));
				$this->cnx->Request("UPDATE users SET password = ? WHERE id = ?",[$password, $user_id]);
				$_SESSION['auth']->password = $password;
				$this->app->setFlash('Votre mots de pass a bien étais modifier');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}
			$this->errors = $validator->getErrors();
		
		}
		return $this;
	}

	public function editEmail(): self
	{
		if(isset($_POST['edit-email'])){

			$this->app->methodPostValid('POST');

			$this->session->checkCsrf();
		
			$profil_id = (int) $_SESSION['auth']->id;
		
			$email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
		
			$email_confirm = trim(filter_var($_POST['emailConfirm'], FILTER_SANITIZE_EMAIL));
	
			$exist = $this->cnx->request('SELECT email FROM users WHERE email = ?',[$email],1);

			$validator = $this->getValidator()
				->notEmpty($email,$email_confirm)
				->isDifferent($email,$email_confirm)
				->validEmail($email_confirm)
				->validEmail($email)
				->isReqExist($exist);

			if($validator->isValid()){
				$this->cnx->Update("UPDATE users SET email = ? WHERE id = ?",[$email, $profil_id]);
				$this->app->setFlash('Votre email a bien étais modifier');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}
			$this->errors = $validator->getErrors();
		
		}
		return $this;
	}

	public function delAvatar(): self
	{
		if(isset($_POST['delete-avatar'])){

			$this->app->methodPostValid('POST');

			$this->session->checkCsrf();
			
			$file = $_SESSION['auth']->avatar;
		
			$validator = $this->getValidator()
				->fileExist($file,'inc/img/avatars/');
			
			if($validator->isValid())
			{
				$profil_id = intval($_SESSION['auth']->id);	
				unlink('inc/img/avatars/' . $file);
				$this->cnx->Update("UPDATE users SET avatar = ? WHERE id = ?",[null,$profil_id]);
				$this->app->setFlash('L\'avatar a bien été supprimer');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}
			$this->errors = $validator->getErrors();
		}
		return $this;
	}

	private function getValidator()
	{
		return new Validator();
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
