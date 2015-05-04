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
	private $_path;
	private $_methode;
	private $_name;

	public function __construct($methode, $path, $action) {
		$this->_methode = $methode;
		$this->_path = $path;
		$this->_action = $action;
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

}
