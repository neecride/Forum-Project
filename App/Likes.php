<?php

namespace App;

class Likes{
	

	//instance de las class CnxBdd
	private function Cnx(){
		return new Database();
	}	
	
	public function likesCount($postid){

	    return $this->Cnx()->CountID('SELECT id FROM likes WHERE id_article = ?',[$postid]);

	}

	public function dislikesCount($postid){

	    return $this->Cnx()->CountID('SELECT id FROM dislikes WHERE id_article = ?',[$postid]);

	}

	public function voteLikeUser($postid){
	    
	    $votelike = false;
	    if(isset($_SESSION['auth']->id)){
	        $votelike = $this->Cnx()->Prepare('SELECT * FROM likes WHERE id_article = ? AND id_membre = ?',[$postid, $_SESSION['auth']->id],1);	        
	    }
	    
	    if($votelike != null){
	        return $votelike->id_membre == $_SESSION['auth']->id ? '#7cc94c' : '' ;
	    }
	    return null;
	    
	}

	public function voteDislikeUser($postid){
	    
	    $votedislike = false;
	    if(isset($_SESSION['auth']->id)){
	        $votedislike = $this->Cnx()->Prepare('SELECT * FROM dislikes WHERE id_article = ? AND id_membre = ?',[$postid, $_SESSION['auth']->id],1);
	    }
	    
	    if($votedislike != null){
	        return $votedislike->id_membre == $_SESSION['auth']->id ? '#ff3333' : '' ;
	    }
	    return null;
	    
	}

	public function voteBar($postid){
	    
	    return ($this->likesCount($postid) + $this->dislikesCount($postid)) == 0 ? 100 : round(100 * ($this->likesCount($postid) / ($this->likesCount($postid) + $this->dislikesCount($postid))));
	    
	}



}