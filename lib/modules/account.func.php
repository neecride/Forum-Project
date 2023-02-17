<?php
isNot_connect();

if(isset($_POST['theme'])){
    $error = '';
    checkCsrf();//on vérifie tout de meme les failles csrf

    if(!empty($_POST['theme_name'])){

        if(!preg_match('#^[A-Za-z0-9]+$#',$_POST['theme_name'])){

            $error .= errors(['Le formulaire n\'est pas valide alphanumérique']);

        }

    }if(empty($error)){

        $req = $db->prepare("SELECT * FROM users_themes WHERE user_id = ?");
        $req->execute([intval($_SESSION['auth']->id)]);
        $theme = $req->fetch();

        if(isset($_SESSION['auth']->id,$theme->user_id) && !empty($_SESSION['auth']->id == $theme->user_id)){

            $db->prepare("UPDATE users_themes SET user_theme = ? WHERE user_id = ?")->execute([$_POST['theme_name'],intval($_SESSION['auth']->id)]);

        }else{

            $db->prepare("INSERT INTO users_themes SET user_id = ?, user_theme = ?")->execute([intval($_SESSION['auth']->id),$_POST['theme_name']]);

        }
        setFlash('Le theme a bien été modifier');
        redirect($router->generate('account'));

    }

}

/*********
* supression
***********/
if(isset($_POST['lock-account'])){

  if(!empty($_SESSION['auth']->authorization === 3)){
      setFlash('Pas possible de supprimer un administrateur','orange');
      redirect($router->generate('home'));
  }else {

    checkCsrf();

    $id = (int) $_SESSION['auth']->id;

    $req = $db->prepare("UPDATE users SET activation = 0 WHERE id = ?")->execute([$id]);

    $_SESSION = array();
    setcookie('remember', NULL, -1);

    setFlash('Votre compte a bien étais désactiver <strong>Bien jouer :)</strong>');

    redirect($router->generate('home'));

  }

}


//check les utilisateur
function user_account(){

    global $db, $match;

    $user_id = intval($_SESSION['auth']->id);

    $req = $db->prepare('SELECT
        users.id as userid,
        users.username,
        users.email,
        users.slug,
        users.avatar,
        users.description,
        users.date_inscription,
        users.userurl,
        users_themes.user_theme
        
        FROM users

        LEFT JOIN users_themes ON users.id = users_themes.user_id

        WHERE id = ?

    ');

    $req->execute([$user_id]);

    $users = $req->fetchObject();
    return $users;
}

$user = user_account();