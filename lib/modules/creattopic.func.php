<?php
//si on est pas logger
$App->isNotConnect('forum');

/**
* La sauvegarde
**/
if(isset($_POST['topics'])){
    checkCsrf();//on verifie les faille csrf
    $topic_name = strip_tags(trim($_POST['f_topic_name']));
    $content = strip_tags(trim($_POST['f_topic_content']));
    $userid = (int) $_SESSION['auth']->id;
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '' ;
    $error = '';
    $sticky = isset($_POST['sticky']) ? (int) $_POST['sticky'] : '' ;
    if(grapheme_strlen($topic_name) < 6 || grapheme_strlen($topic_name) > 50){

        $error .= errors(['Le titre du topic doit avoir entre 6 et 50 caractères']);

    }if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý\-\'\!?\s]{4,50}$/', $topic_name)){
    
        $error .= errors(['Le titre ne dois pas contenir de charactères spéciaux parenthèses etc... être comprise entre 4 et 50 caractères inclus']);
        
    }if(grapheme_strlen($content) < 100){

        $error .= errors(['Votre topic dois contenir au moins 100 caractères']);

    }if(!empty($sticky) && !preg_match("#^(1)$#",$sticky)){

            $error .= errors(["Vous ne devez pas modidiez ce champ " .intval($sticky)]);

    }if(empty($tags)){

        $error .= errors(['Vous devez choisir au moins 1 tag']);

    }
    if(!empty($tags) && count($_POST['tags']) > 4){

        $error .= errors(['Seul 4 tags sont requis']);

    }if(isset($tags) && !empty($tags)){

        foreach($tags as $v){

            if(!preg_match('/^[0-9]+$/', $v)){
                $error .= errors(['Les tags ne doivent être que des chiffres']);
            }
            
        }
        
    }if(empty($error)){
            //on insert les donee de f_topics
            if(!empty($sticky)){

                $i = [$topic_name, $userid ,$content,$sticky];
                
                $db->prepare("INSERT INTO f_topics SET f_topic_name = ?, f_user_id = ?, f_topic_content = ?, sticky = ?, f_topic_date = NOW()")->execute($i);

                $lastid = $db->lastInsertId();//redirection ver le topic creer a instant
                sleep(1);
                // on met a jour topic track
                $db->prepare("INSERT INTO f_topic_track SET read_topic = NOW(), user_id = ?, topic_id = ?")->execute([$userid,$lastid]);

                foreach($tags as $item){

                    $db->prepare("INSERT INTO f_topic_tags SET user_id = ?, topic_id = ?, tag_id = ?")->execute([$userid,$lastid,$item]);

                }

                setFlash('Votre topic a bien étais poster');
                $pageflag = $lastid.'#topic-'.$lastid;
                redirect($router->routeGenerate('home', ['id' => $pageflag]));

            }
            $i = [$topic_name, $userid ,$content];

            $db->prepare("INSERT INTO f_topics SET f_topic_name = ?, f_user_id = ?, f_topic_content = ?, f_topic_date = NOW()")->execute($i);

            $lastid = $db->lastInsertId();//redirection ver le topic creer a instant
            sleep(1);
            // on met a jour topic track
            $db->prepare("INSERT INTO f_topic_track SET read_topic = NOW(), user_id = ?, topic_id = ?")->execute([$userid,$lastid]);
            
            foreach($tags as $item){

                $db->prepare("INSERT INTO f_topic_tags SET user_id = ?, topic_id = ?, tag_id = ?")->execute([$userid,$lastid,$item]);

            }
            setFlash('Votre topic a bien étais poster');
            $pageflag = $lastid.'#topic-'.$lastid;
            redirect($router->routeGenerate('viewtopic', ['id' => $pageflag]));
    }
}