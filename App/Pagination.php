<?php

namespace App;

use PDO;

Class Pagination extends Database{
	
	private $count;

	/*
	* return instance Parameters
	*/
	public function Params()
	{
		return new Parameters();
	}

	public function GetApp()
	{
		return new App();
	}
	
	public function GetRoute()
	{
		return new Router();
	}

	/*
	* notre objet PDO
	*/
	public function thisPDO()
	{
		return $this->Getpdo();
	}

	/*
	* instance de la class Parameters qui retrourne le nombre de pages
	*/
	public function Perpage(): int
	{
		return (int) $this->Params()->GetParam(2);
	}

    public function getInt(string $name, ?int $default = null): ?int
    {	
        $match = $this->GetRoute()->matchRoute();
        if(!isset($_GET[$name])) return $default;
        if($_GET[$name] === '0') return 0;

        if(!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
            if(isset($match['params']) && $match['params'] != null){
                if(isset($match['params']['slug']) && $match['params']['slug'] != null){
                    header('Location:' . $this->GetRoute()->routeGenerate($match['name'], ['slug' => $match['params']['slug'], 'id' => $match['params']['id']]));
                }else{
                    header('Location:' . $this->GetRoute()->routeGenerate($match['name'], ['id' => $match['params']['id']]));
                }
            }else{
                header('Location:' . $this->GetRoute()->routeGenerate($match['name']));
            }
            $this->GetApp()->setFlash("Le paramètre $name dans l'url n'est pas un entier",'orange');
            http_response_code(301);
            exit();
        }
        return (int)$_GET[$name];
    }

    public function getPositiveInt(string $name, ?int $default = null): ?int
    {
		$match = $this->GetRoute()->matchRoute();

        $param = self::getInt($name, $default);
        if($param !== null && $param <= 0){
            if(isset($match['params']) && $match['params'] != null){
                if(isset($match['params']['slug']) && $match['params']['slug'] != null){
                    header('Location:' . $this->GetRoute()->routeGenerate($match['name'], ['slug' => $match['params']['slug'], 'id' => $match['params']['id']]));
                }else{
                    header('Location:' . $this->GetRoute()->routeGenerate($match['name'], ['id' => $match['params']['id']]));
                }
            }else{
                header('Location:' . $this->GetRoute()->routeGenerate($match['name']));
            }
            $this->GetApp()->setFlash("Le paramètre $name dans l'url n'est pas un entier positif",'orange');
            http_response_code(301);
            exit();
        }
        return $param;
    }

	/*
	* initialise la page 
	*/
	public function CurrentPage(): int
	{
		return $this->getPositiveInt('page', 1);
	}

	/*
	* return count ellements in database
	*/
	public function CountIdForpagination(string $statement,int $attr=null)
	{
		if(!is_null($attr)){

			if(is_null($this->count)){

				$smtp = $this->Getpdo()->prepare($statement);
	
				$smtp->execute([$attr]);
	
				$this->count = (int) $smtp->fetch(PDO::FETCH_NUM)[0];
			}

			return $this->count;

		}else{

			if(is_null($this->count)){
				$this->count = (int) $this->Getpdo()->query($statement)->fetch(PDO::FETCH_NUM)[0];
			}
			
			return $this->count;
		}
	}

	public function isPage(): int
	{
		return ceil($this->count/$this->Params()->GetParam(2));
	}

	/*
	* check si la page appeler existe
	*/
	public function isExistPage(string $url)
	{
		if($this->CurrentPage() > $this->isPage() && $this->CurrentPage() > 1) {
			$this->GetApp()->setflash("Ce numéro de page n'existe pas","orange");
			header('Location:' . $url);
			http_response_code(301);
			exit();
		}
	}

	/*
	* retourn une mini pagination 
	*/
	public function userLinkPage(string $url,int $id,int $countid)
	{
				
		$t = (int) ceil($countid/$this->Params()->GetParam(2));

		if($this->Params()->GetParam(2) >= $t){
			if($t == 1){
			  $linkRedirectPage =  $url.'#rep-' . $id;
			}else{
			  $linkRedirectPage =   $url.'?page='.$t.'#rep-' . $id;
			}
		}
		return $linkRedirectPage;

	}

	public function offset(): int 
	{
		return $this->Params()->GetParam(2) * ($this->CurrentPage() - 1);
	}

	public function setOfset()
	{
		return ' '. intval($this->Params()->GetParam(2)) .' OFFSET '. intval($this->offset());
	}

	public function Prev(string $url) 
	{
		if($this->isPage() >= 2):
			if ($this->CurrentPage() > 1) {
				$link = $url;
				if ($this->CurrentPage() > 2) {
					$link .= "?page=" . ($this->CurrentPage() - 1);
				}
				return "<li class='page-item'><a class='page-link' href='$link'><i class='fas fa-angle-double-left'></i></a></li>";
			} else {
				return '<li class="disabled page-item"><a class="page-link"><i class="fas fa-angle-double-left"></i></a></li>';
			}
		endif;
	}

	public function Next(string $url) 
	{
		if($this->isPage() >= 2):
			if ($this->CurrentPage() < $this->isPage()) {

				$curentplus = "?page=" . ($this->CurrentPage()+1);
				return "<li class='page-item'><a class='page-link' href='$url$curentplus'><i class='fas fa-angle-double-right'></i></a></li>";

			} else {
				return '<li class="disabled page-item"><a class="page-link"><i class="fas fa-angle-double-right"></i></a></li>';
			}
		endif;
	}

	/*
	* génère un lien qui pointe vers la bonne page 
	* javascript si les liens généré sont superieur a 10 alors faire une fonction pour coullissé les nombre avec les flèche
	*/
	public function tinyLinkPage(string $url,int $countid)
	{ 
		
		$t = (int) ceil($countid/$this->Params()->GetParam(2));
		if($t > 1){
			echo '<div class="uri">';
				echo '<span id="urileft"><i class="fas fa-caret-left"></i></span>';

					for($i=1; $i <= $t; $i++){ 

						if($i == 1){

							echo "<a href=".$url.">$i</a>";
							
						}elseif($i >= 2){

							echo "<a href=".$url.'?page='. $i.">$i</a>";

						}
		
					}

				echo '<span id="uriright"><i class="fas fa-caret-right"></i></span>';
			echo '</div>';
		}

	}

	/*
	* return pagination numbers
	*/
	public function pageFor(string $url)
	{
		if($this->isPage() >= 2):
			$nb=2;
			for($i=1; $i <= $this->isPage(); $i++){
				if($i <= $nb || $i > $this->isPage() - $nb ||  ($i > $this->CurrentPage()-$nb && $i < $this->CurrentPage()+$nb)){
					if($i == $this->CurrentPage()) {
						echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
					} elseif($i == 1) {
						echo '<li class="page-item"><a class="page-link" href='.$url.'>'. $i .'</a></li>' ;
					}else{
						echo '<li class="page-item"><a class="page-link" href='.$url.'?page='.$i.'>'. $i .'</a></li>' ;
					}
				}else{
					if($i > $nb && $i < $this->CurrentPage()-$nb){
						$i = $this->CurrentPage() - $nb;
					}elseif($i >= $this->CurrentPage() + $nb && $i < $this->isPage()-$nb){
						$i = $this->isPage() - $nb;
					}
					$it = ($i-1);
					echo "<li class='page-item'><a class='page-link' href='$url'?page='$it'>...</a></li>";
				}
			}
		endif;
	
	}


}
