<?php

namespace App;

use Framework;

class Validator{
	
	/**
	 * errors
	 *
	 * @var array
	 */
	private $errors = [];
	
	private App $app;
	private Framework\Router $router;
	private Parameters $parameters;
	private Database $cnx;

	public function __construct()
	{
		$this->app = new App;
		$this->router = new Framework\Router;
		$this->parameters = new Parameters;
		$this->cnx = new Database;
	}

	/**
	 * required vérifie que les champ sont présent dans le tableau
	 *
	 * @param  mixed $keys
	 * @return self
	 */
	public function required(string $key): self
	{
		if(is_null($key))
		{
			var_dump($key);
			$this->errors[] = "Tout les champs sont requis";
		}
		return $this;
	}

	/**
	 * isDiffent on vérifie si les valeurs sont différente
	 *
	 * @param  mixed $key
	 * @param  mixed $field
	 * @return self
	 */
	public function isDifferent(string $key, $field): self 
	{
		if($key != $field)
		{
			$this->errors[] = "Les champ sont différent";
		}
		return $this;
	} 
	
	/**
	 * fileExist vérifie si le fichier exist bien
	 *
	 * @param  mixed $key
	 * @param  mixed $path
	 * @return self
	 */
	public function fileExist(string $key, string $path): self
	{
		if(!file_exists($path . $key))
		{
			$this->errors[] = "Le fichier n'existe pas";
		}
		return $this;
	}
	
	/**
	 * extensionAllowed vérifie si le fichier est bien valid
	 *
	 * @param  mixed $key
	 * @param  mixed $ext_autorize
	 * @return self
	 */
	public function extensionAllowed(string $key, $ext_autorize = []): self
	{
		if(!in_array($key, $ext_autorize))
		{
			$this->errors[] = "le fichier n'est pas valide PNG|JPG uniquement";
		}
		return $this;
	}
	
	/**
	 * sizeFileUpload vérifie la taille du fichier
	 *
	 * @param  mixed $key
	 * @param  mixed $max_size
	 * @return self
	 */
	public function sizeFileUpload(string $key,int $max_size) :self
	{
		if($key > $max_size)
		{
			$this->errors[] = "le fichier est trop volumineux 40ko max";
		}
		return $this;
	}


	/**
	 * isReqExist vérifie si une valeur existe déjà en base de donnée
	 *
	 * @param  mixed $key
	 * @return self
	 */
	public function isReqExist($key): self
	{
		if($key)
		{
			$this->errors[] = "Le champ existe déjà en base de donnée";
		}
		return $this;
	}
	
	/**
	 * postOk vérifie si un utlisateur a le droit d'édité un topic ou une réponse etc... en fonction de $_SESSION['auth']->authorization
	 * trois niveau par defaut 1|2|3 respectivement membre modo et admin
	 * @param  mixed $key
	 * @param  mixed $lvl
	 * @return self
	 */
	public function postOk(int $key, array $lvl = [1,2,3]): self
	{
		if(!in_array($key, $lvl))
		{
			$this->app->setFlash('Vous devez avoir le bon rang pour poster');
			$this->app->redirect($this->router->routeGenerate('home'));
		}
		return $this;
	}
	
	/**
	 * minLength valide le contenue du site limite de caractères
	 *
	 * @param  mixed $key
	 * @param  mixed $limit
	 * @return self
	 */
	public function minLength(string $key, int $limit): self
	{
		if(grapheme_strlen($key) <= $limit)
		{
			$this->errors[] = "Votre topic dois contenir au moins $limit caractères";
		}
		return $this;
	}
	
	/**
	 * maxLength 
	 *
	 * @param  mixed $key
	 * @param  mixed $max
	 * @return self
	 */
	public function maxLength(string $key,int $max): self
	{
		if(grapheme_strlen($key) >= $max)
		{
			$this->errors[] = "Le champ dois contenir max $max caractères";
		}
		return $this;
	}
	
	/**
	 * betweenLength
	 *
	 * @param  mixed $key
	 * @param  mixed $min
	 * @param  mixed $max
	 * @return self
	 */
	public function betweenLength(string $key, int $min, int $max): self
	{
		if(grapheme_strlen($key) <= $min || grapheme_strlen($key) >= $max)
		{
			$this->errors[] = "Le champ dois contenir min $min & max $max caractères";
		}
		return $this;
	}


	/**
	 * validName check si le username est bien valid
	 *
	 * @param  mixed $key
	 * @return self
	 */
	public function validName(string $key): self
	{
		if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý_-]{3,20}$/', $key))
		{
			$this->errors[] = "Le champ doit contenir 3|20 caractères alphanuméric (accent compris) tirets (-) et underscores (_) pas d'espaces.";
		}
        return $this;
	}
	
	/**
	 * validTtitle validation pour les tritre
	 *
	 * @param  mixed $key
	 * @return self
	 */
	public function validTtitle(string $key): self
	{
		if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý\-\'\,.!?\s]{5,50}$/',$key))
		{
			$this->errors[] = "Le titre dois contenir 5|50 caractère (accent compris et espaces) et des (,|.|!|?)";
		}
		return $this;
	}
	
	/**
	 * validPagination valide les pagination
	 *
	 * @param  mixed $key
	 * @return self
	 */
	public function validPagination(string $key): self
	{
		if(!preg_match("#^(10|15|20)$#",$key))
		{
			$this->errors[] = "Le formulaire n'est pas valide seulement int 10|15|20 sont possible";
		}
		return $this;
	}
	
	/**
	 * alertColor validation couleur du widget alert
	 *
	 * @param  mixed $key
	 * @return self
	 */
	public function alertColor(string $key): self
	{
		if(!preg_match("#^(turquoise|jaune|gris|rouge|orange|marine|bleu|violet|vert)$#",$key))
		{
			$this->errors[] = "Le formulaire n'est pas valide seulement turquoise|jaune|gris|rouge|orange|marine|bleu|violet|vert sont disponible";
		}
		return $this;
	}
	
	/**
	 * validActivParam validation activation|desactivation du widget alert
	 *
	 * @param  mixed $key
	 * @return self
	 */
	public function validActivParam(string $key): self
	{
		if(!preg_match("#^(oui|non)$#",$key))
		{
			$this->errors[] = "Le formulaire n'est pas valide seulement oui|non sont possible";
		}
		return $this;
	}
	
	/**
	 * validThemeName le nom de theme
	 *
	 * @param  mixed $key
	 * @return self
	 */
	public function validThemeName(string $key): self
	{
		if(!preg_match('#^[a-z]{4,10}$#',$key)){
			$this->errors[] = "Le champ theme n'est pas valide (4|10) caractère minuscule uniquement";
		}
		return $this;
	}
	
	/**
	 * validEmail filtre et valide les emails
	 *
	 * @param  mixed $key
	 * @return self
	 */
	public function validEmail(string $key): self
	{
		if(filter_var($key, FILTER_VALIDATE_EMAIL) === false)
		{
			$this->errors[] = "Les email ne sont pas valide";
		}
		return $this;
	}
	
	/**
	 * validMdp valid les mots de pass
	 *
	 * @param  mixed $key
	 * @return self
	 */
	public function validMdp(string $key): self
	{
		if(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,50}$/', $key)) 
		{
			$this->errors[] = "Le mots de pass doit être composé de 8|50 caractères, de minuscules, une majuscule de chiffres et d’au moins un caractère spécial";
		}
		return $this;
	}

	/**
	 * notEmpty vérifie que le champ n'est pas vide
	 *
	 * @param  mixed $keys
	 * @return self
	 */
	public function notEmpty(string ...$keys): self
	{
		foreach($keys as $key){
			if(is_null($key) || empty($key))
			{
				$this->errors[] = "Les champ ne doivent pas être vide";
			}
		}
		return $this;
	}

	public function getErrors(): array
	{
		return $this->errors;
	}

	public function isValid(): bool
	{
		return empty($this->errors);
	}


} 