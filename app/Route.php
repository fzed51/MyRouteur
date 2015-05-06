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

	const METHODES = "GET|POST|PATCH|PUT|DELETE";

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
		$this->_path = WEBROOT . $path;
		$this->generateValidation();
		$this->setAction($action);
	}

	/**
	 * Ajoute un nom
	 * @param string $name
	 * @return Route
	 */
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

	/**
	 * initialise les méthodes
	 * @param array|string $methode peut être '*'
	 * @throws Exception
	 */
	private function setMethodes($methode) {
		$liste_methodes = explode('|', self::METHODES);
		if (is_array($methode)) {
			$this->_methodes = $methode;
		} elseif (is_string($methode)) {
			if ($methode === "*") {
				$this->_methodes = $liste_methodes;
			} else {
				$this->_methodes = explode('|', preg_replace("`\s*[ .,;\/-]\s*`", "|", $methode));
			}
		} else {
			throw new Exception("La méthode n'est pas reconnue");
		}
		$this->_methodes = array_intersect($this->_methodes, $liste_methodes);
	}

	private function setAction($action) {
		if (is_callable($action)) {
			$this->_type_action = "CALLABLE";
			$this->_action = $action;
		} elseif (is_string($action) && preg_match("`([a-zA-Z][a-zA-Z0-9_]*)@([a-zA-Z][a-zA-Z0-9_]*)`", $action)) {
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

	function executeAction(array $parametres = []) {
		var_dump($parametres);
		$this->sanitizParametres($parametres);
		var_dump($parametres);
		switch ($this->_type_action) {
			case 'CALLABLE':

				break;
			case 'CONTROL_ACTION':
				preg_match("`([a-zA-Z][a-zA-Z0-9_]*)@([a-zA-Z][a-zA-Z0-9_]*)`", $this->_action, $matchs);
				debug_zval_dump($matchs);
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

	/**
	 * ajoute plusieur validations
	 * @param ArrayObject $validations tableau de prametre => regex de validation
	 * @return Route
	 */
	public function setValidations(ArrayObject $validations) {
		foreach ($validations as $param => $validation) {
			$this->setValidation($param, $validation);
		}
		return $this;
	}

	/**
	 * ajoute une validation
	 * @param string $param nom du paramètre
	 * @param string $validation regex de validation
	 * @return Route
	 */
	public function setValidation($param, $validation) {
		$this->_validation[$param] = $validation;
		return $this;
	}

	/**
	 * netoye les paramètres pour qu'il correspondent à la requète
	 * @param array $parametres
	 */
	private function sanitizParametres(array &$parametres) {
		$tempArray = [];
		$params = $this->getParametresName();
		foreach ($params as $param) {
			if (isset($parametres[$param])) {
				array_push($tempArray, $parametres[$param]);
			}
		}
		$parametres = $tempArray;
	}

}
