<?php
/********
* on recupere les entree
*********/
$parameters = $db->query("SELECT * FROM parameters");
/**********
* sauvegarde params
**********/
if(!empty($_POST)){

    $error = '';
    if(!empty($_POST["slogan"])){

        if((grapheme_strlen($_POST["slogan"]) < 6) || (grapheme_strlen($_POST["slogan"]) > 30)){

            $error .= errors(["Le slogan est trop court 6 mini et 100 max caractères"]);

        }if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý\-\'\!?\s]+$/', $_POST["slogan"])){

            $error .= errors(["Le slogan non valide alphanumeric"]);

        }else{
            $name = strip_tags(trim($_POST['slogan']));
            $edit = $param[0]->param_id;
        }

    }if(!empty($_POST['sitename'])){

        if((grapheme_strlen($_POST['sitename']) < 6) || (grapheme_strlen($_POST['sitename']) > 30)){

            $error .= errors(["Le nom du site est trop court 6 mini et 100 max caractères"]);

        }if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý\-\'\!?\s]+$/', $_POST["sitename"])){

            $error .= errors(["Le nom du site est invalide alphanumeric"]);

        }else{
            $name = strip_tags(trim($_POST['sitename']));
            $edit = $param[1]->param_id;
        }

    }if(!empty($_POST['forumpager'])){

        if(!preg_match("#^(4|6|10|15|20)$#",$_POST['forumpager'])){

            $error .= errors(['Le formulaire n\'est pas valide seulement (3|5|10|15|20) sont possible']);

        }else{
            $name = strip_tags(trim($_POST['forumpager']));
            $edit = $param[2]->param_id;
        }

    }if(!empty($_POST['themeforlayout'])){

        if(!preg_match('#^[A-Za-z0-9]+$#',$_POST['themeforlayout'])){

            $error .= errors(['Le formulaire n\'est pas valide alphanumérique']);

        }else{
            $name = strip_tags(trim($_POST['themeforlayout']));
            $edit = $param[3]->param_id;
        }

    }if(empty($error)){

        $u = [$name, $edit];

        $db->prepare("UPDATE parameters SET param_value = ? WHERE param_id = ?")->execute($u);

        setFlash('Votre paramêtre a bien étais modifier');

        redirect($router->generate('admin'));
    }

}


/*************
* on récupère les catégories
**************/
function inCaTtable(){

    global $db;

    $req = $db->query("

    SELECT categories.id,
           categories.cat_name,
           works.category_id,
           COUNT(works.category_id) AS nbartid

    FROM categories

    LEFT JOIN works ON categories.id = works.category_id

    WHERE categories.id

    GROUP BY categories.id

    ORDER BY categories.date DESC

    ");

    $results = [];

    while($rows = $req->fetchObject()){

        $results[] = $rows;

    }
    return $results;
}
