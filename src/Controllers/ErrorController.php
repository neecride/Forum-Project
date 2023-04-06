<?php

namespace Controllers;

use App\Renderer;

class ErrorController extends Renderer
{

    public function error()
    {
        $this->render('error');
    }

}