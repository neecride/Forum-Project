<?php


namespace App;

class Parsing{


    public function SetPurify(){

        require_once ('..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'htmlpurifier'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'HTMLPurifier.auto.php');

        $purifierConfig = \HTMLPurifier_Config::createDefault();
        $purifierConfig->set('Core.Encoding', 'UTF-8');
        $purifierConfig->set('HTML.Allowed', 'p, a[href|title], blockquote[cite],span[style|class], table[style], thead, tr, th[style], td[style], tbody, pre, code[class|style], hr, em, strong, ul, li, img[src|alt|class], br, ol, del, h1, h2, h3, h4, h5, h6');
        $Purifier = new \HTMLPurifier($purifierConfig);
        
        return $Purifier;
    }

    private function GetApp(){
		return new App();
	}

    //parsedown methode
    public function SetParse(){

        $parsedown = new \Parsedown();
        $parsedown->setSafeMode(true);

        return $parsedown;
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

    public function JustDemo(){

        return "**bonjour je suis du [markdown](https://fr.wikipedia.org/wiki/Markdown#Formatage)**\n\n~~pourquoi~~\n\n> parce que c'est cool\n\n";
       
    }

}