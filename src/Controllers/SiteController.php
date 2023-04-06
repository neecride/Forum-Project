<?php

namespace Controllers;

use Action\LoginAction;
use App\App;
use App\Renderer;

class SiteController extends Renderer
{

    public function error()
    {
        $this->render('error');
    }

    public function logout()
    {
        $app = new App();
        $this->render('logout',compact('app'));
    }

    public function login()
    {
        $app = new App();
        $app->isLogged();
        (new LoginAction())->login();
        $this->render('login');
    }

}