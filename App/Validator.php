<?php

namespace App;

class Validator{
	
	
	//instance de la class parameters 
	private function Params(){
		return new Parameters();
	}

	private function GetApp(){
		return new App();
	}

	public function GetErrors($errors){
		foreach ($errors as $value) {

			return '<li>'.$value.'</li>';

		}
	}	

	/*
	* validation username str
	*/
	public function ValidUsername($flied){

		if((grapheme_strlen($flied) < 3) || (grapheme_strlen($flied) > 15)){
            $error = $this->GetErrors(['Le username doit contenir entre 4 et 15 caractères max']);
        }

        if(empty($field) || !preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,15}$/', $field)){
            $error = $this->GetErrors(["le mot de passe doit être composé de 8 a 15 caractères, de minuscules, une majuscule de chiffres et d’au moins un caractère spécial"]);
        }

	}

	/*
	* verif password diférent
	*/	
	public function PassDiff($field,$passconfirm){

		if($field != $passconfirm){

	         $error = $this->GetErrors(["Vos mots de pass sont différent"]);

	     }

	}

	/*
	* validation input str
	*/
	public function ValidInput($flied,$infstr,$suppstr){

		if((strlen($flied) < $infstr) || (strlen($flied) > $suppstr)){
            $error = $this->GetErrors(['Le champ doit contenir au moins 4 min et 30 max caractères']);
        }

	}

	/*
	* si un email est valide
	*/
	public function VailidEmail($field){
		if(empty(filter_var($field, FILTER_VALIDATE_EMAIL)) || !filter_var($field, FILTER_VALIDATE_EMAIL)){
            $error = ["Votre email n'est pas valide"];
        }
	}

	/*
	* vérifie si un contenue est supperieur a str
	*/
	public function SuppContent($field,$limit,$page,$message){
		if((strlen($field) > $limit)){
			
			$this->GetApp()->setFlash($message,'orange');
            $this->GetApp()->redirect($page); 
            
        }
	}	

	/*
	* vérifie si un contenue est inferieur a str
	*/
	public function InfContent($field,$limit,$page,$message){
		if((strlen($field) < $limit)){
			
			$this->GetApp()->setFlash($message,'orange');
            $this->GetApp()->redirect($page); 
            
        }
	}

	//si la session n'est pas définie
	public function ValidSession($page,$message){
        if(!isset($_SESSION['auth'])){
        	$this->GetApp()->setFlash($message,'orange');
            $this->GetApp()->redirect($page); 
        }
	}


	/*********
	*si on est déjà connecter
	********/
	public function IsLogged($page,$message){
	    
	    if(!empty($_SESSION['auth'])){

	        $this->GetApp()->setFlash($message,'orange');
	        $this->GetApp()->redirect($page);

	    } 
	    
	}

	/******
	* function admin or not
	********/
	public function isAdmin(){
	    
	    if(empty($_SESSION["auth"]->authorization) || !empty($_SESSION["auth"]->authorization != 4)){//verification du rang admin
		
	        $this->GetApp()->setFlash('<strong>Oh oh!</strong> vous n\'avez pas acces a cette page <strong> réserver au admin </strong>','orange');
	        $this->GetApp()->redirect('home');
	    
	    } 
	}

	private function CaptchaInit(){

		if(!empty($this->Params()->GetParam(6))){   
    		return new \ReCaptcha\ReCaptcha($this->Params()->GetParam(6));
		}

	}	

	public function ValidCaptcha($page){

		$recaptcha = $this->CaptchaInit();

        if(!empty($this->Params()->GetParam(7))){

            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['SERVER_ADDR']);

            if(!$resp->isSuccess()){

            	sleep(1);

                $this->GetApp()->setFlash('<strong>Oh oh!</strong> Formulaire incorect !<strong> captcha invalide </strong>','orange');
                $this->GetApp()->redirect($page); 

            }

        }else{

            if(!empty($_POST['captcha'] != $_SESSION['captcha'])){

            	sleep(1);
            	
                $this->GetApp()->setFlash('<strong>Oh oh!</strong> Formulaire incorect !<strong> captcha invalide : captcha différent </strong>','orange');
                $this->GetApp()->redirect($page); 

            }if(empty($_POST['captcha'])){

            	sleep(1);
            	
                $this->GetApp()->setFlash('<strong>Oh oh!</strong> Formulaire incorect !<strong> captcha invalide : ne dois pas être vide </strong>','orange');
                $this->GetApp()->redirect($page); 

            }


        }

	}

	/***********
	* authorization admin or modo ...
	************/
	public function GetRoles($role){

		if(isset($_SESSION["auth"])){
			return in_array($_SESSION["auth"]->authorization , $role);
		}
		return false;
	}

	/*
	* check role user
	* $page string 
	* $message string
	* $role array
	*/
	public function ValidRole($page,$message,$role){

		if(isset($_SESSION["auth"]->authorization) && !empty($_SESSION["auth"]->authorization != $this->GetRoles($role) ) ){

			$this->GetApp()->setFlash($message,'orange');
            $this->GetApp()->redirect($page); 

		}

	}	

	// a tester en profondeur - le but et de vérifier si le parametre est desactiver mais si l'utilisateur est admin ou modo il peut passer
	public function ValidParam($id,$param_activ,$page,$message){

		if(isset($_SESSION["auth"]->authorization) && !empty($_SESSION["auth"]->authorization != $this->GetRoles([4,3]) ) ){
			
			if($this->Params()->GetParam($id,'param_activ') == $param_activ){

				$this->GetApp()->setFlash($message,'orange');
	            $this->GetApp()->redirect($page); 

			}

		}
	}

	/******
	*token
	******/
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

} 