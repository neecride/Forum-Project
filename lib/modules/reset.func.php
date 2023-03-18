<?php

$App->isLogged();

if(isset($match['params']['username']) && isset($match['params']['token']) ){
    $req = $db->prepare('SELECT * FROM users WHERE username = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
    $req->execute([$match['params']['username'], $match['params']['token']]);
    $user = $req->fetch();
    $error = '';
    if($user){

        if(!empty($_POST)){

            checkCsrf();//on vérifie tout de meme les failles csrf
            $pass = strip_tags(trim($_POST['password']));
            $password_confirm = strip_tags(trim($_POST['password_confirm']));

            if($pass != $password_confirm){

                $error .= errors(["Vos mots de pass sont diférent"]);

            }if(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,15}$/', $pass)){

                $error .= errors(["le mot de passe doit être composé de 8 caractères de lettres, une majuscule de chiffres et d’au moins un caractère spécial"]);

            }else if(empty($error)){

                $password = password_hash($pass, PASSWORD_BCRYPT);

                $db->prepare("UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL")->execute([$password]);

                $_SESSION['auth'] = $user;
                setFlash('<strong>Salut !!</strong> votre mots de pass a bien étais restauré <strong>super</strong>');
                redirect($router->routeGenerate('home'));
            }

        }

    }else{

            setFlash('<strong>Ho ho!</strong> mauvaise URL <strong>Ce token n\'est pas valide</strong>','rouge');
            redirect($router->routeGenerate('home'));

    }

}else{

    setFlash('<strong>Ho ho!</strong> mauvaise URL <strong>Vous n\'avez pas le droit d\'être sur cette page </strong>','rouge');
    redirect($router->routeGenerate('home'));

}
