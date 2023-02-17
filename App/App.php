<?php

namespace App;

class App{
	
	/*
	* return instance PDO
	*/
	private function Cnx(){
		return new Database();
	}	

	/*************
	* chemin absolut
	**************/
    public function webroot(){
        
        $path = dirname(dirname(__FILE__));

        $directory = basename($path);
        $url = explode($directory, $_SERVER['REQUEST_URI']);
        if(count($url) == 1){
            $absolute = '/';
        }else{
            $absolute = $url[0] . $directory .'/';
        }
        return $absolute;
    
    }

    public function DD($dd){

		echo '<pre style="background:#fff;">';
		print_r($dd);
		echo '</pre>';

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

	/*************
	* gestion des themes
	**************/
	public function userTheme(){
	    
	    if(isset($_SESSION['auth']->id) && !empty($_SESSION['auth']->id)){

	        return $this->Cnx()->Request("SELECT * FROM users_themes WHERE user_id = ?",[intval($_SESSION['auth']->id)],1);

	    }
	}

	public function themeForLayout($parameters){
    	
	    if($this->userTheme() != false){
	 
	        $theme_name = $this->userTheme()->user_theme;

	        $theme_id = $this->userTheme()->user_id;

	    }
	    
	    if(isset($_SESSION['auth']->id,$theme_id) && !empty($_SESSION['auth']->id == $theme_id)){

	        $themeForLayout = $theme_name;// variable du choix utilisateur

	    }else{

	        $themeForLayout = $parameters;// variable du par defaut

	    }
	    return $themeForLayout;
	    
	}

	
	/*************
	* redirection
	**************/
	public function redirect($location_page, $folder=false){
    
	    if($folder != false){
	        header("location:" . $this->webroot() . $folder .'/'. $location_page);
	        exit();
	    }else{
	        header("location:" . $this->webroot() . $location_page);
	        exit();
	    }
 
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