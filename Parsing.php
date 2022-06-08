<?php

namespace App;

use Aidantwoods\SecureParsedown\SecureParsedown;

class Parsing{


    public function SetPurify($parse){

        require_once ('..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'htmlpurifier'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'HTMLPurifier.auto.php');

        $purifierConfig = \HTMLPurifier_Config::createDefault();
        $purifierConfig->set('Core.Encoding', 'UTF-8');
        $purifierConfig->set('HTML.Allowed', 'p, a[href|title], blockquote[cite],span[style|class], table[style], thead, tr, th[style], td[style], tbody, pre, code[class|style], hr, em, strong, ul, li, img[src|alt|class], br, ol, del, h1, h2, h3, h4, h5, h6');
        $Purifier = new \HTMLPurifier($purifierConfig);
        
        return $Purifier;
    }


    //parsedown methode
    public function SetParse(){

        $parsedown = new \Parsedown();
        $parsedown->setSafeMode(true);

        return $parsedown;
    }


    public function ParserEmoji($text){

        // Nos variables purification et parser
    
        $text = $this->SetParse()->text($text);
    
        // On remplace le code emoji trouver dans le text
        $emoji_remplace = [':grinning:'];
    
        // Par une image
    
        $emoji = ["<img src='".WEBROOT."inc/js/krajee-markdown-editor/img/72x72/1f603.png' />"];
    
        // On remplace ce que l'on trouve dans nos tableau par des emoji
    
        $comment = str_replace($emoji_remplace ,$emoji , $text);
    
        // On renvoie la réponse purifier, parser avec les emojis
    
        return $text;
    
    }



    /**************
    * function prurification parser et smilyser
    ***************/
    public function Rendertext($content){

        //notre purification et parser if you use htmlpurifier -> $Purifier->purify()
        $content = $this->SetParse()->text($content);
        $this->SetPurify($content);
        return $content;
    }

    public function Renderline($content){

        //notre purification et parser if you use htmlpurifier -> $Purifier->purify()
        $content = $this->SetParse()->line($content);
        $this->SetPurify($content);
        return $content;
    }

}
