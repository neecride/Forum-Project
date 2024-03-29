<?php

$router->map('GET|POST', '/', 'home');
$router->map('GET|POST', '/home', 'home','home');
$router->map('GET|POST', '/logout', 'logout','logout');
$router->map('GET'     , '/error', 'error','error');
$router->map('GET|POST', '/remember', 'remember','remember');
$router->map('GET|POST', '/reset-[*:username]-[*:token]', 'reset','reset');
$router->map('GET|POST', '/register', 'register','register');
$router->map('GET|POST', '/confirm-[*:username]-[*:token]', 'confirm','confirm');
$router->map('GET|POST', '/login', 'login','login');


//user account
$router->map('GET|POST', '/account', 'account','account');
$router->map('GET|POST', '/survey', 'survey','survey');
$router->map('GET|POST', '/account-edit', 'account-edit','account-edit');


//forum
$router->map('GET'     , '/forum', 'forum','forum');
$router->map('GET'     , '/forum-[*:slug]-[i:id]', 'viewforums','forum-tags');
$router->map('GET|POST', '/forum-[i:id]', 'viewtopic','viewtopic');
$router->map('GET|POST', '/sticky-[i:id]-[i:sticky]-[*:getcsrf]', 'viewtopic','sticky');
$router->map('GET|POST', '/lock-[i:id]-[i:lock]-[*:getcsrf]', 'viewtopic','lock');
$router->map('GET|POST', '/unlock-[i:id]-[i:lock]-[*:getcsrf]', 'viewtopic','unlock');
$router->map('GET|POST', '/creattopic', 'creattopic','creattopic');
$router->map('GET|POST', '/editetopic-[i:id]', 'editetopic','editetopic');
$router->map('GET|POST', '/editerep-[i:id]', 'editerep','editerep');


//administration
$router->map('GET|POST', '/admin/dashboard', 'admin','admin');
$router->map('GET'     , '/admin/user', 'user','user');
$router->map('GET|POST', '/admin/user-edit-[i:id]-[*:getcsrf]', 'user-edit','user-edit');
$router->map('GET|POST', '/admin/user-delete-[i:delid]-[i:rank]-[*:getcsrf]', 'user','user-delete');
$router->map('GET|POST', '/admin/user-active-[i:activid]-[i:rank]-[*:getcsrf]', 'user','user-active');
$router->map('GET'     , '/admin/tags', 'tags','tags');
$router->map('GET|POST', '/admin/tags-add', 'tags-edit','tags-add');
$router->map('GET|POST', '/admin/tags-edit-[*:editid]-[*:getcsrf]', 'tags-edit','tags-edit');
$router->map('GET|POST', '/admin/tags-delete-[*:delid]-[*:getcsrf]', 'tags-edit','tags-delete');


$match = $router->match();
