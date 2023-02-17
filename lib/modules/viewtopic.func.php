<?php
use App\URL;
/********
*  pagination
*********/
$PerPage = (int) $GetParams->GetParam(2);

$CurrentPage = URL::getPositiveInt('page', 1);

$smtp = $db->prepare('SELECT COUNT(id) FROM f_topics_reponse WHERE f_topic_id = ?');

$smtp->execute([intval($params['id'])]);

$Count = (int)$smtp->fetch(PDO::FETCH_NUM)[0];

$pages = ceil($Count/$PerPage);
//on vérifie si la page existe bien sinon on redirige si on a pas de réponse
if($CurrentPage > $pages && $CurrentPage > 1) {
    setflash("Ce numéro de page n'hexiste pas","orange");
    header('Location:' . $router->generate($match['name'], ['id' => $params['id']]));
    http_response_code(301);
    exit();
}


$offset = $PerPage * ($CurrentPage - 1);
/********
*  pagination fin
*********/

$firstTopic = $db->prepare("SELECT

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

");

$firstTopic->execute([intval($params['id'])]);

$topic = $firstTopic->fetch();

if(!isset($topic->topicsid)){
    setFlash('Cette page n\'éxiste pas redirection sur la page d\'erreur','orange');
    redirect('error');
}

if(empty($params['id']) || !empty($params['id']) != $topic->topicsid){
			
    setFlash('Cette page n\'éxiste pas redirection sur la page d\'erreur','orange');
    redirect('error');

}

//réponses list
$response = $db->prepare("SELECT

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

ORDER BY f_topic_rep_date ASC LIMIT $PerPage OFFSET $offset");

$response->execute([intval($params['id'])]);

/**
* La sauvegarde nouvelles reponse
**/
var_dump('nombre max de page ' . $PerPage,'page en cour ' . $CurrentPage, 'nombre de pages total '.$pages,'nomnre de réponse obtenue ' .$Count);

if(isset($_POST['topics'])){
    checkCsrf();//on verifie les faille csrf
    $id = (int) $params['id'];
    $content = trim($_POST['f_topic_content']);
    $userid = (int) $_SESSION['auth']->id;
    $f_rep_name = trim($topic->f_topic_name);
    $error = '';
    if(grapheme_strlen($content) < 100){

        $error .= errors(['Votre topic dois contenir au moins 100 caractères']);

    }
    if(empty($error)){

        //on insert une reponse
        $i = [$userid,$f_rep_name, $content, $id];

        $db->prepare("INSERT INTO f_topics_reponse SET f_user_id = ?, f_rep_name = ?, f_topic_reponse = ?, f_topic_id = ?, f_topic_rep_date = NOW()")->execute($i);

        $lastid = $db->lastInsertId();//redirection ver le topic creer a instant

        //on met a jour la date du premier topic pour mettre en avant 
        $db->prepare("UPDATE f_topics SET f_topic_message_date = NOW() WHERE id = ?")->execute([$id]);

        //tester une redirection vers la page en court et redirigé dessus même si une nouvelle page se créer
        if($page == 1){

            setFlash("Votre réponse a bien étais poster redirection vers pages $pages");
            redirect($router->generate('viewtopic',['id' => $params['id'] . '?page=' . $pages . '#rep-' . $lastid]));

        }
        setFlash('Votre réponse a bien étais poster');
        redirect($router->generate('viewtopic',['id' => $params['id'] .'#rep-' . $lastid]));
        

    }

}

/**********
* view not view seulement si l'utilisateur est connecter
***********/
if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])){

    if (isset($params['id'])) {

        $userid = (int) $_SESSION['auth']->id;
        $get = (int) $params['id'];
    } else {
        setFlash('Cette page n\'éxiste pas redirection sur la page d\'erreur','orange');
        redirect('error');
    }


    $smtp = $db->prepare('SELECT id,read_topic FROM f_topic_track WHERE user_id = ? AND topic_id = ?');

    $smtp->execute([$userid,$get]);

    $views = $smtp->fetch();
    
    //on update topic track en fonction de l'utilisateur
    if($views != null){ 
        // il faudrai que l'update ce fasse seulement si la date du topic ou des réponses soit suppérieur a f_topic_track 
        // et seulement si l'utilisateur ne la pas déjà vu

        $db->prepare("UPDATE f_topic_track SET read_topic = NOW()  WHERE user_id = ? AND topic_id = ?")->execute([$userid,$get]);

    }else{

        $db->prepare("INSERT INTO f_topic_track SET read_topic = NOW(), user_id = ?, topic_id = ?")->execute([$userid,$get]);

    }

}	

//passer en résolu
if(isset($params['lock'])){
    
    checkCsrf();
    $lock = (int) $params['lock'];
    $id = (int) $params['id'];

    if(!empty($lock) && !preg_match("#^(0|1)$#",$lock)){
        setFlash('Seulement 0 ou 1 est possible','orange');
        redirect($router->generate('viewtopic',['id' => $params['id']]));
    }
    if($lock == 1){
        $info = "résolu";
    }elseif($lock == 0){
        $info = "ouvert";
    }
    $db->prepare("UPDATE f_topics SET topic_lock = ? WHERE id = ?")->execute([$lock,$id]);
    setFlash("Le topic a bien été $info");
    redirect($router->generate('viewtopic',['id' => $params['id']]));
}

//sticky 
if(isset($params['sticky']) && !empty($params['sticky'] >= 0)){
    
    checkCsrf();
    $sticky = (int) $params['sticky'];
    $id = (int) $params['id'];

    if(!preg_match("#^(0|1)$#",$sticky)){
        setFlash("Ce champ sticky doit être un nombre entre 0 & 1",'rouge');
        if(isset($_GET['page'])){
            redirect($router->generate('viewtopic', ['id' => $id.'?page='.$_GET['page']]));
        }
        redirect($router->generate('viewtopic', ['id' => $id]));
    }
    /*if(!filter_var($sticky, FILTER_VALIDATE_INT)) {
        setFlash("Ce champ sticky doit être un nombre entier",'rouge');
        redirect($router->generate('viewtopic', ['id' => $pageflag]));
    }*/
    $u = [$sticky, $id];
    
    $db->prepare("UPDATE f_topics SET sticky = ? WHERE id = ?")->execute($u);
    if($sticky === 1){
        setFlash('Votre message a bien étais mis en sticky');
        redirect($router->generate('viewtopic', ['id' => $id]));

    }elseif($sticky === 0){
        setFlash('Votre message a bien étais retiré des sticky');
        redirect($router->generate('viewtopic', ['id' => $id]));
    }
    
}


function TagsLink($id){

    global $db;

    $tag = $db->prepare('SELECT 
        
    f_topic_tags.topic_id,
    f_topic_tags.tag_id,
        f_tags.id AS tagid,
        f_tags.name,
        f_tags.slug AS tagslug
    
    FROM 
    
    f_topic_tags
    
    LEFT JOIN f_tags ON f_topic_tags.tag_id = f_tags.id
    
    WHERE f_topic_tags.topic_id = ?
    
    ');
    $tag->execute([$id]);
    
    $row = $tag->fetchAll();

    return $row;
    
}

//nb vu
if(isset($_SESSION['auth'])){
    $vu = [intval($params['id'])];
    
    $sql = $db->prepare("UPDATE f_topics SET f_topic_vu = f_topic_vu + 1 WHERE id = ?")->execute($vu); 
}