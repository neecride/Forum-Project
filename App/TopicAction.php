<?php

namespace App;

Class TopicAction{
	
	public $errors;
	private App $app;
	private Database $cnx;
	private Router $router;
	private Session $session;
	private Parameters $parameters;
	private Pagination $pagination;
	private $id;
	

	public function __construct($id)
	{
		$this->id = $id;
		$this->app = new App;
		$this->cnx = new Database;
		$this->router = new Router;
		$this->session = new Session;
		$this->parameters = new Parameters;
		$this->pagination = new Pagination;
	}

	public function checkError()
	{
		if(!is_null($this->errors)){
			return "<div class=\"notify notify-rouge\"><div class=\"notify-box-content\"><li class=\"errmode\">". implode("</li><li class=\"errmode\">",$this->errors) ."</li></div></div>";
		}
	}

	
	public function firstTopic()
	{
		return $this->cnx->Request("SELECT

		f_topics.id AS topicsid,
		f_topics.f_topic_content,
		f_topics.f_topic_name,
		f_topics.f_user_id,
		f_topics.f_topic_date,
		f_topics.f_topic_vu,
		f_topics.topic_lock,
		f_topics.f_topic_message_date,
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

		",[intval($this->id)],1);
	}

	public function viewTopicRep()
	{
		return $this->cnx->Request("SELECT

			f_topics_reponse.id AS topicsrep,
			f_topics_reponse.f_topic_reponse,
			f_topics_reponse.f_topic_id,
			f_topics_reponse.id AS repid,
			f_topics_reponse.f_user_id,
			f_topics_reponse.f_topic_rep_date AS rep_date,
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

		ORDER BY f_topic_rep_date ASC LIMIT {$this->pagination->setOfset()}",[intval($this->id)]);

	}

    public function Responses($page):self
	{
		if(isset($_POST['topics'])){
			
			$this->app->methodPostValid('POST');
			
			$this->session->checkCsrf();//on verifie les faille csrf
			
			$match = $this->router->matchRoute();

			$id = (int) $match['params']['id'];
			$content = strip_tags(trim($_POST['f_topic_content']));
			$userid = (int) $_SESSION['auth']->id;
			
			$validator = $this->getValidator()
				->postContent($content, 100);
		
			if($validator->isValid())
			{
				//on insert une reponse
				$i = [$userid,$content, $id];
				$this->cnx->Request("INSERT INTO f_topics_reponse SET f_user_id = ?, f_topic_reponse = ?, f_topic_id = ?, f_topic_rep_date = NOW()", $i);
				$lastid = $this->cnx->LastInsertID(); //pour la redirection
				//on met a jour la date du premier topic pour mettre en avant 
				$this->cnx->Request("UPDATE f_topics SET f_topic_message_date = NOW() WHERE id = ?",[$id]);
				sleep(1);
				// on met ajour topic track
				$views = $this->cnx->Request('SELECT * FROM f_topic_track WHERE user_id = ? AND topic_id = ?',[$userid,$id],1);
				//on update topic track en fonction de l'utilisateur
				if($views != null){ 
					$this->cnx->Request("UPDATE f_topic_track SET read_topic = NOW() WHERE user_id = ? AND topic_id = ?",[$userid,$id]);
				}else{
					$this->cnx->Request("INSERT INTO f_topic_track SET read_topic = NOW(), user_id = ?, topic_id = ?",[$userid,$id]);
				}
				//tester une redirection vers la page en court et redirigé dessus même si une nouvelle page se créer
				if($page >= 1){
					setFlash('Votre réponse a bien étais poster');
					redirect($this->router->routeGenerate('viewtopic',['id' => $match['params']['id'] .'?page='.$page.'#rep-' . $lastid]));
				}
				$this->app->setFlash('Votre réponse a bien étais poster');
				$this->app->redirect($this->router->routeGenerate('viewtopic',['id' => $match['params']['id'] .'#rep-' . $lastid]));
			}
			$this->errors = $validator->getErrors();
		
		}
		return $this;
	}

	public function resolved(): self
	{
		if(isset($_SESSION['auth']) && isset($this->router->matchRoute()['params']['lock'])){
			$this->app->methodPostValid('GET');
			$this->session->checkCsrf();
			$lock = (int) $this->router->matchRoute()['params']['lock'];
			$id = (int) $this->router->matchRoute()['params']['id'];
		
			if(!empty($lock) && !preg_match("#^(0|1)$#",$lock)){
				setFlash('Seulement 0 ou 1 est possible','orange');
				redirect($this->router->routeGenerate('viewtopic',['id' => $this->router->matchRoute()['params']['id']]));
			}
			if($lock == 1){
				$info = "résolu";
			}elseif($lock == 0){
				$info = "ouvert";
			}
			$this->cnx->Request("UPDATE f_topics SET topic_lock = ? WHERE id = ?",[$lock,$id]);
			$this->app->setFlash("Le topic a bien été $info");
			$this->app->redirect($this->router->routeGenerate('viewtopic',['id' => $this->router->matchRoute()['params']['id']]));
		}
		return $this;
	}

	public function sticky(): self 
	{
		if(isset($_SESSION['auth']) && isset($this->router->matchRoute()['params']['sticky']) && !empty($this->router->matchRoute()['params']['sticky'] >= 0)){
			$this->app->methodPostValid('GET');
			$this->session->checkCsrf();
			$sticky = (int) $this->router->matchRoute()['params']['sticky'];
			$id = (int) $this->router->matchRoute()['params']['id'];
		
			if(!preg_match("#^(0|1)$#",$sticky)){
				$this->app->setFlash("Ce champ sticky doit être un nombre entre 0 & 1",'rouge');
				if(isset($_GET['page'])){
					$this->app->redirect($this->router->routeGenerate('viewtopic', ['id' => $id.'?page='.$_GET['page']]));
				}
				$this->app->redirect($this->router->routeGenerate('viewtopic', ['id' => $id]));
			}
			$u = [$sticky, $id];
			
			$this->cnx->Request("UPDATE f_topics SET sticky = ? WHERE id = ?",$u);
			if($sticky === 1){

				$this->app->setFlash('Votre message a bien étais mis en sticky');
				if(isset($_GET['page'])){
					$this->app->redirect($this->router->routeGenerate('viewtopic', ['id' => $id.'?page='.$_GET['page']]));
				}
				$this->app->redirect($this->router->routeGenerate('viewtopic', ['id' => $id]));
		
			}elseif($sticky === 0){

				$this->app->setFlash('Votre message a bien étais retiré des sticky');
				if(isset($_GET['page'])){
					$this->app->redirect($this->router->routeGenerate('viewtopic', ['id' => $id.'?page='.$_GET['page']]));
				}
				$this->app->redirect($this->router->routeGenerate('viewtopic', ['id' => $id]));
			}
			
		}
		return $this;
	}

	public function viewNotView(): self
	{
		if(isset($_SESSION['auth']) && isset($this->router->matchRoute()['params']['id'])){

			$userid = (int) $_SESSION['auth']->id;
			$get = (int) $this->router->matchRoute()['params']['id'];
		
			$views = $this->cnx->Request('SELECT * FROM f_topic_track WHERE user_id = ? AND topic_id = ?',[$userid,$get],1);
			if($views != null){ 
				if($this->firstTopic()->f_topic_message_date >= $views->read_topic or $this->firstTopic()->f_topic_date >= $views->read_topic){
					$this->cnx->Request("UPDATE f_topic_track SET read_topic = NOW() WHERE user_id = ? AND topic_id = ?",[$userid,$get]);
				}
			}else{
				$this->cnx->Request("INSERT INTO f_topic_track SET read_topic = NOW(), user_id = ?, topic_id = ?",[$userid,$get]);
			}
		}
		return $this;
	}

	public function nbView(): self
	{
		if(isset($_SESSION['auth']) && isset($this->router->matchRoute()['params']['id'])){
			$vu = [intval($this->router->matchRoute()['params']['id'])];
			$this->cnx->Request("UPDATE f_topics SET f_topic_vu = f_topic_vu + 1 WHERE id = ?",$vu); 
		}
		return $this;
	}

	public function getTopicExist(): self
	{
		$match = $this->router->matchRoute();
		if($this->firstTopic($match['params']['id']) == null){
			$this->app->setFlash("Il n'y a pas de topic avec cette id",'orange');
			redirect($this->router->routeGenerate('forum'));
		}
		return $this;
	}

    private function getValidator()
	{
		return (new Validator());
	}

}