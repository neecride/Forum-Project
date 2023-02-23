<?php 

namespace App;

class Forum{

	/*
	* return instance Parameters
	*/
	private function Params(){
		return new Parameters();
	}

	private function Getpagination(){
		return new Pagination();
	}

	public function GetRoute()
	{
		return new Router();
	}

	/*
	* return instance PDO
	*/
	private function Cnx(){
		return new Database();
	}

	private function GetApp(){
		return new App();
	}

	public function homeForum(){

		if(isset($_SESSION['auth'])){
        
			$userid = (int) $_SESSION['auth']->id;
			
			return $this->Cnx()->Request("SELECT
		
			f_topics.id AS topicid,
			f_topics.f_topic_name,
			f_topics.f_topic_content,
			f_topics.f_user_id,
			f_topics.f_topic_date,
			f_topics.f_topic_update_date,
			f_topics.f_topic_message_date,
			f_topics.sticky,
			f_topics.topic_lock,
			f_topics.f_topic_vu,
				users.id AS usersid,
				users.username,
				users.description,
				users.authorization,
				users.avatar,
				users.email,
				users.slug AS userslug,
				users.userurl,
					f_topic_track.read_topic,
		
						/*
						CASE - si on a un nouveau topic on le met au dessu
						et si on a une réponse au passe au dessu du dernier topic
						*/
						CASE
		
						  WHEN f_topic_date < f_topic_message_date THEN f_topic_message_date
		
						  WHEN f_topic_date > f_topic_message_date THEN f_topic_date
		
						  ELSE f_topic_date
		
						END AS Lastdate,
						/* view not view */
						CASE
		
						  WHEN read_topic < f_topic_date THEN f_topic_date
		
						  WHEN read_topic > f_topic_date THEN read_topic
		
						END AS read_last
		
			FROM f_topics
		
			LEFT JOIN f_topic_track ON f_topic_track.topic_id = f_topics.id AND f_topic_track.user_id = ?
		
			LEFT JOIN users ON users.id = f_topics.f_user_id
		
			GROUP BY f_topics.id
		
			ORDER BY sticky DESC, Lastdate DESC LIMIT {$this->Getpagination()->setOfset()}
			",[$userid]);
	
		
		}else{ // si non connecter
	
		  return $this->Cnx()->Request("SELECT
		
		  f_topics.id AS topicid,
		  f_topics.f_topic_name,
		  f_topics.f_topic_content,
		  f_topics.f_user_id,
		  f_topics.f_topic_date,
		  f_topics.f_topic_update_date,
		  f_topics.f_topic_message_date,
		  f_topics.sticky,
		  f_topics.topic_lock,
		  f_topics.f_topic_vu,
		  f_topics.f_topic_update_date,
			  users.id AS usersid,
			  users.username,
			  users.description,
			  users.authorization,
			  users.avatar,
			  users.email,
			  users.slug AS userslug,
			  users.userurl,
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
		
		  LEFT JOIN users ON users.id = f_topics.f_user_id
		
		  GROUP BY f_topics.id
		
		  ORDER BY sticky DESC, Lastdate DESC LIMIT {$this->Getpagination()->setOfset()}
		  ");
		}
		
	}

	public function viewLastReponse($id){

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

		ORDER BY f_topic_rep_date DESC",[intval($id)],1);
	}

	public function Tags($id){

        return $this->Cnx()->Request("SELECT
        f_topic_tags.topic_id,
        f_topic_tags.tag_id,
            f_tags.id AS tagid,
            f_tags.name,
            f_tags.slug
    
        FROM f_topic_tags LEFT JOIN f_tags ON f_tags.id = f_topic_tags.tag_id WHERE topic_id = ? ORDER BY ordre DESC",[intval($id)]);

	}

	public function CountRep($id){
        return $this->Cnx()->CountObj("SELECT COUNT(id) AS countid FROM f_topics_reponse WHERE f_topic_id = ?",[intval($id)]);
    }   

	//navigation
	public function CounterTag($id){
		return $this->Cnx()->CountObj("SELECT COUNT(f_tags.id) AS nbid FROM f_topic_tags LEFT JOIN f_tags on f_tags.id = f_topic_tags.tag_id WHERE f_tags.id = ? ",[intval($id)]);
	}

	//navigation
	public function queryTags(){
		return $this->Cnx()->Request("SELECT * FROM f_tags ORDER BY ordre");
	}

	public function viewForumTags($id){

		if(isset($_SESSION['auth'])){ //si connecter

			$userid = $_SESSION['auth']->id;

			return $this->Cnx()->Request("SELECT

			f_topics.id AS topicid,
			f_topics.f_topic_name,
			f_topics.f_topic_content,
			f_topics.f_user_id,
			f_topics.f_topic_date,
			f_topics.f_topic_update_date,
			f_topics.f_topic_message_date,
			f_topics.sticky,
			f_topics.topic_lock,
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
					et si on a une réponse on passe au dessu du dernier topic
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

			ORDER BY sticky DESC, Lastdate DESC LIMIT {$this->Getpagination()->setOfset()}
			",[intval($userid),intval($id)]);

		}else{ // si non connecter
			
			return $this->Cnx()->Request("SELECT

			f_topics.id AS topicid,
			f_topics.f_topic_name,
			f_topics.f_topic_content,
			f_topics.f_user_id,
			f_topics.f_topic_date,
			f_topics.f_topic_update_date,
			f_topics.f_topic_message_date,
			f_topics.sticky,
			f_topics.topic_lock,
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


				ORDER BY sticky DESC, Lastdate DESC LIMIT {$this->Getpagination()->setOfset()}
			",[intval($id)]); 

		}

	}

	public function getViewForumExist(){
		
		$match = $this->GetRoute()->matchRoute();
		if($this->viewForumTags($match['params']['id']) == null){
			$this->GetApp()->setFlash("Il n'y a pas de topic avec cette id",'orange');
			redirect($this->GetRoute()->routeGenerate('forum'));
		}
	}

	public function firstTopic($id){

		return $this->Cnx()->Request("SELECT

		f_topics.id AS topicsid,
		f_topics.f_topic_content,
		f_topics.f_topic_name,
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

		",[intval($id)],1);

	}

	public function getTopicExist(){
		$match = $this->GetRoute()->matchRoute();
		if($this->firstTopic($match['params']['id']) == null){
			$this->GetApp()->setFlash("Il n'y a pas de topic avec cette id",'orange');
			redirect($this->GetRoute()->routeGenerate('forum'));
		}
	}

	public function viewTopicRep($id){

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

		ORDER BY f_topic_rep_date ASC LIMIT {$this->Getpagination()->setOfset()}",[intval($id)]);

	}

	public function accountLastTopic(){

		$userid = $_SESSION['auth']->id;

		return $this->Cnx()->Request("SELECT

		f_topics.id AS topicid,
		f_topics.f_topic_name,
		f_topics.f_topic_content,
		f_topics.f_user_id,
		f_topics.f_topic_date,
		f_topics.f_topic_update_date,
		f_topics.f_topic_message_date,
		f_topics.sticky,
		f_topics.topic_lock,
		f_topics.f_topic_vu,
			users.id AS usersid,
			users.username,
			users.description,
			users.authorization,
			users.avatar,
			users.email,
			users.slug AS userslug,
			users.userurl,
				f_topic_track.read_topic,
		
					/*
					CASE - si on a un nouveau topic on le met au dessu
					et si on a une réponse au passe au dessu du dernier topic
					*/
					CASE
		
					  WHEN f_topic_date < f_topic_message_date THEN f_topic_message_date
		
					  WHEN f_topic_date > f_topic_message_date THEN f_topic_date
		
					  ELSE f_topic_date
		
					END AS Lastdate,
					/* view not view */
					CASE
		
					  WHEN read_topic < f_topic_date THEN f_topic_date
		
					  WHEN read_topic > f_topic_date THEN read_topic
		
					END AS read_last
		
		FROM f_topics
		
		LEFT JOIN f_topic_track ON f_topic_track.topic_id = f_topics.id AND f_topic_track.user_id = ?
		
		LEFT JOIN users ON users.id = f_topics.f_user_id
		
		WHERE users.id = ?
		
		GROUP BY f_topics.id
		
		ORDER BY sticky DESC, Lastdate DESC LIMIT {$this->Getpagination()->setOfset()}
		",[intval($userid),intval($userid)]);

	}

	public function homePage(int $limit=6){

		return $this->Cnx()->Request("SELECT

			f_topics.id AS topicid,
			f_topics.f_topic_name,
			f_topics.f_topic_content,
			f_topics.f_topic_date,
			f_topics.f_topic_update_date,
				users.id AS usersid,
				users.username,
				users.avatar,
				users.email
		
			FROM f_topics
		
			LEFT JOIN users ON users.id = f_topics.f_user_id
		
			WHERE sticky = 1
		
			GROUP BY f_topics.id
		
			ORDER BY f_topics.f_topic_date DESC LIMIT $limit
	
	   ");
	}
	
	
}