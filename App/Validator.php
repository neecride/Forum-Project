<?php

namespace App;

use DateTime;

class Validator{
	
	/**
	 * errors
	 *
	 * @var array
	 */
	private $errors = [];
	
	private App $app;
	private Router $router;
	private Parameters $parameters;
	private Database $cnx;

	public function __construct()
	{
		$this->app = new App;
		$this->router = new Router;
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
		if(is_null($key)){
			var_dump($key);
			$this->errors = ["Tout les champs sont requis"];
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
		if($key != $field){
			$this->errors = ["Les champ sont différent"];
		}
		return $this;
	} 

	public function fileExist(string $key, string $path): self
	{
		if(!file_exists($path . $key)){
			$this->errors = ["Le fichier n'existe pas"];
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
		if($key){
			$this->errors = ["Le champ existe déjà en base de donnée"];
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
		if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý_-]{3,20}$/', $key)){
			$this->errors = ["Le champ doit contenir 3|20 caractères alphanuméric (accent compris) tirets (-) et underscores (_) pas d'espaces."];
		}
        return $this;
	}

	public function validTtitle(string $key): self
	{
		if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý\-\'\!?\s]{5,50}$/',$key)){
			$this->errors = ["Le titre dois contenir 5|50 caractère (accent compris et espaces) et des (!|?)"];
		}
		return $this;
	}
	
	public function validEmail(string $key): self
	{
		if(filter_var($key, FILTER_VALIDATE_EMAIL) === false){
			$this->errors = ["Les email ne sont pas valide"];
		}
		return $this;
	}

	public function validMdp(string $key): self
	{
		if(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,50}$/', $key)) {
			$this->errors = ["Le mots de pass doit être composé de 8|50 caractères, de minuscules, une majuscule de chiffres et d’au moins un caractère spécial"];
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
			if(is_null($key) || empty($key)){
				$this->errors = ["Les champ ne doivent pas être vide"];
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
