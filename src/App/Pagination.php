<?php

namespace App;

use Framework\Router;
use PDO;


Class Pagination {

	private $count;
	private $app;
	private $cnx;
	private $router;
	private $parameters;

	public function __construct()
	{
		$this->app = new App;
		$this->cnx = new Database;
		$this->router = new Router;
		$this->parameters = new Parameters;
	}

	public function Perpage(): int
	{
		return (int) $this->parameters->GetParam(2);
	}

    /**
     * getInt vérifie si l'index page est bien un int redirige si non
     *
     * @param  mixed $name
     * @param  mixed $default
     * @return int
     */
    private function getInt(string $name, ?int $default = null): ?int
    {
        $match = $this->router->matchRoute();
        if(!isset($_GET[$name])) return $default;
        	if($_GET[$name] === '0') return 0;
				if(!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
					if(isset($match['params']) && $match['params'] != null){
						if(isset($match['params']['slug']) && $match['params']['slug'] != null){
							header('Location:' . $this->router->routeGenerate($match['name'], ['slug' => $match['params']['slug'], 'id' => $match['params']['id']]));
						}else{
							header('Location:' . $this->router->routeGenerate($match['name'], ['id' => $match['params']['id']]));
						}
					}else{
						header('Location:' . $this->router->routeGenerate($match['name']));
					}
					$this->app->setFlash("Le paramètre $name dans l'url n'est pas un entier",'orange');
					http_response_code(301);
					exit();
				}
        return (int) $_GET[$name];
    }

    /**
     * getPositiveInt vérifie si l'index page est bien un int positive redirige si non
     *
     * @param  mixed $name
     * @param  mixed $default
     * @return int
     */
    private function getPositiveInt(string $name, ?int $default = null): ?int
    {
		$match = $this->router->matchRoute();
        $param = self::getInt($name, $default);
        if($param !== null && $param <= 0){
            if(isset($match['params']) && $match['params'] != null){
                if(isset($match['params']['slug']) && $match['params']['slug'] != null){
                    header('Location:' . $this->router->routeGenerate($match['name'], ['slug' => $match['params']['slug'], 'id' => $match['params']['id']]));
                }else{
                    header('Location:' . $this->router->routeGenerate($match['name'], ['id' => $match['params']['id']]));
                }
            }else{
                header('Location:' . $this->router->routeGenerate($match['name']));
            }
            $this->app->setFlash("Le paramètre $name dans l'url n'est pas un entier positif",'orange');
            http_response_code(301);
            exit();
        }
        return $param;
    }

	
	/**
	 * isExistPage vérifie si une page existe redirige si non
	 *
	 * @return void
	 */
	public function isExistPage()
	{
		$match = $this->router->matchRoute();
		if($this->CurrentPage() > $this->isPage() && $this->CurrentPage() > 1) {
			$this->app->setflash("Ce numéro de page n'existe pas","orange");
			if(isset($match['params']['slug']) && $match['params']['slug'] != null){
				header('Location:' . $this->router->routeGenerate($match['name'], ['slug' => $match['params']['slug'], 'id' => $match['params']['id']]));
			}else{
				header('Location:' . $this->router->routeGenerate($match['name'], ['id' => $match['params']['id']]));
			}
			http_response_code(301);
			exit();
		}
	}


	public function CurrentPage(): int
	{
		return $this->getPositiveInt('page', 1);
	}

	
	/**
	 * CountIdForpagination compte le nombre d'enregistrement en base de donnée
	 *
	 * @param  mixed $statement
	 * @param  mixed $attr
	 * @return void
	 */
	public function CountIdForpagination(string $statement, ?int $attr=null)
	{
		if(!is_null($attr)){
			if(is_null($this->count)){
				$smtp = $this->cnx->thisPdo()->prepare($statement);
				$smtp->execute([intval($attr)]);
				$this->count = (int) $smtp->fetch(PDO::FETCH_NUM)[0];
			}
			return $this->count;
		}else{
			if(is_null($this->count)){
				$this->count = (int) $this->cnx->thisPdo()->query($statement)->fetch(PDO::FETCH_NUM)[0];
			}
			return $this->count;
		}
	}
	
	/**
	 * isPage retourn la page en get démarre de zero
	 *
	 * @return int
	 */
	public function isPage(): int
	{
		return ceil($this->count/$this->parameters->GetParam(2));
	}

	
	/**
	 * userLinkPage retourn un lien qui redirige vers la dernière réponse
	 *
	 * @param  mixed $id
	 * @param  mixed $idrep
	 * @param  mixed $countid
	 * @return void
	 */
	public function userLinkPage(string $id,int $idrep,int $countid)
	{

		$t = (int) ceil($countid/$this->parameters->GetParam(2));
		if($this->parameters->GetParam(2) >= 1){
			if($t === 1){
			  $userLinkPage =  $this->router->routeGenerate('viewtopic', ['id' => $id]).'#rep-' . $idrep;
			}else{
			  $userLinkPage =  $this->router->routeGenerate('viewtopic', ['id' => $id]).'?page='.$t.'#rep-' . $idrep;
			}
		}
		return $userLinkPage;

	}

	private function offset(): int 
	{
		return $this->parameters->GetParam(2) * ($this->CurrentPage() - 1);
	}
	
	public function setOfset()
	{
		return ' '. intval($this->parameters->GetParam(2)) .' OFFSET '. intval($this->offset());
	}

	public function Prev($url) 
	{
		$match = $this->router->matchRoute();
		if($this->isPage() >= 2):
			if ($this->CurrentPage() > 1) {
				$link = $url;
				if ($this->CurrentPage() > 2) {
					$link .= "?page=" . ($this->CurrentPage() - 1);
				}
				$here = $link;
				return "<li class='page-item'><a class='page-link' href='$here'><i class='fas fa-angle-double-left'></i></a></li>";
			} else {
				return '<li class="disabled page-item"><a class="page-link"><i class="fas fa-angle-double-left"></i></a></li>';
			}
		endif;
	}

	public function Next($url) 
	{
		$match = $this->router->matchRoute();
		if($this->isPage() >= 2):
			if ($this->CurrentPage() < $this->isPage()) {

				$curentplus = $url."?page=" . ($this->CurrentPage()+1);
				return "<li class='page-item'><a class='page-link' href='$curentplus'><i class='fas fa-angle-double-right'></i></a></li>";

			} else {
				return '<li class="disabled page-item"><a class="page-link"><i class="fas fa-angle-double-right"></i></a></li>';
			}
		endif;
	}

	/*
	* génère un lien qui pointe vers la bonne page
	* javascript si les liens généré sont superieur a 10 alors faire une fonction pour slider les nombres avec les flèche
	*/
	public function tinyLinkPage(int $id,int $countid)
	{
		$t = (int) ceil($countid/$this->parameters->GetParam(2));
		if($t > 1){
			echo '<div class="uri">';
				echo '<span id="urileft"><i class="fas fa-caret-left"></i></span>';
					for($i=1; $i <= $t; $i++)
					{
						if($i == 1){
							echo "<a class=\"item\" href=".$this->router->routeGenerate('viewtopic', ['id' => $id]).">$i</a>";
						}elseif($i >= 2){
							echo "<a class=\"item\" href=".$this->router->routeGenerate('viewtopic', ['id' => $id]).'?page='.$i.">$i</a>";
						}
					}
				echo '<span id="uriright"><i class="fas fa-caret-right"></i></span>';
			echo '</div>';
		}
	}

	/*
	* return pagination numbers
	*/
	public function pageFor()
	{
		$match = $this->router->matchRoute();
		if($this->isPage() >= 2):
			if(isset($match['name']) && $match['name'] == 'forum-tags'){
				$url = $this->router->routeGenerate($match['name'], ['slug' => $match['params']['slug'], 'id' => $match['params']['id']]);
			}elseif(isset($match['name']) && $match['name'] == 'viewtopic'){
				$url = $this->router->routeGenerate($match['name'], ['id' => $match['params']['id']]);
			}elseif(isset($match['name']) && in_array($match['name'], ['forum'])){
				$url = $this->router->routeGenerate($match['name']);
			}
			$nb=2;
			echo $this->prev($url);
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
					$ii = ($i-1);
					echo "<li class='page-item'><a class='page-link' href='$url'?page='$ii'>...</a></li>";
				}
			}
			echo $this->next($url);
		endif;
	}


}
