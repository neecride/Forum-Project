<?php

$user_name = strip_tags($match['username']);

$token = $match['token'];

$req = $db->prepare('SELECT * FROM users WHERE username = ?');

$req->execute([$user_name]);

$user = $req->fetch();

if($user && $user->confirmed_token == $token ){

    $db->prepare('UPDATE users SET slug = "membre", activation = 1, confirmed_token = null, confirmed_at = NOW() WHERE username = ?')->execute([$user_name]);

    $_SESSION['auth'] = $user;
    
    setFlash('Votre compte a bien étais valider');
    redirect($router->routeGenerate('account'));

}else{
    
    setFlash('Ce token n\'est plus valide <strong>Logger vous ou inscriver vous</strong>','rouge');
    redirect($router->routeGenerate('error'));
}