<?php
//si on est pas logger
$App->isNotConnect('forum');

/********
* on recupere les reponse
*********/
$get_reponse = $db->prepare("SELECT id, f_topic_id AS repid, f_topic_reponse, f_user_id FROM f_topics_reponse WHERE id = ?");

$get_reponse->execute([intval($match['params']['id'])]);

$rep = $get_reponse->fetch();

if($_SESSION['auth']->id == $rep->f_user_id or in_array($_SESSION["auth"]->authorization, [2,3])){

    /**
    * La sauvegarde
    **/
    if(isset($_POST['topics'])){
    
        checkCsrf();//on verifie les faille csrf
        $content = strip_tags(trim($_POST['f_topic_content']));
        $error = '';
        $getid = (int) $match['params']['id'];
        if(grapheme_strlen($content) < 100){
    
            $error .= errors(['Votre réponse dois contenir au moins 100 caractères']);
    
        }if(empty($error)){
    
            $u = [$content ,$getid];
    
            $uReponse = $db->prepare("UPDATE f_topics_reponse SET f_topic_reponse = ? WHERE id = ?");
    
            $uReponse->execute($u);
    
            setFlash('Votre message a bien étais modifier');
    
            $pageflag = $rep->repid.'#rep-'.$getid;
    
            redirect($router->routeGenerate('viewtopic', ['id' => $pageflag]));
    
        }

    }
    
}else{
    setFlash('Vous n\'avez pas le bon rang pour editer cette réponse ou cette réponse n\'est pas la votre','orange');
    redirect($router->routeGenerate('forum'));
}

