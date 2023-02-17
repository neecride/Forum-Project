<?php
/**
* Users gestion
**/
function get_users(){

    global $db;
    $req = $db->query("SELECT * FROM users ORDER BY date_inscription");
    
    $row = $req->fetchAll();
    
    
    return $row;

}

$users = get_users();

/*********
* activation
***********/
if(isset($match['params']['activid'])){

    var_dump($match['params']);
    die('Fonction a faire');

    checkCsrf();

    if($match['params']['rank'] == 3){

        setFlash('On ne peut pas désactivé ou supprimé un admin','rouge');

        redirect($router->generate('user'));

    }else{
        $id = (int) $match['params']['activid'];

        $u = [$id];

        $req = $db->prepare("UPDATE users SET slug = 'membre', activation = '1', authorization = '1' WHERE id = ?")->execute($u);

        setFlash('L\'utilisateur a bien étais mis a jour');

        redirect($router->generate('user'));
    }

}

/*********************
* suppresseion
**********************/

if(isset($match['params']['delid'])){//pas fini

    var_dump($match['params']);
    die('Fonction a faire');

    checkCsrf();

    if($match['params']['rank'] == 3){

        setFlash('On ne peut pas désactivé ou supprimé un admin','rouge');

        redirect($router->generate('users'));

    }else{
        $id = (int) $match['params']['delid'];

        $u = [$id];

        $req = $db->prepare("DELETE users id = ?")->execute($u);

        setFlash('L\'utilisateur a bien étais supprimé');

        redirect($router->generate('users'));
    }
}