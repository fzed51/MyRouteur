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
 * Description of Routeur
 *
 * @author fabien.sanchez
 */
class Routeur {

	static private $_routes = [];

	static function add(Route $route) {
		array_push(static::$_routes, $route);
	}

	static function getRoute($routeName) {
		foreach (static::$_routes as $route) {
			if ($route->getName() == $routeName) {
				return $route;
			}
		}
		throw new Exception("la route : " . $routeName . " n'existe pas !");
	}

	static function getUrl($routeName, ArrayObject $parametres = NULL) {
		$route = self::getRoute($routeName);
		$fullPath = 'localhost' . '/' . $route->getPath();
		if (!is_null($parametres)) {
			foreach ($parametres as $parametre => $value) {
				$fullPath = str_replace($fullPath, '{' . $parametre . '}', $value);
			}
		}
	}

	static private function match(Route $route, Requete $requete) {
		if (!in_array($requete->client['METHODE'], $route->getMethodes())) {
			return false;
		}
		var_dump($route->getRegEx());
		if (preg_match($route->getRegEx(), $requete->client['URI'], $params)) {

		}
		return false;
	}

	static function reparti(Requete $requete) {
		var_dump(static::$_routes);
		var_dump($requete);
		foreach (static::$_routes as $route) {
			if (self::match($route, $requete)) {
				return $route;
			}
		}
		return NULL;
	}

}
