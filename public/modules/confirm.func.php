<?php

$user_name = $params['username'];

$token = $params['token'];

$req = $db->prepare('SELECT * FROM users WHERE username = ?');

$req->execute([$user_name]);

$user = $req->fetch();

if($user && $user->confirmed_token == $token ){

    $db->prepare('UPDATE users SET slug = "membre", activation = 1, confirmed_token = null, confirmed_at = NOW() WHERE username = ?')->execute([$user_name]);

    $_SESSION['auth'] = $user;
    
    setFlash('<strong>Super !</strong> Votre compte a bien étais valider <strong>Bien jouer :)</strong>');
    redirect('account');

}else{
    
    setFlash('<strong>Ho ho!</strong> Ce token n\'est plus valide <strong>Logger vous ou inscriver vous</strong>','rouge');
    redirect('error');
}