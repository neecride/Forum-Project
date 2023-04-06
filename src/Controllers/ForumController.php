<?php

namespace Controllers;

use Action\ForumAction;
use Action\TopicAction;
use App\App;
use App\Pagination;
use App\Renderer;

class ForumController extends Renderer
{

    public function home()
    {
        $home = new ForumAction();
        $this->render('home', compact('home'));
    }

    public function forum()
    {
        $forum      = new ForumAction;
        $pagination = new Pagination;

        $pagination->CountIdForpagination('SELECT COUNT(id) FROM f_topics');
        $pagination->isExistPage();

        $this->render('forum',compact('forum', 'pagination'));
    }

    public function viewtopic(int $id)
    {
        $pagination = new Pagination;
        $forum      = new ForumAction;
        $pagination->CountIdForpagination('SELECT COUNT(id) FROM f_topics_reponse WHERE f_topic_id = ?', $id);
        $pagination->isExistPage();
        $Response   = (new TopicAction())
                    ->postResponses($pagination->isPage())
                    ->viewNotView()
                    ->resolved()
                    ->sticky()
                    ->nbView()
                    ->getTopicExist();

        $this->render('viewtopic',compact('forum','Response','pagination'));
    }

    public function viewforum(int $id)
    {
        $viewforum  = new ForumAction;
        $pagination = new Pagination;
        $pagination->CountIdForpagination('SELECT COUNT(f_tags.id) 
        FROM f_topics LEFT JOIN f_topic_tags 
        ON f_topics.id = f_topic_tags.topic_id 
        LEFT JOIN f_tags ON f_topic_tags.tag_id = f_tags.id
        WHERE f_tags.id = ?',$id);
        $pagination->isExistPage();

        $viewforum->getViewForumExist();

        $this->render('viewforums', compact('viewforum', 'pagination'));
    }

    public function creatTopic()
    {
        $app = new App;
        $forum = new ForumAction;
        $app->isNotConnect();
        $topic = (new TopicAction())->creatTopic();
        $this->render('creattopic',compact('topic','forum'));
    }

    public function editTopic()
    {
        $app = new App;
        $forum = new ForumAction;
        $app->isNotConnect();
        $topic = (new TopicAction())->editTopic();
        $this->render('edittopic',compact('topic','forum'));
    }

    public function editRep()
    {
        $app = new App;
        $app->isNotConnect();
        $response = (new TopicAction())->editResponse();
        $this->render('editrep', compact('response'));
    }

}