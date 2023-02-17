<?php
require_once('config.php');


try{
	$db = new PDO('mysql:host=' . DBHOST . ';dbname='.DBNAME,DBUSER,DBPSWD,array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4' ));
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);//ou FETCH_ASSOC
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //exception or WARNING
}catch(Exception $e){
	/* echo $e->getMessage(); */
	die('Imopsible de ce connecter a la BDD');
}
