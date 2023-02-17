<?php
//si on est pas logger
isNot_connect('forum');

/********
* on recupere le premier topic
*********/
$firstTopic = $db->prepare("SELECT id, f_topic_content, f_user_id FROM f_topics WHERE id = ? ");

$firstTopic->execute([intval($match['params']['id'])]);

$topic = $firstTopic->fetch();

if($_SESSION['auth']->id == $topic->f_user_id or in_array($_SESSION["auth"]->authorization, [2,3])){

    /**
    * La sauvegarde
    **/
    if(isset($_POST['topics'])){
        checkCsrf();//on verifie les faille csrf
        $content = trim($_POST['f_topic_content']);
        $error = '';
        if(grapheme_strlen($content) < 100){

            $error .= errors(['Votre topic dois contenir au moins 100 caractères']);

        }
        if(empty($error)){

            $u = [$content, intval($match['params']['id'])];

            $db->prepare("UPDATE f_topics SET f_topic_content = ? WHERE id = ?")->execute($u);

                setFlash('Votre message a bien étais modifier');

                $pageflag = $topic->id.'#topic-'.intval($match['params']['id']);

                redirect($router->generate('viewtopic', ['id' => $pageflag]));
        }


    }


}else{

    setFlash('Vous n\'avez pas le bon rang pour editer ce topic ou ce topic n\'est pas le votre','orange');
    redirect('forum');
}


