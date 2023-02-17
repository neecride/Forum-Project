<?php

namespace App;

Class Account{
	
	/*
	* return instance Parameters
	*/
	private function Params(){
		return new Parameters();
	}	

	/*
	* return instance PDO
	*/
	private function Cnx(){
		return new Database();
	}

	/*
	* return instance Validator
	*/
	private function Validat(){
		return new Validator();;
	}		
    
	public function Session(){
        return new Session();
    }

    private function GetApp(){
		return new App();
	}
	/*
	* edition du mot de passe password_hash
	*/ 
	public function ChangeMDP(){

		if(isset($_POST['pwd'])){

	        $pass = trim($_POST['password']);
	        $password_confirm = trim($_POST['password_confirm']);

	        $this->Session()->checkCsrf();//on vérifie tout de meme les failles csrf

	        $this->Validat()->ValidInput($pass,6,20);
			
			$this->Validat()->PassDiff($pass,$password_confirm);

	     	if(empty($error)){

	            $user_id = intval($_SESSION['auth']->id);

	            $password = trim(password_hash($pass, PASSWORD_BCRYPT));

	            $this->Cnx()->Prepare("UPDATE users SET password = ? WHERE id = ?",[$password, $user_id]);

	            $_SESSION['auth']->password = $password;

	            $this->GetApp()->setFlash('<strong>Super !</strong> Votre mots de pass a bien étais modifier <strong>Bien jouer :)</strong>');
	            $this->GetApp()->redirect('account'); 

	        }

		}



	}



}