<?php
$_SESSION = array();
setcookie('remember', NULL, -1);
$app->setFlash('Revenez quand vous voulez !','info');
$app->redirect($router->routeGenerate('home'));