<?php
if(isset($_POST['users']) && isset($match['params']['id'])){
    checkCsrf();//on verifie les faille csrf
    $slug = strip_tags(trim($_POST['slug']));
    $username = strip_tags(trim($_POST['name']));
    $error = '';
    
    if(!preg_match('/^[a-zàâäçéèêëîïôöùûüÿñ]+$/', $slug)) {

        $error .= errors(["Votre slug doit être inférieur ou égal à 10 caractères et contenir uniquement des lettres minuscules"]);

    }if(!preg_match('/^[a-zA-Z0-9]+$/', $username)){

        $error .= errors(["Votre pseudo n'est pas valide selement des minuscules/majuscule et underscore (_)"]);

    }if(!in_array($slug, ['admin','modo','membre'])){

        $error .= errors(["Le slug n'est pas valide admin|modo|membre seulement"]);

    }if(empty($error)){
            
            if($slug === "admin"){
                $authorization = (int) 3;
            }elseif($slug === "modo"){
                $authorization = (int) 2;
            }else{
                $authorization = (int) 1;
            }

            $id = (int) $match['params']['id'];

            $u = [$username,$slug,$authorization,$id];

            $req = $db->prepare("UPDATE users SET username = ?, slug = ?, authorization = ? WHERE id = ?")->execute($u);

            setFlash('Votre utilisateur a bien étais modifier');

            redirect($router->routeGenerate('user'));

    }

}

/********
* on recupere les entree
*********/

if(!empty($match['params']['id'])){

    $id = (int) $match['params']['id'];

    $s = [$id];

    $req = $db->prepare("SELECT * FROM users WHERE id = ?");

    $req->execute($s);

    $results =  $req->fetchObject();

    $input = $results;
}

if($input->authorization == 3){
    setFlash('On ne peut pas editer un admin','rouge');
    redirect($router->routeGenerate('user'));
}

if(isset($match['params']['id']) && !empty($match['params']['id'] != $input->id) || empty($match['params']['id'])){
    setFlash('Un problème est survenue <strong> aucun utilisateurs avec cet ID </strong>','orange');
    redirect($router->routeGenerate('user'));
}
