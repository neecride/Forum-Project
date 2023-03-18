<?php


$Response = (new Action\AdminAction())
            ->slogan()
            ->siteName()
            ->alertForm()
            ->alerColor()
            ->activWidget()
            ->themeUpdate()
            ->paginationPerPage();

/********
* on recupere les entree
*********/
$parameters = $Response->fieldRequest();