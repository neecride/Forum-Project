<?php

use App\TopicAction;

$pagination->CountIdForpagination('SELECT COUNT(id) FROM f_topics_reponse WHERE f_topic_id = ?', $match['params']['id']);

$pagination->isExistPage();

$Response = (new TopicAction($match['params']['id']))
                ->Responses($pagination->isPage())
                ->viewNotView()
                ->resolved()
                ->sticky()
                ->nbView()
                ->getTopicExist();
