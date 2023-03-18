<?php
/******
*captcha
******/	
session_start();
$code = mt_rand(10000, 99999);

$_SESSION['captcha'] = $code;

$img = imagecreate(65,30);

$noir = imagecolorallocate($img, 0,0,0);
$blanc = imagecolorallocate($img,255,255,255);

$font = 13;

header('Content-type:image/jpeg');
imagestring($img,$font,10,7,$code,$blanc);
imagejpeg($img,null,75);
imagedestroy($img);