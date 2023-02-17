<?php
$reqOrder = $db->query("SELECT * FROM f_tags ORDER BY ordre ASC");

/**
* La sauvegarde
**/
if(isset($_POST['tags'])){
    checkCsrf();//on verifie les faille csrf
    $name = strip_tags(trim($_POST['name']));
    $slug = $generator->generate($name);
    $ordre = intval($_POST['ordre']);

    if((grapheme_strlen($name) < 3) || (grapheme_strlen($name) > 100)){

        setFlash('Doit contenir au moins 3 caractères et moins de 100','orange');
        if(isset($router->match()['params']['editid'])){

            redirect($router->generate('tags-edit',['editid' => $router->match()['params']['editid'], 'getcsrf' => csrf()]));

        }else{

            redirect($router->generate('tags-add'));

        }


    }if(!intval($ordre)){

        setFlash('<strong>Ho ho !</strong> Le champ ordre ne dois contenir que des chiffres</strong>','orange');

        if(isset($router->match()['params']['editid'])){

            redirect($router->generate('tags-edit',['editid' => $router->match()['params']['editid'], 'getcsrf' => csrf()]));

        }else{

            redirect($router->generate('tags-add'));

        }

    }if(!empty($router->match()['params']['editid'])){

        $id = intval($router->match()['params']['editid']);

        $u = [$name,$slug,$ordre,$id];

        $db->prepare("UPDATE f_tags SET name = ?, slug = ?, ordre = ? WHERE id = ?")->execute($u);

        $id = $db->lastInsertId();

        setFlash('<strong>Super !</strong> Votre forum a bien étais modifier <strong>Bien jouer :)</strong>');

        redirect($router->generate('tags'));


    }else{

        $i = [$name ,$slug,$ordre];

        $db->prepare("INSERT INTO f_tags(name, slug, ordre) VALUE (?,?,?)")->execute($i);

        $id = $db->lastInsertId();

        setFlash('<strong>Super !</strong> Votre forum a bien étais ajouter <strong>Bien jouer :)</strong>');

        redirect($router->generate('tags'));
    }

}
/*********
* supression
***********/
if(isset($router->match()['params']['delid'])){

    checkCsrf();

    $id = [intval($router->match()['params']['delid'])];

    $db->prepare("DELETE FROM f_tags WHERE id = ?")->execute($id);

    setFlash('<strong>Super !</strong> Le tags à bien étais supprimer <strong>Bien jouer :)</strong>');

    redirect($router->generate('tags'));

}

/********
* on recupere les entree
*********/

if(isset($router->match()['params']['editid'])){

    $id = [intval($router->match()['params']['editid'])];

    $req = $db->prepare("SELECT * FROM f_tags WHERE id = ?");
    $req->execute($id);

    $results =  $req->fetch();
    $input = $results;
}


if(isset($router->match()['params']['editid']) && !empty($router->match()['params']['editid'] != $input->id)){

    setFlash('<strong>Ho ho !</strong> un problème est survenue <strong> aucun tags avec cet ID </strong> ','orange');
    redirect($router->generate('tags'));

}
