<?php
    function AppDate($d){

        $datefmt = datefmt_create('fr_FR',\IntlDateFormatter::MEDIUM,\IntlDateFormatter::SHORT,'Europe/London');
        
        return datefmt_format($datefmt ,strtotime($d));
    }


  $home = $db->query("SELECT

    f_topics.id AS topicid,
    f_topics.f_topic_name,
    f_topics.f_topic_slug AS topicslug,
    f_topics.f_topic_content,
    f_topics.f_topic_date,
    f_topics.f_topic_update_date,
        users.id AS usersid,
        users.username,
        users.avatar,
        users.email

    FROM f_topics

    LEFT JOIN users ON users.id = f_topics.f_user_id

    WHERE sticky = 1

    GROUP BY f_topics.id

    ORDER BY f_topics.f_topic_date DESC LIMIT 6
    
    ");


function Tags($id){

    global $db;

    $tag = $db->prepare('SELECT 
        
    f_topic_tags.topic_id,
    f_topic_tags.tag_id,
        f_tags.id AS tagid,
        f_tags.name,
        f_tags.slug AS tagslug
    
    FROM 
    
    f_topic_tags
    
    LEFT JOIN f_tags ON f_topic_tags.tag_id = f_tags.id
    
    WHERE f_topic_tags.topic_id = ?

    ORDER BY ordre
    ');
    $tag->execute([$id]);
    
    $row = $tag->fetchAll();

    return $row;
    
}

$tagList = $db->query('SELECT * FROM f_tags ORDER BY ordre ASC');

