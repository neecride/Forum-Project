<?php

namespace App;

class CnxBdd{
	
	public $select;
	public $where;
	public $attrs;
	public $group;

    public function __construct($select=null,$where=null,$attrs=null,$group=null){

       	$this->select = $select;
       	$this->where = $where;
       	$this->attrs = $attrs;
       	$this->group = $group;

    }

	/*
	* return instance PDO
	*/
	private function Cnx(){
		return new Database();
	}


    public function Select($select,$from){

    	$this->select = $this->Cnx()->thisPDO()->prepare("SELECT $select FROM $from ");

    	return $this;

    }

    public function Where($where){

    	$this->where = "WHERE $where";
    	return $this;

    }

    public function Attrs($attrs=null,$one=null){

    	$this->select->execute($attrs);
    	if($one === 1){
    		$this->attrs = $this->select->fetch();
    	}else{
    		$this->attrs = $this->select->fetchAll();
    	}
    	return $this;

    }    

    public function Group($group){

    	$this->group = "GROUP BY $group";

    	return $this;
    }


}
