<?php

isNot_connect();


function user_account(){

    global $db, $match;

    $user_id = (int) $_SESSION['auth']->id;

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

if(isset($_POST['avatar'])){

    checkCsrf();//on vérifie tout de meme les failles csrf

    //on initialise l'id
    $profil_id = (int) $_SESSION['auth']->id;

    $avatar = $_FILES['avatar'];

    //on définie le nom de l'image
    $avatar_name = $avatar['name'];

    //on definie l'extension
    $extension = strtolower(substr($avatar_name, -3));

    //toutes les extensions n'on pas que 3 caractères
    //$extension = strtolower(substr(strrchr($avatar_name, '.'),1));

    //on renome l'image avant envoie avec l'id de l'utilisateur
    $save_name = md5($profil_id).'.'.$extension;

    //taille du fichier envoyez
    $pdsfile = filesize($_FILES['avatar']['tmp_name']);

    //1go en octets 1048576
    $max_size = 200000; //200ko

    //on definie l'extension autoriser
    $ext_autorize = ['png'];

    $error = '';

    if(!in_array($extension, $ext_autorize)){

        $error .= errors(['le fichier n\'est pas valide PNG uniquement']);

    }
    if($pdsfile > $max_size){

        $error .= errors(['le fichier est trop volumineux 200ko max']);

    }else if(empty($error)){

        move_uploaded_file($avatar['tmp_name'], 'inc/img/avatars/'.$save_name);

        $db->prepare("UPDATE users SET avatar = ? WHERE id = ?")->execute([$save_name, $profil_id]);

        $_SESSION['auth']->avatar = $save_name;
        setFlash('<strong>Super !</strong> Votre avatar a bien étais modifier/ajouter');
        redirect($router->generate('account'));

    }

}

if(isset($_POST['delete-avatar'])){

    checkCsrf();//on vérifie tout de meme les failles csrf

    $profil_id = intval($_SESSION['auth']->id);

    $image_name = md5($profil_id) . '.png';
    $error = '';

    if(!file_exists('inc/img/avatars/' . $image_name)){

        $error .= errors(['le fichier n\'existe pas']);

    }if(empty($error)){

        unlink('inc/img/avatars/' . $image_name);

        $db->prepare("UPDATE users SET avatar = ? WHERE id = ?")->execute(['', $profil_id]);

        setFlash('L\'avatar a bien été supprimer');
        redirect($router->generate('account'));

    }

}

if(isset($_POST['pwd'])){

    checkCsrf();//on vérifie tout de meme les failles csrf

    $pass = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    $error = '';

    if(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,15}$/', $pass)) {

        $error .= errors(["le mot de passe doit être composé de 8 a 15 caractères, de minuscules, une majuscule de chiffres et d’au moins un caractère spécial"]);

    }if($pass != $password_confirm){

        $error .= errors(["Vos mots de pass sont diférent"]);

    }if(empty($error)){


        $user_id = (int) $_SESSION['auth']->id;

        $password = trim(password_hash($pass, PASSWORD_BCRYPT));

        $db->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$password, $user_id]);

        $_SESSION['auth']->password = $password;

        setFlash('Votre mots de pass a bien étais modifier');
        redirect($router->generate('account'));

    }


}

if(isset($_POST['edit-profil'])){

    checkCsrf();//on vérifie tout de meme les failles csrf

    $profil_id = (int) $_SESSION['auth']->id;

    $description = $_POST['description'];

    $error = '';

    if(grapheme_strlen($description) > 200){

        $error .= errors(["Votre description ne dois pas dépasser 200 caractères"]);

    }else if(empty($error)){

        $db->prepare("UPDATE users SET description = ? WHERE id = ?")->execute([$description, $profil_id]);

        setFlash('Votre profil a bien étais modifier');
        redirect($router->generate('account-edit'));

    }

}

if(isset($_POST['edit-email'])){

    checkCsrf();//on vérifie tout de meme les failles csrf

    $profil_id = intval($_SESSION['auth']->id);

    $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));

    $email_confirm = trim(filter_var($_POST['email_confirm'], FILTER_VALIDATE_EMAIL));

    $error = '';

    $req = $db->prepare('SELECT id FROM users WHERE email = ?');

    $req->execute([$_POST['email']]);

    $emailt = $req->fetch();
    if($emailt){

        $error .= errors(["Email est déjà utiliser"]);

    }
    if(empty($email)){

        $error .= errors(["L'email n'est pas valide ne doit pas être vide"]);

    }if($email != $email_confirm){

        $error .= errors(["L'email est diférent"]);

    }
    if(empty($error)){

        $db->prepare("UPDATE users SET email = ? WHERE id = ?")->execute([$email, $profil_id]);

        setFlash('Votre email a bien étais modifier');
        redirect($router->generate('account-edit'));
    }

}