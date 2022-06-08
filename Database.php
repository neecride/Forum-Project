<?php

namespace App;


class Database{
	
    private $dbname;
    private $dbuser;
    private $dbhost;
    private $dbpass;
    private $pdo;

     
    public function __construct(){

    	require_once('..'. DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR .'config.php');
       
       	$this->dbhost = DBHOST;
        $this->dbname = DBNAME; 
        $this->dbuser = DBUSER; 
        $this->dbpass = DBPSWD; 

    }

	private function Getpdo(){
		if($this->pdo === null){	
			try{
				$pdo = new \PDO("mysql:dbname={$this->dbname};host={$this->dbhost}",$this->dbuser,$this->dbpass,array( \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')); 
				$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);//ou FETCH_ASSOC
				$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); //exception or WARNING
				$this->pdo = $pdo;
			}catch(\Exception $e){
				/* echo $e->getMessage(); */
				die('Imopsible de ce connecter a la BDD');    
			}
		}
		return $this->pdo;

	}

	//si besoin on a notre objet pdo
	public function thisPDO(){
		return $this->Getpdo();
	}

    public function Request($statement,$attrs=null,$one = null){
        
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
            
            //si attrs = null return query
            return $this->getPDO()->query($statement); 
            
        }
        return null;
        
    }

	public function Query($statement){

		return $this->Getpdo()->query($statement);

	}

	public function Prepare($statement,$attr,$one=null){

		$req = $this->Getpdo()->prepare($statement);

		$req->execute($attr);

		if($one === 1){
			return $req->fetch();
		}else{
			return $req->fetchAll();
		}
	}	

	public function Insert($statement,$attr){

		$req = $this->Getpdo()->prepare($statement);

		$req->execute($attr);
	}	

	public function Delete($statement,$attr){

		$req = $this->Getpdo()->prepare($statement);

		$req->execute($attr);
	}	

	public function Update($statement,$attr){

		$req = $this->Getpdo()->prepare($statement);

		$req->execute($attr);
	}

	public function CountID($statement,$attr){

		$req = $this->Getpdo()->prepare($statement);

		$req->execute($attr);

		$results = $req->rowCount();

		return $results;
	}

	public function LastInsertID(){
		return $this->Getpdo()->lastInsertId();
	}



}
