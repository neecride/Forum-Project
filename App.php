<?php

namespace App;

class App{

	/*************
	* instance de la class CnxBdd
	**************/
	public static function Cnx(){
		return new Database();
	}

	/*************
	* chemin absolut
	**************/
    public static function webroot(){
        
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

    public static function DD($dd){

		echo '<pre style="background:#fff;">';
		print_r($dd);
		echo '</pre>';

    }

    /*************
	* trucast long titre
	*************/
	public static function trunque($str, $nb = '') {
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
	public static function userTheme(){
	    
	    if(isset($_SESSION['auth']->id) && !empty($_SESSION['auth']->id)){

	        return self::Cnx()->Prepare("SELECT * FROM users_themes WHERE user_id = ?",[intval($_SESSION['auth']->id)],1);

	    }
	    return false;
	}

	public static function themeForLayout($parameters){
    	
	    if(self::userTheme() != false){
	 
	        $theme_name = self::userTheme()->user_theme;

	        $theme_id = self::userTheme()->user_id;

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
	public static function redirect($location_page, $folder=false){
    
	    if($folder != false){
	        header("location:" . self::webroot() . $folder .'/'. $location_page);
	        exit();
	    }else{
	        header("location:" . self::webroot() . $location_page);
	        exit();
	    }
 
	}


	/*************
	* flash message
	**************/
	public static function flash(){
	    if(isset($_SESSION['Flash'])){

		    extract($_SESSION['Flash']);
			unset($_SESSION['Flash']);
	        
	        return "<div class='notify notify-$type'><div class='notify-box-content'>$message</div></div>";
		} 		
	}


	public static function setFlash($message,$type = 'vert'){
		$_SESSION['Flash']['message'] = $message;
		$_SESSION['Flash']['type'] = $type;
	}

}
