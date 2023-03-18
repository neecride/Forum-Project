<?php

$pagination->CountIdForpagination('SELECT COUNT(id) FROM f_topics_reponse WHERE f_topic_id = ?', $match['params']['id']);

$pagination->isExistPage();

$Response = (new Action\TopicAction($match['params']['id']))
            ->postResponses($pagination->isPage())
            ->viewNotView()
            ->resolved()
            ->sticky()
            ->nbView()
            ->getTopicExist();