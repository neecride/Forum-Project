<?php

namespace App;

use Framework;

class App{
	
	private Framework\Router $router;

	public function __construct(array $module= [], array $dependencies = [])
    {
		$this->router = new Framework\Router;
		if(array_key_exists('renderer', $dependencies))
		{
			$dependencies['renderer']->addGlobal('router', $this->router);
		}
    }

    public static function DD($dd){

		echo '<pre style="background:#fff;">';
		print_r($dd);
		echo '</pre>';

    }

	public function StrRandom($length){
	    
	    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
	    
	    return substr(str_shuffle(str_repeat($alphabet, $length)), 0,$length);
	    
	}

    /*************
	* trucast long titre
	*************/
	public function trunque($str, $nb = '') {
		if (strlen($str) > $nb) {
			$str = substr($str, 0, $nb);
			$position_espace = strrpos($str, " ");
			$texte = substr($str, 0, $position_espace); 
			$str = $str."...";
		}
		return $str;
	}

	function isNotConnect()
	{
		 if(!isset($_SESSION['auth'])){
			$this->setFlash('Vous devez être connecter pour acceder a cette page','orange');
			$this->redirect($this->router->routeGenerate('home'));
		}
	}

	public function isLogged()
	{
	    if(!empty($_SESSION['auth'])){
	        $this->setFlash('Vous êtes déjà connecter','orange');
	        $this->redirect($this->router->routeGenerate('home'));
	    } 
	}

	public function isAdmin(){
	    if(!in_array($_SESSION["auth"]->authorization, [3])){
	        $this->setFlash("Vous n'avez pas acces a cette page <strong> réserver au admin </strong>",'orange');
	        $this->redirect($this->router->routeGenerate('home'));
	    } 
	}

	public function methodPostValid(string $key): self
	{
		$method = mb_strtoupper($_SERVER['REQUEST_METHOD'] ?? $key);
		if ($method !== $key) {
			$this->setFlash("Le formulaire n'est pas un formulaire $key");
			$this->redirect($this->router->routeGenerate('error'));
		}
		return $this;
	}
	

	
	/*************
	* redirection
	**************/
	public function redirect($location_page): void
	{
		header("location:".$location_page);
		exit();
	}


	/*************
	* flash message
	**************/
	public function flash(){
	    if(isset($_SESSION['Flash'])){
		    extract($_SESSION['Flash']);
			unset($_SESSION['Flash']);
	        return "<div class='notify notify-$type'><div class='notify-box-content'>$message</div></div>";
		}
	}


	public function setFlash($message,$type = 'vert'){
		$_SESSION['Flash']['message'] = $message;
		$_SESSION['Flash']['type'] = $type;
	}

}