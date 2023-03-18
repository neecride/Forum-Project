<?php
/********
 *  pagination
 *********/

$pagination->CountIdForpagination('SELECT COUNT(f_tags.id) 
FROM f_topics LEFT JOIN f_topic_tags 
ON f_topics.id = f_topic_tags.topic_id 
LEFT JOIN f_tags ON f_topic_tags.tag_id = f_tags.id
WHERE f_tags.id = ?',$match['params']['id']);

$pagination->isExistPage();

$forum->getViewForumExist();