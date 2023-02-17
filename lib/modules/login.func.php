<?php

is_logged();

if(isset($_POST['login'])){

  if(!empty($_SESSION['auth'])){
    setFlash('Ca ne va pas ! <strong> tu est déjà logger</strong>','orange');
    redirect($router->generate('account'));
  }
  if(!empty($_SESSION['login_time']) && $_SESSION['login_time'] < time()){
      unset($_SESSION['login_fail']);
      unset($_SESSION['login_time']);
  }
  if(!empty($_SESSION['login_fail']) && $_SESSION['login_fail'] >= 5){
          $error = errors(['Vous avez entré de mauvais identifiants 10 fois de suite il vous faut attendre '. date('H\hi',$_SESSION['login_time']) .' pour réessayer']);
  }
  else if(isset($_POST) && !empty($_POST)){//si des valeurs sont poster
  checkCsrf();//on vérifie tout de meme les failles csrf

        $username = trim($_POST['username']);
        $pass = trim($_POST['password']);

        $req = $db->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND activation = 1 AND confirmed_at IS NOT NULL');
        $req->execute(['username' => $username]);
        $user = $req->fetch();

      if($user == null){
          if(empty($_SESSION['login_fail'])){
              $_SESSION['login_fail'] = 1;
              $_SESSION['login_time'] = time()+ 60 * 3;
          }else{
              $_SESSION['login_fail']++;
          }
          setFlash('Les données n\'existe pas ou votre compte n\'est pas actif','orange');
          redirect('login');

      }if(password_verify($_POST['password'], $user->password)){

            $db->prepare('UPDATE users SET lastconect = NOW() WHERE id = ?')->execute([$user->id]);

            $_SESSION['auth'] = $user;

            if(isset($_POST['remember']) && !empty($_POST['remember'] == 1)){
                
                $remember_token = str_random(100);
                $db->prepare('UPDATE users SET remember_token = ? WHERE id = ?')->execute([$remember_token, $user->id]);
                setcookie('remember', $user->id . '==' . $remember_token . sha1($user->id . 'ratonlaveurs' . $_SERVER['REMOTE_ADDR']), time() + 3600 * 24 * 3, '/', $_SERVER['HTTP_HOST'], false,true);
                setFlash('Vous êtes bien connecter avec un cookie');
                redirect($router->generate('account'));

            }else{

                setFlash('Vous êtes bien connecter');
                redirect($router->generate('account'));

            }

      }else{

        sleep(1);
        if(empty($_SESSION['login_fail'])){
            $_SESSION['login_fail'] = 1;
            $_SESSION['login_time'] = time()+ 60 * 3;
        }else{
            $_SESSION['login_fail']++;
        }

        setFlash('Formulaire incorect ! <strong>Identifiant non valide</strong>','orange');
        redirect($router->generate('login'));

      }

  }

}