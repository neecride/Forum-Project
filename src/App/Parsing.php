<?php

namespace App;

use Parsedown;

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

        $parsedown = new Parsedown();
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

    public function MarkDownEditor($id,$sql=null){
        $req = isset($sql) && !empty($sql) ? $sql : $this->JustDemo() ;
        $value = isset($_POST[$id]) && !empty($_POST[$id]) ? htmlentities($_POST[$id])  : $req ;
        $editor = "<textarea style='position:relative;' type='text' data-rows='32' class='markdown' data-language='fr' data-height='100px' class='myarea form-control' id='editor1' name='$id'>$value</textarea><div id='preview'> </div>";
        return $editor;
    }

    public function input($id,$type,$CssClass,$PlaceHolder=null,$required=null,$sql=null){
        $req = isset($sql) && !empty($sql) ? $sql : null ;
        $value = isset($_POST[$id]) && !empty($_POST[$id]) ? htmlentities($_POST[$id]) : $req ;
        return "<input type='$type' class='$CssClass' id='$id' name='$id' placeholder='$PlaceHolder' value='$value' $required>";
    }

    
	public function checkFilesOptions(string $sql = null,string $fileUrl = RACINE.DS.'public'.DS.'templates')
    { 
		$scandir = scandir($fileUrl);
        
        //is_file($scandir);
		
        foreach($scandir as $fichier){
			$explode = explode('.',$fichier);
            $selected = null;
			if(isset($sql) && !empty($sql == $explode[0])){
				$selected =  ' selected="selected"';
			} 
			if(preg_match("#\.(php)$#",strtolower($fichier))){
				echo "<option value=".$explode[0]." $selected>$explode[0]</option>";
			}
		}
	}

    public function select($id, $options = []){
        $return = "<select class='form-control' id='$id' name='$id'>";
        foreach($options as $k => $v){
            $selected = '';
            if(isset($_POST[$id]) && $k == $_POST[$id]){
                $selected = ' selected';
            }
            $return .= '<option value='.htmlentities($k).' $selected>$v</option>';
        }
        $return .= '</select>';
        return $return;
    }

}