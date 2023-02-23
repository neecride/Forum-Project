<?php

namespace App;

use PDO;

class Database{
	
    private $dbname;
    private $dbuser;
    private $dbhost;
    private $dbpass;
    private $pdo;

     
    public function __construct()
	{

    	require_once('..'. DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR .'config.php');
       
       	$this->dbhost = DBHOST;
        $this->dbname = DBNAME; 
        $this->dbuser = DBUSER; 
        $this->dbpass = DBPSWD; 

    }

	protected function Getpdo() : PDO
	{
		if($this->pdo === null){	
			try{
				$pdo = new PDO("mysql:dbname={$this->dbname};host={$this->dbhost}",$this->dbuser,$this->dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4')); 
				$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->pdo = $pdo;
			}catch(\Exception $e){
				/* echo $e->getMessage(); */
				die('Imopsible de ce connecter a la BDD');
			}
		}
		return $this->pdo;
	}

	//si besoin on a notre objet pdo
	public function thisPDO()
	{
		return $this->Getpdo();
	}

    public function Request(string $statement,array $attrs=null,int $one=null)
	{
        if(!is_null($attrs)){

            $req = $this->getPDO()->prepare($statement);
           
            $req->execute($attrs);
            
            if($one === 1){
            	//retour 1 result
         		return $req->fetch();
            }else{
            	//return all result
                return $req->fetchAll();
            }
            
        }else{
            
			$req = $this->getPDO()->query($statement);
			if($one === 1){
				//retour 1 result
				return $req->fetch();
            }else{
            	//return all result
                return $req->fetchAll();
            }
            
        }
        return null;
    }

	public function Delete($statement,$attr)
	{

		$req = $this->Getpdo()->prepare($statement);

		$req->execute($attr);
	}	

	public function Update($statement,$attr)
	{

		$req = $this->Getpdo()->prepare($statement);

		$req->execute($attr);
	}

	public function CountObj($statement,$attr)
	{

		$counter = $this->Getpdo()->prepare($statement,$attr);
        $counter->execute($attr);

        $count = $counter->fetchObject();

        return $count;
	}

	public function SiteParams($statement){
	    
	    $params = $this->Getpdo()->query($statement);

	    $param = [];

	    while($rows = $params->fetchObject()){
	        $param[] = $rows;
	    }

	    return $param;
	}


	public function LastInsertID()
	{
		return $this->Getpdo()->lastInsertId();
	}

}
