<?php

$user = (new Action\AccountAction());

$App->isNotConnect();

$pagination->CountIDForpagination('SELECT COUNT(id) FROM f_topics WHERE f_user_id = ?', $_SESSION['auth']->id);

$pagination->isExistPage();