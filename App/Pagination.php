<?php

namespace App;


Class Pagination extends Database{
	
	/*
	* return instance Parameters
	*/
	private function Params(){
		return new Parameters();
	}	

	public function RequestTarget(){

		if(isset($_GET['page'])){
			$getpage = (int) $_GET['page'];
			return $getpage;
		}

	}
	
	//si besoin on a notre objet pdo
	public function thisPDO(){
		return $this->Getpdo();
	}

	/*
	* return instance PDO
	*/
	private function Cnx(){
		return new Database();
	}		

	/*
	* return pagemax pagination
	*/
	private function PagesMax(){

		//$this->Params()->GetParam(2)

		return $this->Params()->GetParam(2);

	}

	public function CountPage($statement,$attr=null){


			$req = $this->Getpdo()->prepare($statement);
			
			$req->execute($attr);

			$results = $req->rowCount();

			return $results;

	}


	/*
	* return count article in database
	*/
	public function CountData(){

		return $this->Cnx()->CountID('SELECT id FROM f_topics');

	}

	/*
	* return split count articles and pagemax pagination
	*/
	private function PageTotales(){

		return ceil($this->CountData()/$this->PagesMax());
	}

	/*
	* return star loop pagination
	*/
	private function StartPager(){

		return ($this->CurrentPage()-1)*$this->PagesMax();

	}


	/*
	* return pagination limit
	*/
	public function Limited(){

		return $this->StartPager() .','. $this->PagesMax();

	}

	/*
	* return current page where get ppagination 
	*/
	private function CurrentPage(){

		if($this->RequestTarget() > 0 AND $this->RequestTarget() <= $this->PageTotales()) {
		    
		   $CurrentPage = $this->RequestTarget();
		    
		} else {
		    //si id null alors page sera egal 1 
		    $CurrentPage = 1;

		}

		return $CurrentPage;

	}


	/*
	* return pagination view
	*/
	function Pager($nb=2,$page=null,$statement,$attr=null){
	
	
		if(!empty($this->CountPage($statement,$attr=null) > $this->PagesMax())){
	
		echo '<div class="pag"><ul class="pagination mb-3 mt-3 pagination-sm">';
		//prev
		if($this->CurrentPage() > "1"){
	
			$prev = $this->CurrentPage()-1;
			echo '<li class="page-item"><a class="page-link" href=' . $this->GetApp()->webroot() .$page.$prev.'><i class="fas fa-angle-double-left"></i></a></a></li>' ;
	
		}else{
	
			 echo '<li class="disabled page-item"><a class="page-link"><i class="fas fa-angle-double-left"></i></a></li>';
	
		}
	
		//pagination current
	
			for($i=1; $i <= $this->PageTotales(); $i++) {
	
				if($i <= $nb || $i > $this->PageTotales() - $nb ||  ($i > $this->CurrentPage()-$nb && $i < $this->CurrentPage()+$nb)){
	
					if($i == $this->CurrentPage()) {
	
						echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
	
	
					} else {
	
						echo '<li class="page-item"><a class="page-link" href=' . $this->GetApp()->webroot() .$page.$i.'>'. $i .'</a></li>' ;
	
					}
	
				}else{
					if($i > $nb && $i < $this->CurrentPage()-$nb){
						$i = $this->CurrentPage() - $nb;
					}elseif($i >= $this->CurrentPage() + $nb && $i < $this->PageTotales()-$nb){
						$i = $this->PageTotales() - $nb;
					}
					echo '<li class="page-item"><a class="page-link" href=' . $this->GetApp()->webroot() .$page . ($i-1) .'>...</a></li>';
				}
	
			}
	
		//next last page
		if($this->CurrentPage() != $this->PageTotales()){
	
			$next = $this->CurrentPage()+1;
	
			echo '<li class="page-item"><a class="page-link" href=' . $this->GetApp()->webroot() .$page .$next.'><i class="fas fa-angle-double-right"></i></a></li>' ;
	
		}else{
	
			echo '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-right"></i></a></li>';
	
		}
	
		echo '</ul></div>';
		}
	}


}