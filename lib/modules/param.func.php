<?php
/********
* on recupere les entree
*********/
$parameters = $db->query("SELECT * FROM parameters");

/*********
* activation
***********/
if(isset($_GET['activ'])){
    
    //die('url ok');
    
    checkCsrf();
    
    $id = intval($_GET['activ']);
    
    $param = 'oui';
    $u = [$param, $id];
    
    $db->prepare("UPDATE parameters SET param_activ = ? WHERE param_id = ?")->execute($u);
    
    setFlash('<strong>Super !</strong> Vos paramêtres on bien étais ajouter/modifier <strong>Bien jouer :)</strong>');

    redirect('parameters','adminpanel');
    
}

/*********
* desactiv
***********/
if(isset($_GET['del'])){
    
    //die('url ok');
    
    checkCsrf();
    
    $id = intval($_GET['del']);
    
    $param = 'non';
    $u = [$param, $id];
    $db->prepare("UPDATE parameters SET param_activ = ? WHERE param_id = ?")->execute($u);
    
    setFlash('<strong>Super !</strong> Vos paramêtres on bien étais ajouter/modifier <strong>Bien jouer :)</strong>');

    redirect('parameters','adminpanel');
    
}

/**********
* sauvegarde params
**********/
if(!empty($_POST)){
    
    
    $error = '';
    if(!empty($_POST["slogan"])){
        
        if((strlen($_POST["slogan"]) < 6) || (strlen($_POST["slogan"]) > 100)){
            
            $error .= errors(["Le slogan est trop court 6 mini et 100 max caractères"]);
            
        }if(!preg_match("/^[a-zA-Z0-9\s\-]+$/i", $_POST["slogan"])){
            
            $error .= errors(["Le slogan non valide alphanumeric ( a-z A-Z 0-9 - ) pas d'accents "]);
            
        }else{
            $name = strip_tags(trim($_POST['slogan']));
            $edit = $param[0]->param_id;   
        }   

    }if(!empty($_POST['sitename'])){
        
        if((strlen($_POST['sitename']) < 6) || (strlen($_POST['sitename']) > 100)){
            
            $error .= errors(["Le nom du site est trop court 6 mini et 100 max caractères"]);
            
        }if(!preg_match("/^[a-zA-Z0-9\s\-]+$/i", $_POST["sitename"])){
            
            $error .= errors(["Le nom du site est invalide alphanumeric ( a-z A-Z 0-9 - ) pas d'accents "]);
            
        }else{
            $name = strip_tags(trim($_POST['sitename']));
            $edit = $param[1]->param_id; 
        }  
           
    }if(!empty($_POST['pager'])){
          
        if(!preg_match("#^(3|5|10|15|20)$#",$_POST['pager'])){
            
            $error .= errors(['Le formulaire n\'est pas valide seulement (3|5|10|15|20) sont possible']);
            
        }else{
            $name = strip_tags(trim($_POST['pager']));
            $edit = $param[2]->param_id;     
        }  
        
    }if(!empty($_POST['forumpager'])){
          
        if(!preg_match("#^(3|5|10|15|20)$#",$_POST['forumpager'])){
            
            $error .= errors(['Le formulaire n\'est pas valide seulement (3|5|10|15|20) sont possible']);
            
        }else{
            $name = strip_tags(trim($_POST['forumpager']));
            $edit = $param[4]->param_id;     
        }  
        
    }if(!empty($_POST['themeforlayout'])){
        
        if(!preg_match('#^[A-Za-z0-9]+$#',$_POST['themeforlayout'])){

            $error .= errors(['Le formulaire n\'est pas valide alphanumérique']);

        }else{
            $name = strip_tags(trim($_POST['themeforlayout']));
            $edit = $param[5]->param_id;     
        }
        
    }if(!empty($_POST['secretkey'])){

      
        if(!preg_match('/^[a-zA-Z0-9\-_]+$/',$_POST['secretkey'])){
            
            $error .= errors(['Le formulaire n\'est pas valide alphanumérique']);
            
        }else{
            $name = strip_tags(trim($_POST['secretkey']));
            $edit = $param[6]->param_id;     
        }  
        
    }if(!empty($_POST['publickey'])){

      
        if(!preg_match('/^[a-zA-Z0-9\-_]+$/',$_POST['publickey'])){
            
            $error .= errors(['Le formulaire n\'est pas valide alphanumérique']);
            
        }else{
            $name = strip_tags(trim($_POST['publickey']));
            $edit = $param[7]->param_id;     
        }  
        
    }if(empty($error)){
        
        $u = [$name, $edit];

        $db->prepare("UPDATE parameters SET param_value = ? WHERE param_id = ?")->execute($u);

        setFlash('<strong>Super !</strong> Votre paramêtre a bien étais modifier <strong>Bien jouer :)</strong>');

        redirect('parameters','adminpanel');   
    }

}

