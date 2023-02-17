<?php

class Form{
	
	public function MarkEdit($id, $sql=''){

		$req = isset($sql) && !empty($sql) ? $sql : '' ;
		$value = isset($_POST[$id]) ? htmlspecialchars($_POST[$id]) : $req ;
		$editor = "<textarea type='text' data-provide='markdown-editable' class='form-control' id='editor1' name='$id'>$value</textarea><div id='preview'></div>";

		return $editor;
	}
}