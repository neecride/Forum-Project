<?php 

namespace App;

class Func{

    /*
	* return instance PDO
	*/
	private function Cnx(){
		return new Database();
	}
	
	public function Navi(){

        return $this->Cnx()->Request("SELECT * FROM f_tags ORDER BY ordre ASC");
    
    }

	public function NaviTagsCount($id){

        return $this->Cnx()->CountObj("SELECT 
		
		COUNT(f_tags.id) AS nbid

		FROM f_topic_tags 

		LEFT JOIN f_tags on f_tags.id = f_topic_tags.tag_id

		WHERE f_tags.id = ?

		",[$id]);
    
    }

    
}