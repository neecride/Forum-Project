<?php 

namespace App;

use IntlDateFormatter;

Class Parameters{

	private Database $cnx;

	public function __construct()
	{
		$this->cnx = new Database;
	}

	private function Params()
    {
	    return $this->cnx->Request("SELECT * FROM parameters");
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
     * themeForLayout initialise le theme
     *
     * @param mixed $param
     */
    public function themeForLayout(): string
    {
        return $this->GetParam(3);
    }

}