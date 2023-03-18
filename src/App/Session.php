<?php

namespace App;

use Framework;

class Session{

	public function GetRoute()
	{
		return new Framework\Router();
	}

	private function Params()
	{
		return new Parameters();
	}

	private function GetApp()
	{
		return new App();
	}

	private function ExistCsrf()
	{
		if(!isset($_SESSION['csrf'])){
			$_SESSION['csrf'] = md5(time() + mt_rand());
		}
	}

	/******
	* function admin or not
	********/
	public function is_admin()
	{
		if(empty($_SESSION["auth"]->authorization) || in_array($_SESSION["auth"]->authorization,[3])){//verification du rang admin
			$this->GetApp()->setFlash('Vous n\'avez pas acces a cette page | réserver au admin','orange');
			$this->GetApp()->redirect($this->GetRoute()->routeGenerate('home'));
		}
	}

	public function csrf()
	{
		$this->ExistCsrf();
	    return $_SESSION['csrf'];
	}

	public function csrfInput()
	{
		return '<input type="hidden" value="' . $this->csrf() . '" name="csrf">';
	}

	public function checkCsrf()
	{
		$match = $this->GetRoute()->matchRoute();
	    if(
	        (isset($_POST['csrf']) && $_POST['csrf'] == $this->csrf()) 
	        ||
	        (isset($match['params']['getcsrf']) && $match['params']['getcsrf'] == $this->csrf())
	      )
	    {
	      return true;	
	    }
	    $this->GetApp()->setFlash('C\'est pas bien ! <strong> :( Faille CSRF </strong>','rouge');
	    $this->GetApp()->redirect($this->GetRoute()->routeGenerate('error'));
	}


}