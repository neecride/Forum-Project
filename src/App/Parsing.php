<?php

namespace App;

use Parsedown;

class Parsing{


    private $parameters;

    public function __construct()
    {
        $this->parameters   = new Parameters;
    }


    public function SetPurify(){

        require_once ('..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'htmlpurifier'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'HTMLPurifier.auto.php');

        $purifierConfig = \HTMLPurifier_Config::createDefault();
        $purifierConfig->set('Core.Encoding', 'UTF-8');
        $purifierConfig->set('HTML.Allowed', 'p, a[href|title], blockquote[cite],span[style|class], table[style], thead, tr, th[style], td[style], tbody, pre, code[class|style], hr, em, strong, ul, li, img[src|alt|class], br, ol, del, h1, h2, h3, h4, h5, h6');
        $Purifier = new \HTMLPurifier($purifierConfig);
        return $Purifier;
    }

    //parsedown methode
    public function SetParse()
    {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
        return $parsedown;
    }

    /**************
    * function prurification parser et smilyser
    ***************/
    public function Rendertext($content)
    {
        //notre purification et parser if you use htmlpurifier -> $Purifier->purify()
        $content = $this->SetParse()->text($content);
        $this->SetPurify($content);
        return $content;
    }

    public function Renderline($content)
    {
        //notre purification et parser if you use htmlpurifier -> $Purifier->purify()
        $content = $this->SetParse()->line($content);
        $this->SetPurify($content);
        return $content;
    }

    public function JustDemo(){
        return "**bonjour je suis du [markdown](https://fr.wikipedia.org/wiki/Markdown#Formatage)**\n\n~~pourquoi~~\n\n> parce que c'est cool\n\n";
    }

    public function MarkDownEditor(string $id, string $sql=null,string $editor = 'editor1')
    {
        $req = isset($sql) && !empty($sql) ? $sql : $this->JustDemo() ;
        $value = isset($_POST[$id]) && !empty($_POST[$id]) ? strip_tags($_POST[$id]) : $req ;
        return "<textarea style='position:relative;' type='text' data-rows='32' class='markdown' data-language='fr' data-height='100px' class='myarea form-control' id=\"$editor\" name=\"$id\">$value</textarea><div id='preview'> </div>";
    }

    public function input(string $id,string $type,string $PlaceHolder=null,string $required=null,string $sql=null)
    {
        $req = isset($sql) && !empty($sql) ? $sql : null ;
        //stocke la req si elle existe sinon met $_POST
        $value = isset($_POST[$id]) && !empty($_POST[$id]) ? strip_tags($_POST[$id]) : $req ;
        return "<input type=\"$type\" class=\"HoTagsI form-control\" id=\"$id\" name=\"$id\" placeholder=\"$PlaceHolder\" value=\"$value\" $required>";
    }

	public function checkFilesOptions(string $sql = null)
    {
		$fichiers = scandir(RACINE.DS.'public'.DS.'templates');
        // Parcourt la liste des fichiers et dossiers
        foreach ($fichiers as $fichier) :
            $selected = null;
            if(isset($sql) && !empty($sql == $fichier)){
                $selected =  ' selected="selected"';
			}
            // Ignore les fichiers qui ne sont pas des dossiers ou le dossier courant ou le dossier parent
            if (is_dir(RACINE.DS.'public'.DS.'templates' . DS . $fichier) && $fichier !== '.' && $fichier !== '..') {
                // Affiche le nom du dossier
                echo "<option value=".$fichier." $selected>$fichier</option>";
            }
        endforeach;
	}

    public function select($id, $options = [])
    {
        $return = "<select class='form-control' id='$id' name='$id'>";
        foreach($options as $k => $v){
            $selected = '';
            if(isset($_POST[$id]) && $k == $_POST[$id]){
                $selected = ' selected';
            }
            $return .= '<option value='.strip_tags($k).' $selected>$v</option>';
        }
        $return .= '</select>';
        return $return;
    }

}
