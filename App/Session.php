<?php

namespace App;

class Session{

	private function ExistCsrf(){
		
		if(!isset($_SESSION['csrf'])){

			$_SESSION['csrf'] = md5(time() + mt_rand());

		}

	}

	private function AltoMatch(){
        return new Route();
    }

	/******
	* function admin or not
	********/
	public function is_admin(){
		if(empty($_SESSION["auth"]->authorization) || !empty($_SESSION["auth"]->authorization != 4)){//verification du rang admin

			App::setFlash('<strong>Oh oh!</strong> vous n\'avez pas acces a cette page <strong> réserver au admin </strong>','orange');
			App::redirect('home');

		}
	}

	public function csrf(){

		$this->ExistCsrf();

	    return $_SESSION['csrf'];
	    
	}

	public function csrfInput(){
		return '<input type="hidden" value="' . $this->csrf() . '" name="csrf">';
	}	
		
	public function checkCsrf(){

	    if(
	        (isset($_POST['csrf']) && $_POST['csrf'] == $this->csrf()) 
	        ||
	        (null !== $this->AltoMatch()->Target()['params']['getcsrf'] && $this->AltoMatch()->Target()['params']['getcsrf'] == $this->csrf())
	      )
	    {
	      return true;	
	    }
	    App::setFlash('<strong>Oh oh!</strong> C\'est pas bien ! <strong> :( Faille CSRF </strong>','rouge');
	    App::redirect('error');
	    
	}


}