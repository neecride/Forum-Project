<?php

namespace App;

class Home{


	/*
	* return instance PDO
	*/
	private function Cnx(){
		return new Database();
	}		


	public function TagsList(){
		return $this->Cnx()->Request('SELECT * FROM f_tags ORDER BY ordre ASC');
	}

	public function Hometags($id){

		return $this->Cnx()->Request('SELECT 
		
				f_topic_tags.topic_id,
				f_topic_tags.tag_id,
					f_tags.id AS tagid,
					f_tags.name,
					f_tags.slug AS tagslug
		
		FROM 

		f_topic_tags
		
		LEFT JOIN f_tags ON f_topic_tags.tag_id = f_tags.id

		WHERE f_topic_tags.topic_id = ?
		
		',[$id]);

	}

	public function HomeAdvert(){

		return $this->Cnx()->Request("SELECT

		f_topics.id AS topicid,
		f_topics.f_topic_name,
		f_topics.f_topic_slug AS topicslug,
		f_topics.f_topic_content,
		f_topics.f_topic_date,
		f_topics.f_topic_update_date,
			users.id AS usersid,
			users.username,
			users.avatar,
			users.email,
				f_topic_tags.topic_id,
				f_topic_tags.tag_id,
					f_tags.id AS tagid,
					f_tags.name,
					f_tags.slug AS tagslug

		FROM f_topics

		LEFT JOIN users ON users.id = f_topics.f_user_id

		LEFT JOIN f_topic_tags ON f_topics.id = f_topic_tags.topic_id
		
		LEFT JOIN f_tags ON f_topic_tags.tag_id = f_tags.id

		WHERE sticky = 1

		GROUP BY f_topics.id

		ORDER BY f_topics.f_topic_date DESC LIMIT 6
		
		",[]);

		
	}


}