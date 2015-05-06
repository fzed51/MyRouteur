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

	static function getUrl($routeName, array $parametres = NULL) {
		$route = self::getRoute($routeName);
		$fullPath = $route->getPath();
		if (!is_null($parametres)) {
			foreach ($parametres as $parametre => $value) {
				$fullPath = str_replace('{' . $parametre . '}', $value, $fullPath);
			}
		}
		return $fullPath;
	}

	static private function match(Route $route, Requete $requete) {
		if (!in_array($requete->client['METHODE'], $route->getMethodes())) {
			return false;
		}
		$params = [];
		if (preg_match($route->getRegEx(), str_replace('index.php', '', $requete->client['URI']), $params)) {
			return $route->executeAction($params);
		}
		return false;
	}

	static function reparti(Requete $requete) {
		foreach (static::$_routes as $route) {
			// echo "test " . $route->getPath() . " et " . $requete->client['URI'] . '<br>' . PHP_EOL;
			$reponse = self::match($route, $requete);
			if ($reponse !== false) {
				return $reponse;
			}
		}
		return NULL;
	}

}
