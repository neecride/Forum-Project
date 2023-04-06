<?php

namespace Controllers;

use Action\AccountAction;
use Action\ForumAction;
use App\App;
use App\Pagination;
use App\Renderer;

class AccountController extends Renderer
{

    public function account()
    {
        $app = new App;
        $forum = new ForumAction;
        $pagination = new Pagination;
        $pagination->CountIDForpagination('SELECT COUNT(id) FROM f_topics WHERE f_user_id = ?', $_SESSION['auth']->id);
        $app->isNotConnect();
        $user = (new AccountAction)->desactivAccount()->editEmail()->postAvatar()->delAvatar()->postDescription()->editMdp();
        $this->render('account', compact('user','forum','pagination'));
    }

}