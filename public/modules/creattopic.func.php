<?php
//si on est pas logger
isNot_connect('forum');

/**
* La sauvegarde
**/
if(isset($_POST['topics'])){
    checkCsrf();//on verifie les faille csrf
    $topic_name = trim($_POST['f_topic_name']);
    $content = trim($_POST['f_topic_content']);
    $userid = (int) $_SESSION['auth']->id;
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '' ;
    $error = '';
    $sticky = isset($_POST['sticky']) ? (int) $_POST['sticky'] : '' ;
    if(grapheme_strlen($topic_name) < 6 || grapheme_strlen($topic_name) > 50){

        $error .= errors(['Le titre du topic doit avoir entre 6 et 50 caractères']);

    }if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý\-\'\!?\s]+$/', $topic_name)){
    
        $error .= errors(['Le titre ne dois pas contenir de charactères spéciaux parenthèses etc...']);
        
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
            
            $select = $db->prepare('SELECT id FROM f_tags WHERE id = ?');
            
            $select->execute([$v]);
            
            $checktag = $select->fetchAll();

            if(!preg_match('/^[0-9]+$/', $v)){
                $error .= errors(['Les tags ne doivent être que des chiffres']);
            }
            if(!empty($checktag[0]->id) != $v){   
                $error .= errors(['Le tags en question est inconue']);
            }
            
        }
        
    }if(empty($error)){
            //on insert les donee de f_topics
            if(!empty($sticky)){

                    $i = [$topic_name, $userid ,$content,$sticky];
                    
                    $db->prepare("INSERT INTO f_topics SET f_topic_name = ?, f_user_id = ?, f_topic_content = ?, sticky = ?, f_topic_date = NOW()")->execute($i);

                    $lastid = $db->lastInsertId();//redirection ver le topic creer a instant

                foreach($tags as $item){

                    $db->prepare("INSERT INTO f_topic_tags SET topic_id = ?, tag_id = ?")->execute([$lastid,$item]);

                }

                setFlash('<strong>Super !</strong> Votre topic a bien étais poster <strong>Bien jouer </strong>');

                $pageflag = $lastid.'#topic-'.$lastid;

                redirect($router->generate('viewtopic', ['id' => $pageflag]));

            }
            $i = [$topic_name, $userid ,$content];

            $db->prepare("INSERT INTO f_topics SET f_topic_name = ?, f_user_id = ?, f_topic_content = ?, f_topic_date = NOW()")->execute($i);

            $lastid = $db->lastInsertId();//redirection ver le topic creer a instant

            foreach($tags as $item){

                $db->prepare("INSERT INTO f_topic_tags SET topic_id = ?, tag_id = ?")->execute([$lastid,$item]);

            }

            setFlash('<strong>Super !</strong> Votre topic a bien étais poster <strong>Bien jouer </strong>');

            $pageflag = $lastid.'#topic-'.$lastid;

            redirect($router->generate('viewtopic', ['id' => $pageflag]));

    }
}

/*********
* on recupere les tags
**********/
$select = $db->query('SELECT * FROM f_tags');

$tag = $select->fetchAll();
