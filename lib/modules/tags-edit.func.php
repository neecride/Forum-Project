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
        if(isset($match['params']['editid'])){

            redirect($router->routeGenerate('tags-edit',['editid' => $match['params']['editid'], 'getcsrf' => csrf()]));

        }else{

            redirect($router->routeGenerate('tags-add'));

        }


    }if(!intval($ordre)){

        setFlash('Le champ ordre ne dois contenir que des chiffres','orange');

        if(isset($match['params']['editid'])){

            redirect($router->routeGenerate('tags-edit',['editid' => $match['params']['editid'], 'getcsrf' => csrf()]));

        }else{

            redirect($router->routeGenerate('tags-add'));

        }

    }if(!empty($match['params']['editid'])){

        $id = intval($match['params']['editid']);

        $u = [$name,$slug,$ordre,$id];

        $db->prepare("UPDATE f_tags SET name = ?, slug = ?, ordre = ? WHERE id = ?")->execute($u);

        setFlash('Votre tag a bien étais modifier');

        redirect($router->routeGenerate('tags'));


    }else{

        $i = [$name ,$slug,$ordre];

        $db->prepare("INSERT INTO f_tags(name, slug, ordre) VALUE (?,?,?)")->execute($i);

        setFlash('Votre tag a bien étais ajouter');

        redirect($router->routeGenerate('tags'));
    }

}
/*********
* supression
***********/
if(isset($match['params']['delid'])){

    checkCsrf();

    $id = [intval($match['params']['delid'])];

    $db->prepare("DELETE FROM f_tags WHERE id = ?")->execute($id);

    setFlash('Le tag à bien étais supprimer');

    redirect($router->routeGenerate('tags'));

}

/********
* on recupere les entree
*********/

if(isset($match['params']['editid'])){

    $id = [intval($match['params']['editid'])];

    $req = $db->prepare("SELECT * FROM f_tags WHERE id = ?");
    $req->execute($id);

    $results =  $req->fetch();
    $input = $results;
}


if(isset($match['params']['editid']) && !empty($match['params']['editid'] != $input->id)){

    setFlash('Un problème est survenue aucun tags avec cet ID','orange');
    redirect($router->routeGenerate('tags'));

}
