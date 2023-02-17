<?php
use App\URL;
/********
*  pagination
*********/
//mail('test@test.com','test de maildev','ceci est un test de maildev 2');
$PerPage = (int) $GetParams->GetParam(2);

$CurrentPage = URL::getPositiveInt('page', 1);

$Count = (int)$db->query('SELECT COUNT(id) FROM f_topics')->fetch(PDO::FETCH_NUM)[0];
$pages = ceil($Count/$PerPage);
//on vérifie si la page existe bien sinon on redirige 
if($CurrentPage > $pages) {
    setflash("Ce numéro de page n'existe pas","orange");
    header('Location:' . $router->generate('forum'));
    http_response_code(301);
    exit();
}
$offset = $PerPage * ($CurrentPage - 1);
/********
*  pagination fin
*********/

    if(isset($_SESSION['auth']->id)){ //si connecter
        
        $userid = isset($_SESSION['auth']->id) ? intval($_SESSION['auth']->id) : '' ;
        
        $topic = $db->prepare("SELECT
    
        f_topics.id AS topicid,
        f_topics.f_topic_name,
        f_topics.f_topic_slug AS topicslug,
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
    
        ORDER BY sticky DESC, Lastdate DESC LIMIT $PerPage OFFSET $offset
        ");

        $topic->execute([$userid]);

    
    }else{ // si non connecter

      $topic = $db->query("SELECT
    
      f_topics.id AS topicid,
      f_topics.f_topic_name,
      f_topics.f_topic_slug AS topicslug,
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
    
      ORDER BY sticky DESC, Lastdate DESC LIMIT $PerPage OFFSET $offset
      ");
    }

    function LastReponse($id){
        global $db;

		$lastRep = $db->prepare("SELECT

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

		ORDER BY f_topic_rep_date DESC");

        $lastRep->execute([$id]);

        $last = $lastRep->fetch();
        
        return $last;

	}

    function Tags($id){

        global $db;

        $tags = $db->prepare("SELECT
        f_topic_tags.topic_id,
        f_topic_tags.tag_id,
            f_tags.id AS tagid,
            f_tags.name,
            f_tags.slug
    
        FROM f_topic_tags LEFT JOIN f_tags ON f_tags.id = f_topic_tags.tag_id WHERE topic_id = ? ORDER BY ordre");

        $tags->execute([$id]);

        return $tags;
    }

    function CountRep($id){
        
        global $db;
        
        $counter = $db->prepare("SELECT COUNT(id) AS countid FROM f_topics_reponse WHERE f_topic_id = ?");
        $counter->execute([$id]);
        $count = $counter->fetchObject();
        return $count;
   
    }   


function CounterTag($id){
    global $db;

    $counter = $db->prepare("SELECT COUNT(f_tags.id) AS nbid FROM f_topic_tags LEFT JOIN f_tags on f_tags.id = f_topic_tags.tag_id WHERE f_tags.id = ? ");

    $counter->execute([$id]); 
    $tagCount = $counter->fetchObject();

    return $tagCount;
}