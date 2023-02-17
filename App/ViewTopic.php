<?php

namespace App;

class ViewTopic{

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


	/*
	* return instance des route
	*/	
	private function AltoMatch(){
        return new Route();
    }

	/*
	* return instance de App
	*/	
	private function GetApp(){
        return new App();
    }
	
	/*
	* return instance de session
	*/
	private function Session(){
        return new Session();
    }

	/*
	* return pagemax pagination
	*/
	private function PagesMax(){

		return $this->Params()->GetParam(2);

	}

	public function RequestPage(){

		if(isset($_GET['page'])){
			$getpage = (int) $_GET['page'];
			return $getpage;
		}

	}

	/*
	* return count article in database
	*/
	public function CountData(){

		return $this->Cnx()->CountID('SELECT id FROM f_topics_reponse WHERE f_topic_id = ?',[intval($this->AltoMatch()->Target()['params']['id'])]);

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
		$nb = 2;
	
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


	/*public function ViewTopics(){

		return $this->Cnx()->Request("SELECT

        f_topics.id AS topicsid,
        f_topics.f_topic_content,
        f_topics.f_topic_name,
        f_topics.f_topic_slug,
        f_topics.f_user_id,
        f_topics.f_topic_date,
        f_topics.f_topic_vu,
		f_topics.topic_lock,
		f_topics.sticky,
            users.id AS usersid,
            users.username,
            users.description,
            users.authorization,
            users.avatar,
            users.email,
            users.slug,
            users.userurl

    FROM f_topics

    LEFT JOIN users ON users.id = f_topics.f_user_id

    WHERE f_topics.id = ?

    ",[intval($this->AltoMatch()->Target()['params']['id'])],1);
		
	} */

	/*public function GetResPonse(){
		return $this->Cnx()->Request("SELECT

			f_topics_reponse.id AS topicsrep,
			f_topics_reponse.f_topic_reponse,
			f_topics_reponse.f_topic_id,
			f_topics_reponse.id AS repid,
			f_topics_reponse.f_user_id,
			f_topics_reponse.f_topic_rep_date AS rep_date,
			f_topics_reponse.f_topic_update_date AS update_date,
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

		WHERE f_topics_reponse.f_topic_id = ?

		GROUP BY f_topics_reponse.id

		ORDER BY f_topic_rep_date DESC LIMIT ".$this->Limited()."",[intval($this->AltoMatch()->Target()['params']['id'])]);

	}*/ 

	/*
	* redirect if id = null
	*/
	/*public function Chek(){
		$checkPost = $this->ViewTopics();

		if(empty(intval($this->AltoMatch()->Target()['params']['id'])) || !empty(intval($this->AltoMatch()->Target()['params']['id']) != $checkPost->id)){
			
		    $this->GetApp()->setFlash('Cette page n\'éxiste pas redirection sur la page d\'erreur','orange');
    		$this->GetApp()->redirect('error');

		}
	}*/


	/**********
	* view not view
	***********/
	public function ViewNotView(){

		if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])){

			if (null !== $this->AltoMatch()->Target()['params']['id'] && is_int($this->AltoMatch()->Target()['params']['id'])) {

				$userid = intval($_SESSION['auth']->id);
				$get = intval($this->AltoMatch()->Target()['params']['id']);
				// Suite de la fonction...
			} else {
				// L'ID du sujet de forum n'est pas valide
				/*$this->GetApp()->setFlash('Cette page n\'éxiste pas redirection sur la page d\'erreur','orange');
				$this->GetApp()->redirect('error');*/
			}

			$views = $this->Cnx()->Request("SELECT id,read_topic FROM f_topic_track WHERE user_id = ? AND topic_id = ?",[$userid,$get],1);
			
			//on update topic track en fonction de l'utilisateur
			if($views != null){
	
				$this->Cnx()->Update("UPDATE f_topic_track SET read_topic = NOW()  WHERE user_id = ? AND topic_id = ?",[$userid,$get]);

			}else{

				$this->Cnx()->Insert("INSERT INTO f_topic_track SET read_topic = NOW(), user_id = ?, topic_id = ?",[$userid,$get]);

			}

		}	
	} 


	public function Up(){
		
		if(isset($this->AltoMatch()->Target()['params']['up'])){

			$this->Session()->checkCsrf();//on verifie les faille csrf
			
			$id = (int) $this->AltoMatch()->Target()['params']['up'];
			$this->Cnx()->Update("UPDATE f_topics SET f_topic_date = NOW() WHERE id = ?",[$id]);
			$this->GetApp()->setFlash('Votre topic a bien été remonter');
			$this->GetApp()->redirect('topic-'.$id);
		}

	}

	public function LockTopic(){

		if(isset($this->AltoMatch()->Target()['params']['lock'])){
			
			$this->Session()->checkCsrf();
			$lock = $this->AltoMatch()->Target()['params']['lock'];
			$id = $this->AltoMatch()->Target()['params']['id'];
	
			if(!empty($lock) && !preg_match("#^(1)$#",$lock)){
				$this->GetApp()->setFlash('Seulement 1 est possible','orange');
				$this->GetApp()->redirect('topic-'.$id);
			}
			if($lock == 1){
				$info = "résolu";
			}elseif($lock == 0){
				$info = "ouvert";
			}
			
			$this->Cnx()->Update("UPDATE f_topics SET topic_lock = ? WHERE id = ?",[$lock,$id]);
		    $this->GetApp()->setFlash('Le topic a bien été '.$info,'success');
    		$this->GetApp()->redirect('topic-'.$id);
		}

		
	}

}