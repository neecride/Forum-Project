<?php

namespace App;

use PDO;

Class Pagination extends Database{
	
	/*
	* return instance Parameters
	*/
	private function Params(){
		return new Parameters();
	}
	
	//si besoin on a notre objet pdo
	public function thisPDO(){
		return $this->Getpdo();
	}

	public function Perpage(){
		return (int) $this->Params()->GetParam(2);
	}

	public function subLinkPage($url,$countid){ 
		
		$tt = ceil($countid/$this->Params()->GetParam(2));

		if($tt > 1){
		  for($ii=1; $ii <= $tt; $ii++){ 

				if($ii == 1){

					echo "<a href=".$url.">$ii</a>";
					
				}else{

					echo "<a href=".$url.'?page='. $ii.">$ii</a>";
				}
 
			}
		}

	}

	public function userLinkPage($url,$id,$countid){
				
		$tt = ceil($countid/$this->Params()->GetParam(2));

		if($this->Params()->GetParam(2) >= $tt){
			if($tt == 1){
			  $linkRedirectPage =  $url.'#rep-' . $id;
			}else{
			  $linkRedirectPage =   $url.'?page='.$tt.'#rep-' . $id;
			}
		}
		return $linkRedirectPage;

	}


	public function offset($PerPage,$CurrentPage){

		return $PerPage * ($CurrentPage - 1);

	}
	
	public function isExistPage($CurrentPage, $pages,$url){
		if($CurrentPage > $pages && $CurrentPage > 1) {
			setflash("Ce numéro de page n'hexiste pas","orange");
			header('Location:' . $url);
			http_response_code(301);
			exit();
		}
	}

	/*
	* return count article in database
	*/
	public function CountIDForpagination($statement,$attr=null)
	{
		if($attr != null){

			$smtp = $this->Getpdo()->prepare($statement);

			$smtp->execute([intval($attr)]);

			$Count = (int) $smtp->fetch(PDO::FETCH_NUM)[0];

			return $Count;

		}else{
			$Count = (int) $this->Getpdo()->query($statement)->fetch(PDO::FETCH_NUM)[0];

			return $Count;
		}
	}


	public function Prev($CurrentPage, $url) {
		if ($CurrentPage > 1) {
			$link = $url;
			if ($CurrentPage > 2) {
				$link .= "?page=" . ($CurrentPage - 1);
			}
			return "<li class='page-item'><a class='page-link' href='$link'><i class='fas fa-angle-double-left'></i></a></li>";
		} else {
			return '<li class="disabled page-item"><a class="page-link"><i class="fas fa-angle-double-left"></i></a></li>';
		}
	}

	public function Next($CurrentPage, $pages, $url) {

		if ($CurrentPage < $pages) {

			return "<li class='page-item'><a class='page-link' href='$url'><i class='fas fa-angle-double-right'></i></a></li>";

		} else {
			return '<li class="disabled page-item"><a class="page-link"><i class="fas fa-angle-double-right"></i></a></li>';
		}
	}


	/*
	* return pagination view
	*/
	public function pageFor($CurrentPage, $pages, $url){

		$nb=2;
		for($i=1; $i <= $pages; $i++){
		  if($i <= $nb || $i > $pages - $nb ||  ($i > $CurrentPage-$nb && $i < $CurrentPage+$nb)){
			if($i == $CurrentPage) {
			  echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
			} elseif($i == 1) {
			  echo '<li class="page-item"><a class="page-link" href='.$url.'>'. $i .'</a></li>' ;
			}else{
			  echo '<li class="page-item"><a class="page-link" href='.$url.'?page='.$i.'>'. $i .'</a></li>' ;
			}
		  }else{
			if($i > $nb && $i < $CurrentPage-$nb){
			  $i = $CurrentPage - $nb;
			}elseif($i >= $CurrentPage + $nb && $i < $pages-$nb){
			  $i = $pages - $nb;
			}
			$it = ($i-1);
			echo "<li class='page-item'><a class='page-link' href='$url'?page='$it'>...</a></li>";
		  }
		}
	
	}


}
