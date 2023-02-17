<?php
is_logged();

if(!empty($_POST) && !empty(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){//si des valeurs sont poster
    checkCsrf();//on vérifie tout de meme les failles csrf
    $req = $db->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();

    if($user){
        
        $reset_token = strtolower(str_random(60));
        $db->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?')->execute([$reset_token, $user->id]);
        
        
        $header="MIME-Version: 1.0\r\n";
        $header.='From:"'.$_SERVER['HTTP_HOST'].'"<support@'.$_SERVER['HTTP_HOST'].'.com>'."\n";
        $header.='Content-Type:text/html; charset="uft-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';

        $message = '
        <html>
            <body>
                <div align="center">
                    Pour réinitialiser votre mots de pass merci de cliquer sur ce >> <a href="http://'.$_SERVER['HTTP_HOST'].'/reset-'.urlencode($user->username).'-'.$reset_token.'" target="_blank">LIEN</a> <<
                </div>
            </body>
        </html>
        ';

        mail($_POST['email'], 'Réinitialisation de votre mots de pass',$message,$header);

        
        setFlash('Lien de restauration de mots de pass a bien étais envoyez');
        redirect('home');
        
    }else{
        
        setFlash('<strong>Oh oh!</strong> aucun compte ne corecpond a cette ! <strong>email</strong>','rouge');
		redirect('home');
        
    }

}