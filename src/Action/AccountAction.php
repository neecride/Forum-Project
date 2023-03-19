<?php

namespace Action;

use App;
use Framework;

Class AccountAction{
	
	public $errors;
	private App\App $app;
	private App\Database $cnx;
	private App\Validator $validator;
	private Framework\Router $router;
	private App\Session $session;

	public function __construct()
	{
		$this->app 			= new App\App;
		$this->cnx 			= new App\Database;
		$this->router 		= new Framework\Router;
		$this->session 		= new App\Session;
		$this->validator 	= new App\Validator;
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
	 * editMdp permet d'édité le mot de pass
	 *
	 * @return self
	 */
	public function editMdp(): self
	{
		if(isset($_POST['pwd'])){

			$this->app->methodPostValid('POST');
			$this->session->checkCsrf();

			$pass = trim($_POST['password']);
			$password_confirm = trim($_POST['password_confirm']);

			$this->validator
				->notEmpty($pass,$password_confirm)
				->isDifferent($pass,$password_confirm)
				->validMdp($pass);

			if($this->validator->isValid()){
				$user_id = (int) $_SESSION['auth']->id;
				$password = trim(password_hash($pass, PASSWORD_BCRYPT));
				$this->cnx->Request("UPDATE users SET password = ? WHERE id = ?",[$password, $user_id]);
				$_SESSION['auth']->password = $password;
				$this->app->setFlash('Votre mots de pass a bien étais modifier');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}
			$this->errors = $this->validator->getErrors();

		}
		return $this;
	}
	
	/**
	 * editEmail permet d'édité l'adresse email
	 *
	 * @return self
	 */
	public function editEmail(): self
	{
		if(isset($_POST['edit-email'])){

			$this->app->methodPostValid('POST');
			$this->session->checkCsrf();
			$profil_id = (int) $_SESSION['auth']->id;
			$email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
			$email_confirm = trim(filter_var($_POST['emailConfirm'], FILTER_SANITIZE_EMAIL));
			$exist = $this->cnx->request('SELECT email FROM users WHERE email = ?',[$email],1);
			$this->validator
				->notEmpty($email,$email_confirm)
				->isDifferent($email,$email_confirm)
				->validEmail($email_confirm)
				->validEmail($email)
				->isReqExist($exist);
			if($this->validator->isValid()){
				$this->cnx->Update("UPDATE users SET email = ? WHERE id = ?",[$email, $profil_id]);
				$this->app->setFlash('Votre email a bien étais modifier');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}
			$this->errors = $this->validator->getErrors();
		
		}
		return $this;
	}
	
	/**
	 * delAvatar permet de supprimé l'avatar
	 *
	 * @return self
	 */
	public function delAvatar(): self
	{
		if(isset($_POST['delete-avatar'])){

			$this->app->methodPostValid('POST');

			$this->session->checkCsrf();

			$file = $_SESSION['auth']->avatar;

			$this->validator->fileExist($file,'inc/img/avatars/');

			if($this->validator->isValid())
			{
				$profil_id = intval($_SESSION['auth']->id);	
				unlink('inc/img/avatars/' . $file);
				$this->cnx->Update("UPDATE users SET avatar = ? WHERE id = ?",[null,$profil_id]);
				$this->app->setFlash('L\'avatar a bien été supprimer');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}
			$this->errors = $this->validator->getErrors();
		}
		return $this;
	}
	
	/**
	 * postAvatar post un avatar
	 *
	 * @return self
	 */
	public function postAvatar(): self
	{
		if(isset($_POST['avatar']) && !empty($_FILES['avatar']['tmp_name']))
		{
			$this->app->methodPostValid('POST');
			$this->session->checkCsrf();

			if(!is_uploaded_file($_FILES['avatar']['tmp_name'])){
				$this->app->setFlash("Un problème a eu lieu lors de l'upload",'orange');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}

			//on initialise l'id
			$profil_id = (int) $_SESSION['auth']->id;
			$avatar = $_FILES['avatar'];
			//on définie le nom de l'image
			$avatar_name = $avatar['name'];
			//on definie l'extension
			$extension = strtolower(substr($avatar_name, -3));
			//toutes les extensions n'on pas que 3 caractères
			//$extensionAllowed = strtolower(substr(strrchr($avatar_name, '.'),1));
			//on renome l'image avant envoie avec l'id de l'utilisateur
			$save_name = md5($profil_id).'.'.$extension;
			//taille du fichier envoyez
			$pdsfile = filesize($_FILES['avatar']['tmp_name']);
			//1go en octets 1048576
			$max_size = 40000; //30ko
			//on definie l'extension autoriser
			$extensionAllowed = ['png','jpg'];
			$this->validator
				->sizeFileUpload($pdsfile,$max_size)
				->extensionAllowed($extension, $extensionAllowed);

			if($this->validator->isValid())
			{
				move_uploaded_file($avatar['tmp_name'], 'inc/img/avatars/'.$save_name);
				$this->cnx->Request("UPDATE users SET avatar = ? WHERE id = ?",[$save_name, $profil_id]);
				$_SESSION['auth']->avatar = $save_name;
				$this->app->setFlash('Votre avatar a bien étais ajouter');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}
			$this->errors = $this->validator->getErrors();
		}
		return $this;
	}
	
	/**
	 * postDescription met a jour la description
	 *
	 * @return self
	 */
	public function postDescription(): self
	{
		if(isset($_POST['edit-profil']))
		{
			$this->app->methodPostValid('POST');
			$this->session->checkCsrf();
			$profil_id = (int) $_SESSION['auth']->id;

			$description = trim(strip_tags($_POST['description']));

			$this->validator->maxLength($description,200);
			if($this->validator->isValid())
			{
				$this->cnx->Request("UPDATE users SET description = ? WHERE id = ?",[$description, $profil_id]);
				$this->app->setFlash('Votre profil a bien étais modifier');
				$this->app->redirect($this->router->routeGenerate('account-edit'));
			}
			$this->errors = $this->validator->getErrors();
		}
		return $this;
	}
	
	/**
	 * desactivAccount permet de desactiver le profil utilisateur 
	 *
	 * @return self
	 */
	public function desactivAccount(): self
	{
		if(isset($_POST['lock-account'])){
			$this->app->methodPostValid('POST');
			
			if(!empty($_SESSION['auth']->authorization === 3)){
				$this->app->setFlash('Pas possible de supprimer un administrateur','orange');
				$this->app->redirect($this->router->routeGenerate('home'));
			}else {

			  $this->session->checkCsrf();
			  $id = (int) $_SESSION['auth']->id;
			  $this->cnx->Request("UPDATE users SET activation = 0 WHERE id = ?",[$id]);
			  $_SESSION = array();
			  setcookie('remember', NULL, -1);
			  $this->app->setFlash('Votre compte a bien étais désactiver');
		  
			  $this->app->redirect($this->router->routeGenerate('home'));
		  
			}
		  
		  }
		  return $this;
	}
	
	/**
	 * userAccount affiche tout les utilisateurs
	 *
	 * @return mixed
	 */
	public function userAccount()
	{
		return $this->cnx->Request('SELECT * FROM users WHERE id = ?',[intval($_SESSION['auth']->id)],1);
	}


}
