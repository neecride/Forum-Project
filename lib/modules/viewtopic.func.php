<?php

use App\TopicAction;

$topic = $forum->firstTopic($match['params']['id']); 

$pagination->CountIdForpagination('SELECT COUNT(id) FROM f_topics_reponse WHERE f_topic_id = ?', $match['params']['id']);

$pagination->isExistPage();

$forum->getTopicExist();

$Response = (new TopicAction())
                ->Responses($pagination->isPage())
                ->viewNotView($topic->f_topic_message_date,$topic->f_topic_date)
                ->resolved()
                ->sticky()
                ->nbView();
