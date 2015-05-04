<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 * Description of Route
 *
 * @author fabien.sanchez
 */
class Route {

	private $_action;
	private $_type_action;
	private $_path;
	private $_methodes;
	private $_name;

	public function __construct($methode, $path, $action) {
		$this->setMethodes($methode);
		$this->_path = $path;
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
			$this->_methodes = explode(['|', ' ', ';'], $methode);
		} else {
			throw new \Exception("La méthode n'est pas reconnue");
		}
		$this->_methodes = array_intersect($this->_methodes, ['GET', 'POST', 'PUT', 'DELETE']);
	}

	private function setAction($action) {
		if (is_callable($action)) {
			$this->_type_action = "CALLABLE";
			$this->_action = $action;
		} elseif (is_string($action)) {
			$this->_type_action = "CONTROL_ACTION";
			$this->_action = $action;
		} else {
			throw new \Exception("L'action n'est pas reconnue");
		}
	}

	function getMethodes() {
		return $this->_methodes;
	}

	function executeAction(\ArrayObject $Parametres) {
		switch ($this->_type_action) {
			case 'CALLABLE':

				break;
			case 'CONTROL_ACTION':

				break;
		}
	}

}
