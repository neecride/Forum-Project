<?php
/**
* Users gestion
**/
function get_users(){

    global $db;
    $req = $db->query("SELECT * FROM users ORDER BY date_inscription");
    
    $row = $req->fetchAll();
    
    
    return $row;

}

$users = get_users();

/*********
* activation
***********/
if(isset($match['params']['activid'])){

    checkCsrf();

    if($match['params']['rank'] == 3){

        setFlash('On ne peut pas désactivé ou supprimé un admin','rouge');

        redirect($router->routeGenerate('user'));

    }else{
        $id = (int) $match['params']['activid'];

        $u = [$id];
        //UPDATE users SET slug = "membre", activation = 1, confirmed_token = null, confirmed_at = NOW() 
        $req = $db->prepare("UPDATE users SET slug = 'membre', activation = '1', authorization = '1' ,confirmed_token = null, confirmed_at = NOW() WHERE id = ?")->execute($u);

        setFlash('L\'utilisateur a bien étais mis a jour');

        redirect($router->routeGenerate('user'));
    }

}

/*********
* activation
***********/
if(isset($match['params']['unactiv'])){

    checkCsrf();

    if($match['params']['rank'] == 3){

        setFlash('On ne peut pas désactivé ou supprimé un admin','rouge');

        redirect($router->routeGenerate('user'));

    }else{
        $id = (int) $match['params']['unactiv'];

        $u = [$id];
        //UPDATE users SET slug = "membre", activation = 1, confirmed_token = null, confirmed_at = NOW() 
        $req = $db->prepare("UPDATE users SET activation = 0 WHERE id = ?")->execute($u);

        setFlash('L\'utilisateur a bien étais mis a jour');

        redirect($router->routeGenerate('user'));
    }

}

/*********************
* suppression
**********************/

if(isset($match['params']['delid'])){

    checkCsrf();

    if($match['params']['rank'] == 3){

        setFlash('On ne peut pas désactivé ou supprimé un admin','rouge');

        redirect($router->routeGenerate('user'));

    }else{
        
        $id = (int) $match['params']['delid'];

        $u = [$id];

        $reqdelete = $db->prepare("SELECT email FROM users WHERE id = ?");
        
        $reqdelete->execute([$id]);

        $rowdel = $reqdelete->fetch();

        $header="MIME-Version: 1.0\r\n";
        $header.='From:"'.$_SERVER['HTTP_HOST'].'"<support@'.$_SERVER['HTTP_HOST'].'.com>'."\n";
        $header.='Content-Type:text/html; charset="uft-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';

        $message = "
        <html>
            <body>
                <div align='center'>
                    <p>Vous recevez ce mail car vous avez demander la destruction de votre compte avec toutes ses données.</p> 
                    <p>C'est chose faite.</p>
                </div>
            </body>
        </html>
        ";

        mail($rowdel->email, 'Confirmation de suppression',$message,$header);

        $deltopic = $db->prepare('DELETE FROM f_topics WHERE f_user_id = ?')->execute($u);
        
        $delrep = $db->prepare('DELETE FROM f_topics_reponse WHERE f_user_id = ?')->execute($u); 
        
        $deltheme = $db->prepare('DELETE FROM users_themes WHERE user_id = ?')->execute($u); 
        
        $delttopictags = $db->prepare('DELETE FROM f_topic_tags WHERE user_id = ?')->execute($u); 
        
        $deltrack =  $db->prepare('DELETE FROM f_topic_track WHERE user_id = ?')->execute($u);
        
        $req = $db->prepare("DELETE FROM users WHERE id = ?")->execute($u);
        
        setFlash("L'utilisateur a bien étais supprimé avec toutes ses donnée");

        redirect($router->routeGenerate('user'));
    }
}