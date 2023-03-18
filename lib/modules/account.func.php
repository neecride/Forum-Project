<?php

$App->isNotConnect();

$user = (new Action\AccountAction())->desactivAccount();
