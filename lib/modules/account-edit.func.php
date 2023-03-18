<?php

$App->isNotConnect();

$user = (new Action\AccountAction())
        ->editEmail()
        ->postAvatar()
        ->delAvatar()
        ->postDescription()
        ->editMdp();