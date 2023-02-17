<?php

namespace App;

use AltoRouter;

class ViewForums{
	
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

	private function AltoMatch(){
        return new Route();
    }

	private function Session(){
        return new Session();
    }

	private function GetApp(){
		return new App();
	}


	public function RequestPage(){

		if(isset($_GET['page'])){
			$getpage = (int) $_GET['page'];
			return $getpage;
		}

	}

	/*
	* return pagemax pagination
	*/
	private function PagesMax(){

		return $this->Params()->GetParam(2);

	}

	/*
	* return count article in database
	*/
	public function CountData(){

		return $this->Cnx()->CountID('SELECT 
		
		f_tags.id
		
		FROM 
		
		f_topics 
		
		LEFT JOIN f_topic_tags ON f_topics.id = f_topic_tags.topic_id
		
		LEFT JOIN f_tags ON f_topic_tags.tag_id = f_tags.id
		
		WHERE f_tags.id = ?',[intval($this->AltoMatch()->Target()['params']['id'])]);

	}

	/*
	* return split count articles and pagemax pagination
	*/
	private function PageTotales(){

		return ceil($this->CountData()/$this->PagesMax());
	}

	/*
	* return star loop pagination
	*/
	private function StartPager(){

		return ($this->CurrentPage()-1)*$this->PagesMax();

	}

	/*
	* return pagination limit
	*/
	private function Limited(){

		return $this->StartPager() .','. $this->PagesMax();

	}

	/*
	* return current page where get ppagination 
	*/
	private function CurrentPage(){

		if($this->RequestPage() > 0 AND $this->RequestPage() <= $this->PageTotales()) {
		    
		   $CurrentPage = $this->RequestPage();
		    
		} else {
		    //si id null alors page sera egal 1 
		    $CurrentPage = 1;

		}

		return $CurrentPage;

	}

	function Pager($page){
		$nb=2;
	
		if(!empty($this->CountData() > $this->PagesMax())){
	
		echo '<div class="page"><ul class="pagination mb-3 mt-3 pagination-sm">';
		//prev
		if($this->CurrentPage() > "1"){
	
			$prev = $this->CurrentPage()-1;
			echo '<li class="page-item"><a class="page-link" href=' . $this->GetApp()->webroot() .$page.'?page='.$prev.'><i class="fas fa-angle-double-left"></i></a></a></li>' ;
	
		}else{
	
			 echo '<li class="disabled page-item"><a class="page-link"><i class="fas fa-angle-double-left"></i></a></li>';
	
		}
	
		//pagination current
	
			for($i=1; $i <= $this->PageTotales(); $i++) {
	
				if($i <= $nb || $i > $this->PageTotales() - $nb ||  ($i > $this->CurrentPage()-$nb && $i < $this->CurrentPage()+$nb)){
	
					if($i == $this->CurrentPage()) {
	
						echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
	
	
					} else {
	
						echo '<li class="page-item"><a class="page-link" href=' . $this->GetApp()->webroot() .$page.'?page='.$i.'>'. $i .'</a></li>' ;
	
					}
	
				}else{
					if($i > $nb && $i < $this->CurrentPage()-$nb){
						$i = $this->CurrentPage() - $nb;
					}elseif($i >= $this->CurrentPage() + $nb && $i < $this->PageTotales()-$nb){
						$i = $this->PageTotales() - $nb;
					}
					echo '<li class="page-item"><a class="page-link" href=' . $this->GetApp()->webroot() .$page.'?page='.($i-1) .'>...</a></li>';
				}
	
			}
	
		//next last page
		if($this->CurrentPage() != $this->PageTotales()){
	
			$next = $this->CurrentPage()+1;
	
			echo '<li class="page-item"><a class="page-link" href=' . $this->GetApp()->webroot() .$page.'?page='.$next.'><i class="fas fa-angle-double-right"></i></a></li>' ;
	
		}else{
	
			echo '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-right"></i></a></li>';
	
		}
	
		echo '</ul></div>';
		}
	}


	public function CountRep($id){
        return $this->Cnx()->CountObj("SELECT COUNT(id) AS countid FROM f_topics_reponse WHERE f_topic_id = ?",[$id]);
    }

	public function ViewTopics(){

		if(isset($_SESSION['auth']->id)){ //si connecter

			$userid = (int) isset($_SESSION['auth']->id) ? intval($_SESSION['auth']->id) : '' ;
      		$get = (int) $this->AltoMatch()->Target()['params']['id'];
    
			return $this->Cnx()->Request("SELECT
		
			f_topics.id AS topicid,
			f_topics.f_topic_name,
			f_topics.f_topic_slug AS topicslug,
			f_topics.f_topic_content,
			f_topics.f_user_id,
			f_topics.f_topic_date,
			f_topics.f_topic_update_date,
			f_topics.f_topic_message_date,
			f_topics.sticky,
			f_topics.f_topic_vu,
				users.id AS usersid,
				users.username,
				users.description,
				users.authorization,
				users.avatar,
				users.email,
				users.slug AS userslug,
				users.userurl,
				f_topic_tags.topic_id,
				f_topic_tags.tag_id,
					f_tags.id AS tagid,
					f_tags.name,
					f_tags.slug,
					/*
					CASE - si on a un nouveau topic on le met au dessu
					et si on a une réponse au passe au dessu du dernier topic
					*/
					CASE
		
					  WHEN f_topic_date < f_topic_message_date THEN f_topic_message_date
		
					  WHEN f_topic_date > f_topic_message_date THEN f_topic_date
		
					  ELSE f_topic_date
		
					END AS Lastdate,
					/*
					view not view
					*/
					CASE
		
					WHEN read_topic < f_topic_date THEN f_topic_date
		
					WHEN read_topic > f_topic_date THEN read_topic
		
					END AS read_last
		
			FROM f_topics
		
			LEFT JOIN f_topic_tags ON f_topics.id = f_topic_tags.topic_id
		
			LEFT JOIN f_tags ON f_topic_tags.tag_id = f_tags.id
		
			LEFT JOIN users ON users.id = f_topics.f_user_id
		
			LEFT JOIN f_topic_track ON f_topic_track.topic_id = f_topics.id AND f_topic_track.user_id = ?
		
			WHERE f_tags.id = ?
		
			ORDER BY sticky DESC, Lastdate DESC LIMIT ".$this->Limited()."
			",[$userid,$get]);
		
		
		
		}else{ // si non connecter
			
      	  $get = (int) $this->AltoMatch()->Target()['params']['id'];

		  return $this->Cnx()->Request("SELECT
		
			f_topics.id AS topicid,
			f_topics.f_topic_name,
			f_topics.f_topic_slug AS topicslug,
			f_topics.f_topic_content,
			f_topics.f_user_id,
			f_topics.f_topic_date,
			f_topics.f_topic_update_date,
			f_topics.f_topic_message_date,
			f_topics.sticky,
			f_topics.f_topic_vu,
				users.id AS usersid,
				users.username,
				users.description,
				users.authorization,
				users.avatar,
				users.email,
				users.slug AS userslug,
				users.userurl,
				f_topic_tags.topic_id,
				f_topic_tags.tag_id,
					f_tags.id AS tagid,
					f_tags.name,
					f_tags.slug,
					/*
					CASE - si on a un nouveau topic on le met au dessu
					et si on a une réponse au passe au dessu du dernier topic
					*/
					CASE
		
						WHEN f_topic_date < f_topic_message_date THEN f_topic_message_date
		
						WHEN f_topic_date > f_topic_message_date THEN f_topic_date
		
						ELSE f_topic_date
		
					END AS Lastdate
					
				FROM f_topics
		
				LEFT JOIN f_topic_tags ON f_topics.id = f_topic_tags.topic_id
		
				LEFT JOIN f_tags ON f_topic_tags.tag_id = f_tags.id
		
				LEFT JOIN users ON users.id = f_topics.f_user_id
		
				WHERE f_tags.id = ?
		
		
				ORDER BY sticky DESC, Lastdate DESC LIMIT ".$this->Limited()."
		  ",[$get]);

		}

	}
	
	public function LastReponse($id){

		return $this->Cnx()->Request("SELECT

		f_topics_reponse.id AS idrep,
		f_topics_reponse.f_topic_rep_date,
		f_topics_reponse.f_topic_id,
		f_topics_reponse.f_user_id,
		f_topics_reponse.f_rep_name,
			users.id AS usersrep,
			users.username,
			users.description,
			users.authorization,
			users.avatar,
			users.email,
			users.slug,
			users.userurl

		FROM f_topics_reponse

		LEFT JOIN users ON users.id = f_topics_reponse.f_user_id

		WHERE f_topic_id = ?

		GROUP BY f_topics_reponse.id

		ORDER BY f_topic_rep_date DESC",[$id],1);


	}


}