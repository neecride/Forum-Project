<?php
$App->isLogged();

if(isset($_POST['register'])){

$username = strip_tags(trim($_POST['name']));
$pass = strip_tags(trim($_POST['pass']));
$password_confirm = strip_tags(trim($_POST['pass_confirm']));
checkCsrf();//on vérifie tout de meme les failles csrf
$error = '';

    if(empty($username)){

        $error .= errors(["Vous devez mettre un pseudo"]);

    }if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý_-]{4,15}$/', $username)){

        $error .= errors(["La longueur doit être comprise entre 4 et 15 caractères inclus, Les caractères autorisés sont les lettres (majuscules et minuscules), les chiffres et les tirets (-) et underscores (_)."]);

    }if(grapheme_strlen($username) < 5 || grapheme_strlen($username) > 15){

        $error .= errors(['Le username doit contenir entre 4 et 15 caractères max']);

    }if(empty($error)){

        $req = $db->prepare('SELECT id FROM users WHERE username = ?');

        $req->execute([$username]);

        $user = $req->fetch();
        if($user){

            $error .= errors(["Pseudo est déjà utiliser"]);

        }

    }if(empty(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

        $error .= errors(["Votre email n'est pas valide"]);

    }
    if(!empty($_POST['captcha'] != $_SESSION['captcha'])){

        $error .= errors(["Le captcha n'est pas valide"]);

    }if(empty($_POST['captcha'])){

        $error .= errors(["Le captcha est obligatoire"]);

    }
    if(empty($error)){

        $req = $db->prepare('SELECT id FROM users WHERE email = ?');

        $req->execute([$_POST['email']]);

        $email = $req->fetch();
        if($email){

            $error .= errors(["Email est déjà utiliser"]);

        }

    }if(!empty($pass != $password_confirm)){

            $error .= errors(["Vos mots de pass sont diférent"]);

        }if(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,15}$/', $pass)){

            $error .= errors(["le mot de passe doit être composé de 8 caractères de lettres, une majuscule de chiffres et d’au moins un caractère spécial"]);

        }if(empty($error)){

            $req = $db->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmed_token = ?, date_inscription = now()");

            $password = password_hash($pass, PASSWORD_BCRYPT);

            $token = strtolower(str_random(60));

            $req->execute([$username,  $password, $_POST['email'], $token]);

            $user_id = $db->lastInsertId();

            $header="MIME-Version: 1.0\r\n";
            $header.='From:"'.$_SERVER['HTTP_HOST'].'"<support@'.$_SERVER['HTTP_HOST'].'.com>'."\n";
            $header.='Content-Type:text/html; charset="uft-8"'."\n";
            $header.='Content-Transfer-Encoding: 8bit';

            $message = '
            <html>
                <body>
                    <div align="center">
                        Pour valider votre compte merci de cliquer sur ce >> <a href="http://'.$_SERVER['HTTP_HOST'].'/confirm-'.urlencode($username).'-'.$token.'" target="_blank" >LIEN</a> <<
                    </div>
                </body>
            </html>
            ';

            mail($_POST['email'], 'Confirmation de votre inscription',$message,$header);

            setFlash('<strong>Super !</strong> Vous êtes bien inscrit reste a valider votre compte ! par Email');
            redirect($router->routeGenerate('home'));

        }

}