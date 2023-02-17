<?php

function reconnect_from_cookie(){//reconextion automatique

    if(isset($_COOKIE['remember']) && !isset($_SESSION['auth'])){
        if(!isset($db)){
            global $db;
        }
        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];
        $req = $db->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute([$user_id]);
        $user = $req->fetch();
        $key = sha1($user->id . 'ratonlaveurs' . $_SERVER['REMOTE_ADDR']);
        if($_SESSION['auth'] = $user){
            $expected = $user_id . '==' . $user->remember_token . $key;
            if($expected == $remember_token){

                $_SESSION['auth'] = $user;
                setcookie('remember', $remember_token, time() + 3600 * 24 * 3, '/', $_SERVER['HTTP_HOST'], false,true);
                setFlash('Vous avez étais reconnectez avec les cookies');

            } else{

                setcookie('remember', null, -1);

            }
        }else{

            setcookie('remember', null, -1);

        }
    }
}
reconnect_from_cookie();

function copyleft(){
    echo 'Conception par <a href="https://www.deviantart.com/snyl-laposny" style="color: #912c1a;">Wysiwyg</a>';
}
/**********
* mise en cash
**********/
function version($path){
    if (file_exists($file = $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. $path)){
        $mtime = filemtime($file);
        $path_info = pathinfo($file);
        $ext = substr($file, strrpos($file, '.'));

        return str_replace($ext,'-'. hash('md5',$mtime),$path) . $ext;
        //return $path_info['dirname'] . '/' . $path_info['filename'] . '-' . hash('md5', $mtime) . '.' . $path_info['extension']

    }

    return $path;
}
/**************
* redirect
***************/
function redirect($location_page){
        header("location:".$location_page);
        exit();
}


/*********
*is Not connect
********/
function isNot_connect($page = 'home'){
    global $router;
     if(!isset($_SESSION['auth'])){//si rien en session

        setFlash('Vous devez être connecter pour acceder a cette page','orange');
        redirect($router->generate($page));
    }

}

/*********
*is Not connect
********/
function is_logged(){
    global $router;
    if(!empty($_SESSION['auth'])){

        setFlash('<strong>Oh oh!</strong> ça ne va pas ! <strong> tu est déjà logger</strong>','orange');
        redirect($router->generate('account'));

    }

}
/******
* function admin or not
********/
function is_admin(){
    global $router;
    if(!empty($_SESSION["auth"]->authorization < 3)){//verification du rang admin

        setFlash('Vous n\'avez pas acces a cette page <strong> réserver au admin </strong>','orange');
        redirect($router->generate('home'));

    }
}


/******
*token
******/
function str_random($length){

    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";

    return substr(str_shuffle(str_repeat($alphabet, $length)), 0,$length);

}

/*******
* auto slug
*******/
function Slug($string){
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}

function rank($slug){
    if($slug == 'admin'){
      $rank = "admin";
    }elseif($slug == 'modo'){
      $rank = "modo";
    }elseif($slug == 'membre'){
      $rank = "membre";
    }else{
      $rank = "other";
    }
    return $rank;
}


/******
*fail csrf
******/
if(!isset($_SESSION['csrf'])){

    $key = md5(time() + mt_rand());

	$_SESSION['csrf'] = $key;
}

function csrf($params = null){

    if($params != null){
        return $params . $_SESSION['csrf'];
    }else{
        return $_SESSION['csrf'];
    }

}

function csrfInput(){
	return '<input type="hidden" value="' . $_SESSION['csrf'] . '" name="csrf">';
}

// check les csrf et envoie un mail en cas de problème 
function checkCsrf(){
    global $router,$params;

    if(
        (isset($_POST['csrf']) && $_POST['csrf'] == $_SESSION['csrf'])
        ||
        (isset($params['getcsrf']) && $params['getcsrf'] == $_SESSION['csrf'])
      )
    {
      return true;
    }

    if(isset($_SESSION['auth']->id) && !empty($_SESSION['auth']->id)){

        $userConnect = 'session en cour de l\'utilisateur, '.$_SESSION['auth']->username;

    }else{

        $userConnect = 'session en cour de l\'utilisateur, Visiteur pas de session';
    }
    mail('amin@mail.com', 'Faille CSRF sur votre site',"Vous recevez ce mail car il y a eu une faille avec cette IP ".$_SERVER["REMOTE_ADDR"] ." ". $userConnect ." ");
    sleep(5);

    setFlash("<strong>Oh oh!</strong> C'est pas bien ! <strong> :( Faille CSRF </strong>",'rouge');
    redirect($router->generate('error'));
    exit();

}

/********
* les formulaires
********/
function newInput($field,$label=null,$attrs = []){

    $r = '';
    if($label!=null){

        $r = '<label for="'. $field .'">'.$label.'</label>';

    }

    $r .= '<input name="'.$field.'" ';

    foreach($attrs as $k => $v){
        $r .= ' '.$k.'='.$v ;
    }
    $r .= ' />';

    return $r;
}


function input($id,$type,$CssClass,$PlaceHolder='',$empty='', $required=''){
    $value = isset($_POST[$id]) && !empty($_POST[$id]) ? $_POST[$id] : $empty ;
    return "<input type='$type' class='$CssClass' id='$id' name='$id' placeholder='$PlaceHolder' value='$value' $required>";
}

function input_email($id, $label){
    $label = isset($label) ? $label : '';
    $value = isset($_POST[$id]) ? $_POST[$id] : '';
    return "<label class='field-label' for='$label'>$label</label><input type='email'  class='form-control field-input' id='$id' name='$id' value='$value' />";
}
function input_password($id, $label){
    $label = isset($label) && !empty($label) ? $label : "";
    $value = isset($_POST[$id]) ? $_POST[$id] : '';
    return "<label class='field-label' for='$label'>$label</label><input type='password'  class='form-control field-input' id='$id' name='$id' value='$value' />";
}
function textArea($id,$type,$CssClass,$required=''){
    $value = isset($_POST[$id]) && !empty($_POST[$id]) ? $_POST[$id] : "Ne postez pas d'insultes, évitez les majuscules... Tout message d'incitation à la haine, au piratage, les insultes à la personne, le harcèlement reviendra à être banis du site temporairement ou définitivement vous êtes responsable de vos commentaires...";
    return "<textarea  type='$type' class='$CssClass' id='$id' name='$id' $required>$value</textarea>";
}

function BootstrapMde($id, $sql=''){
    $req = isset($sql) && !empty($sql) ? $sql : '' ;
    $value = isset($_POST[$id]) ? $_POST[$id] : $req ;
    $editor = "<textarea style='position:relative;' type='text' data-rows='32' class='markdown' data-language='fr' data-height='100px' class='myarea form-control' id='editor1' name='$id'>$value</textarea><div id='preview'> </div>";
    return $editor;
}

function BootstrapMdeCom($id, $sql=''){
    $req = isset($sql) && !empty($sql) ? $sql : '' ;
    $value = isset($_POST[$id]) ? $_POST[$id] : $req ;
    $editor = "<textarea type='text' data-height='400' data-provide='markdown-editable' onkeyup='countCharCom(this)' class='form-control' id='editor2' name='$id'>$value</textarea><div id='preview'> </div>";
    return $editor;
}

function JustDemo(){

    return "**bonjour je suis du [markdown](https://fr.wikipedia.org/wiki/Markdown#Formatage)**\n\n~~pourquoi~~\n\n> parce que c'est cool\n\n:grinning:\n";
   
}


function select($id, $options = []){
    $return = "<select class='form-control' id='$id' name='$id'>";
    foreach($options as $k => $v){
        $selected = '';
        if(isset($_POST[$id]) && $k == $_POST[$id]){
            $selected = ' selected';
        }
        $return .= "<option value='$k' $selected>$v</option>";
    }
    $return .= '</select>';
    return $return;
}

/*************
* trucast long titre
*************/
function trunque($str, $nb = '') {
	if (strlen($str) > $nb) {
		$str = substr($str, 0, $nb);
		$position_espace = strrpos($str, " ");
		$texte = substr($str, 0, $position_espace);
		$str = $str."...";
	}
	return $str;
}

/***********
* error message
***********/
function errors($messages = []){

    if(!empty($messages)){
        foreach($messages as $v){

            $return = '<li class="errmode">'.$v.'</li>';

        }
    }
    return $return;
}

function CheckErreor($error){

    $check = !empty($error) ? "<div class='notify notify-rouge'><div class='notify-box-content'><ul>".$error."</ul></div></div>" : '' ;

    return $check;

}

/***********
* message info
***********/
function messageInfo($titre,$message, $type = 'vert', $active = '0', $position = ''){
  if($active = 1){
    return '<div class="alert-box alert-'.$type.'">
                <div class="alert-box-title">'.$titre.'</div>
                <div class="alert-box-content">'.$message.'</div>
            </div>';
    }
}

/*************
* flash message
**************/
function flash(){
  if(isset($_SESSION['Flash'])){
    extract($_SESSION['Flash']);
    unset($_SESSION['Flash']);

        return "<div class='notify notify-$type'><div class='notify-box-content'>$message</div></div>";
	}
}

function setFlash($message,$type = 'vert'){
	$_SESSION['Flash']['message'] = $message;
	$_SESSION['Flash']['type'] = $type;
}

function navLink(){
    global $db;
    $nav = $db->query("SELECT * FROM f_tags ORDER BY ordre");
    return $nav;
}

/***********
* alert folder install exist
***********/
if(file_exists('install.php')){

    echo "<div class='notify notify-rouge'><div class='notify-box-content'><li>il faut supprimer de fichier install.php pour eviter le hack</li></div></div>";

}
