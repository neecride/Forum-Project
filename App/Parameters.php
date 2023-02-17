<?php 

namespace App;

use IntlDateFormatter,IntlTimeZone;

Class Parameters{
	
	/*
	* return instance PDO
	*/
	private function Cnx(){
	return new Database();
	}

	private function Params(){
	    
	    return $this->Cnx()->SiteParams("SELECT * FROM parameters");

	}

	public function GetParam($Param_id,$activ='param_value'){

		return $this->Params()[$Param_id]->$activ;

	}

    public function AppDate($d){

        (int) $dateType = IntlDateFormatter::MEDIUM;
        (int) $timeType = IntlTimeZone::DISPLAY_SHORT_GENERIC;
        $datefmt = datefmt_create('fr_FR',$dateType,$timeType,'Europe/Paris');
        
        return datefmt_format($datefmt ,strtotime($d));
    }

	public function userTheme(){

        if(isset($_SESSION['auth']->id) && !empty($_SESSION['auth']->id)){
    
            return $this->Cnx()->Request("SELECT * FROM users_themes WHERE user_id = ?",[intval($_SESSION['auth']->id)],1);
    
        }
        return false;
    }

    //si l'utilisateur choisis un theme on l'utilise sinon on met le theme par defaut
    public function themeForLayout(string $param){
        
        if($this->userTheme() != false){
            
            $theme_name = $this->userTheme()->user_theme;
            
            $theme_id = $this->userTheme()->user_id;
            
        }
        if(isset($_SESSION['auth']->id,$theme_id) && !empty($_SESSION['auth']->id == $theme_id)){
            
            $themeForLayout = $theme_name;// variable du template
            
        }else{
   
            $themeForLayout = $param;// variable du template
            
        }
        
        return $themeForLayout;
    
    }

}