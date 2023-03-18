<?php 

namespace App;

use IntlDateFormatter;

use Framework;

Class Parameters{

    private App $app;
	private Database $cnx;
    
	private Framework\Router $router;

	public function __construct()
	{
        $this->app      = new App;
		$this->cnx      = new Database;
		$this->router   = new Framework\Router;
	}

	private function Params()
    {
	    return $this->cnx->SiteParams("SELECT * FROM parameters");
	}

	public function GetParam($Param_id,$activ='param_value')
    {
		return $this->Params()[$Param_id]->$activ;
	}

    public function AppDate($date){

        (int) $dateType = IntlDateFormatter::MEDIUM;
        (int) $timeType = IntlDateFormatter::NONE;
        $datefmt = datefmt_create('fr_FR',$dateType,$timeType,'Europe/Paris');

        return datefmt_format($datefmt ,strtotime($date));

        /*$english_days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        $french_days = array('lun', 'mar', 'mer', 'jeu', 'ven', 'sam', 'dim');
        $english_months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $french_months = array('jan', 'fév', 'mar', 'avr', 'mai', 'jui', 'jui', 'aoû', 'sep', 'oct', 'nov', 'déc');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date("j M. Y à H:i:s", strtotime($date) ) ) );*/

    }

    /**
     * widget retourne des widgets 
     *
     * @return mixed
     */
    public function widget()
    {
        $match              = $this->router->matchRoute();
        $router             = $this->router;
        $App                = $this->app;
        $themeForLayout     = $this->themeForLayout();
        $fileUrl = RACINE.DS.'public'.DS.'templates'.DS.$themeForLayout.DS.'parts';
        $scandir = scandir($fileUrl);
        $activeWidget = "oui";
        $inpage = in_array($match['target'], ['home','forum','viewtopic','viewforums','survey']);
        if($activeWidget == "oui" && $inpage){
            echo '<div class="col-md-3">';
            echo '<div class="section-title-nav">';
            echo '<h5>Widget</h5>';
            echo '</div>';
            foreach($scandir as $fichier)
            {
                if(preg_match("#\.(php)$#",strtolower($fichier)) && !is_null($scandir)){
                    require RACINE.DS.'public'.DS.'templates'.DS.$themeForLayout.DS.'parts'.DS.$fichier;
                }
            }
            echo '</div>';
        }
    }

    /**
     * themeForLayout initialise le theme
     *
     * @param mixed $param
     */
    public function themeForLayout(): string
    {
        return $this->GetParam(3);
    }

}