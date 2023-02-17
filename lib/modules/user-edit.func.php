<?php
if(isset($_POST['users']) && isset($match['params']['id'])){
    checkCsrf();//on verifie les faille csrf
    $activation = (int) $_POST['activation'];
    $slug = $_POST['slug'];
    $error = '';
    
    if(!preg_match('/^[a-zàâäçéèêëîïôöùûüÿñ]+$/', $slug)) {

        $error .= errors(["Votre slug doit être inférieur ou égal à 10 caractères et contenir uniquement des lettres minuscules"]);

    }if(grapheme_strlen($slug) < 3 || grapheme_strlen($slug) > 10){

        $error .= errors(['Votre slug doit avoir 3 ou 10 caractères']);

    }if(!preg_match("#^(0|1)$#",$activation)){

        $error .= errors(['Le champ n\'est pas valide <strong> choix possible 0 ou 1</strong>']);

    }if(empty($error)){
            
            if($slug === "admin"){
                $authorization = (int) 3;
            }elseif($slug === "modo"){
                $authorization = (int) 2;
            }else{
                $authorization = (int) 1;
            }

            $id = (int) $match['params']['id'];

            $u = [$slug, $activation, $authorization,$id ];

            $req = $db->prepare("UPDATE users SET slug = ?, activation = ?, authorization = ? WHERE id = ?")->execute($u);

            setFlash('<strong>Super !</strong> Votre utilisateur a bien étais modifier <strong>Bien jouer :)</strong>');

            redirect($router->generate('user'));

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
    redirect($router->generate('user'));
}

if(isset($match['params']['id']) && !empty($match['params']['id'] != $input->id) || empty($match['params']['id'])){
    setFlash('Un problème est survenue <strong> aucun utilisateurs avec cet ID </strong>','orange');
    redirect($router->generate('user'));
}
