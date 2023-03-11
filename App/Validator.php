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
	
	/**
	 * params
	 *
	 * @var array
	 */
	private $params;
	private App $app;
	private Router $router;
	private Parameters $parameters;

	public function __construct(array $params)
	{
		$this->app = new App;
		$this->params = $params;
		$this->router = new Router;
		$this->parameters = new Parameters;
	}
	
	/**
	 * required vérifie que les champ sont présent dans le tableau
	 *
	 * @param  mixed $keys
	 * @return self
	 */
	public function required(string ...$keys): self
	{	
		foreach($keys as $key){
			$value 	=  (string) $this->getValue($key);
			if(is_null($value) || empty($value)){
				$this->addError($key, 'required');
			}
		}
	
		return $this;
	}

	/*
	* si on a une traduction 
	* return error $this->addError($key, 'slug');
	*/
	/**
	 * isDiffent on vérifie si les valeurs sont différente
	 *
	 * @param  mixed $key
	 * @param  mixed $value
	 * @return self
	 */
	public function isDifferent(string $key, string $field): self 
	{	
		$value 	=  (string) $this->getValue($key);
		if($value !== $field){
			$this->addError($key, 'isDifferent');
		}
		return $this;
	} 

	public function fileExist(string $key): self
	{
		$value 	=  (string) $this->getValue($key);
		if(!file_exists('inc/img/avatars/' . $value)){
			$this->addError($key, 'fileExist');
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
		$value 	=  (string) $this->getValue($key);
		var_dump($value);
		if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý_-]{3,20}$/', $value)){
			$this->addError($key, 'validName');
		}
        return $this;
	}

	public function validTtitle(string $key): self
	{
		$value 	=  (string) $this->getValue($key);
		if(!preg_match('/^[a-zA-Z0-9ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæçèéêëìíîïñòóôõöøùúûüý\-\'\!?\s]{5,50}$/',$value)){
			$this->addError($key, 'validTitle');
		}
		return $this;
	}

	public function validEmail(string $key): self
	{
		$value 	=  (string) $this->getValue($key);
		if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
			$this->addError($key, 'validEmail');
		}
		return $this;
	}

	public function validMdp(string $key): self
	{
		$value 	=  (string) $this->getValue($key);
		if(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,50}$/', $value)) {
			$this->addError($key, 'validMdp');
		}
		return $this;
	}


	public function length(string $key, ?int $min, ?int $max = null): self
	{
		$value 	=  (string) $this->getValue($key);
		$length = grapheme_strlen($value);
			if
			(
				!is_null($min) &&
				!is_null($max) &&
				($length < $min || $length > $max)
			)
			{
				$this->addError($key, 'betweenLength', [$min, $max]);
				return $this;
			}
			if
			(
				!is_null($min) &&
				($length < $min)
			)
			{
				$this->addError($key, 'minLength', [$min]);
				return $this;
			}
			if
			(
				!is_null($max) &&
				($length > $max)
			)
			{
				$this->addError($key, 'maxLength', [$max]);
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
			$value 	=  (string) $this->getValue($key);
			if(is_null($value) || empty($value)){
				$this->addError($key, 'notEmpty');
			}
		}
		return $this;
	}

	public function isValid(): bool
	{
		return empty($this->errors);
	}
	
	/**
	 * dateTime
	 *
	 * @param  mixed $key
	 * @param  mixed $format
	 * @return self
	 */
	public function dateTime(string $key, string $format = "Y-m-d H:i:s"): self
	{
		$value = (string) $this->getValue($key);
		$date = DateTime::createFromFormat($format, $value);
		$errors = DateTime::getLastErrors();
		if($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === null){
			$this->addError($key, 'dateTime');
		}
		return $this;
	}
		
	/**
	 * getErrors recupère les erreurs
	 *
	 * @return ValidationError[]
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}

		
	/**
	 * getValue
	 *
	 * @param  mixed $key
	 * @return void
	 */
	private function getValue(string $key)
	{
		if(array_key_exists($key, $this->params)){
			return $this->params[$key];
		}
		return null;
	}
	
	/**
	 * addError ajoute une erreur
	 *
	 * @param  mixed $key
	 * @param  mixed $rule
	 * @param  mixed $attributes
	 * @return void
	 */
	private function addError(string $key, string $rule, $attributes = []): void
	{
		$this->errors[$key] = new ValidationError($key, $rule);
	}

} 
