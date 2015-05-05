<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use ArrayObject;
use Exception;

/**
 * Description of Route
 *
 * @author fabien.sanchez
 */
class Route {

	const METHODES = ['GET', 'POST', 'PATCH', 'PUT', 'DELETE'];

	private $_action;
	private $_type_action;
	private $_path;
	private $_methodes;
	private $_name;
	private $_validation;

	/**
	 * Spécifie une nouvelle route
	 * @param string|array $methode
	 * @param string $path
	 * @param string|callable $action
	 */
	public function __construct($methode, $path, $action) {
		$this->setMethodes($methode);
		$this->_path = $path;
		$this->generateValidation();
		$this->setAction($action);
	}

	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	public function getName() {
		return $this->_name;
	}

	public function getPath() {
		return $this->_path;
	}

	private function setMethodes($methode) {
		if (is_array($methode)) {
			$this->_methodes = $methode;
		} elseif (is_string($methode)) {
			if ($methode === "*") {
				$this->_methodes = self::METHODES;
			} else {
				$this->_methodes = explode('|', preg_replace("`\s*[ .,;\/-]\s*`", "|", $methode));
			}
		} else {
			throw new Exception("La méthode n'est pas reconnue");
		}
		$this->_methodes = array_intersect($this->_methodes, self::METHODES);
	}

	private function setAction($action) {
		if (is_callable($action)) {
			$this->_type_action = "CALLABLE";
			$this->_action = $action;
		} elseif (is_string($action)) {
			$this->_type_action = "CONTROL_ACTION";
			$this->_action = $action;
		} else {
			throw new Exception("L'action n'est pas reconnue");
		}
	}

	public function getMethodes() {
		return $this->_methodes;
	}

	public function getRegEx() {
		$parametres = $this->getParametresName();
		$regex = preg_quote($this->_path, '`');
		foreach ($parametres as $parametre) {
			$regex = str_replace(preg_quote('{' . $parametre . '}', '`'), '(?P<' . $parametre . '>' . $this->_validation[$parametre] . ')', $regex);
		}
		return '`' . $regex . '`U';
	}

	private function getParametresName() {
		$matchs = array();
		preg_match_all("`\{([a-zA-Z][a-zA-Z0-9-_]*)\}`", $this->_path, $matchs);
		return $matchs[1];
	}

	function executeAction(ArrayObject $Parametres) {
		switch ($this->_type_action) {
			case 'CALLABLE':

				break;
			case 'CONTROL_ACTION':

				break;
		}
	}

	private function generateValidation() {
		$this->_validation = array();
		$params = $this->getParametresName();
		foreach ($params as $param) {
			$this->setValidation($param, '.+');
		}
	}

	public function setValidations(ArrayObject $validations) {
		foreach ($validations as $param => $validation) {
			$this->setValidation($param, $validation);
		}
		return $this;
	}

	public function setValidation($param, $validation) {
		$this->_validation[$param] = $validation;
		return $this;
	}

}
