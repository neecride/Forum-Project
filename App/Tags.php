<?php

namespace App;

class Tags{

    /*
	* return instance Parameters
	*/
	private function Params(){
		return new Parameters();
	}

    /*
    * return instance PDO
    */
    private function Cnx(){
        return new Database();
    }
    
    
    public function Tags($id){

        return $this->Cnx()->Request("SELECT
        f_topic_tags.topic_id,
        f_topic_tags.tag_id,
            f_tags.id AS tagid,
            f_tags.name,
            f_tags.slug
    
        FROM f_topic_tags
    
        LEFT JOIN f_tags ON f_tags.id = f_topic_tags.tag_id
    
        WHERE topic_id = ?",[$id]);
    }

}