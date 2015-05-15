<?php

namespace App\Routeur;

/**
 * Description of Routeur
 *
 * @author fabien.sanchez
 */
class Routeur {

    const METHODES = "GET|POST|PATCH|PUT|DELETE";

    private static $route_by_methode = array();
    private static $route_by_name = array();

    public static function get($path, $action, $name = null) {
        return self::add('GET', $path, $action, $name);
    }

    public static function post($path, $action, $name = null) {
        return self::add('POST', $path, $action, $name);
    }

    public static function patch($path, $action, $name = null) {
        return self::add('PATCH', $path, $action, $name);
    }

    public static function put($path, $action, $name = null) {
        return self::add('PUT', $path, $action, $name);
    }

    public static function delete($path, $action, $name = null) {
        return self::add('DELETE', $path, $action, $name);
    }

    public static function any($path, $action, $name = null) {
        return self::add(self::METHODES, $path, $action, $name);
    }

    public static function add($methodes, $path, $action, $name = null) {
        // contrôl des méthodes
        if (preg_match("`^GET|POST|PATCH|PUT|DELETE(\|GET|POST|PATCH|PUT|DELETE)*$`", $methodes) == 0) {
            throw new \App\Routeur\RouteurException("La(es) méthode(s) '{$methodes}' n'est(ne sont) pas valide");
        }
        // création de la route
        $route = new Route($path, $action);
        // enregistrement de la route dans le tableau des méthoes
        $lst_methodes = explode('|', $methodes);
        foreach ($lst_methodes as $methode) {
            if (array_key_exists($methode, self::$route_by_methode)) {
                self::$route_by_methode[$methode] = array();
            }
            array_push(self::$route_by_methode[$methode], $route);
        }
        // enregistrement de la route dans le tableau de nom
        if (!is_null($name)) {
            self::$route_by_name[$name] = $route;
        } else {
            if (is_string($action)) {
                self::$route_by_name[$action] = $route;
            }
        }
        return $route;
    }

    public static function getUrl($routeName, array $parametres = NULL) {
        if (array_key_exists($routeName, self::$route_by_name)) {
            throw new RouteurException("La route '{$routeName}' n'est pas connue");
        }
        $route = self::$route_by_name[$routeName];
        $url = $route->getPath();
        if (!is_null($parametres)) {
            foreach ($parametres as $parametre => $value) {
                $url = str_replace('{' . $parametre . '}', $value, $url);
            }
        }
        return urlencode($url);
    }

    public static function reparti($methode, $uri) {
        if (preg_match('`^' . self::METHODES . '$`', $methode) == 0) {
            throw new RouteurException("La méthode '{$methode}' est inconnue !");
        }
        if (array_key_exists($methode, self::$route_by_methode)) {
            throw new RouteNotFoundException();
        }
        foreach (self::$route_by_methode[$methode] as $route) {
            if ($route->match($uri)) {
                $route->call();
            }
        }
        throw new RouteNotFoundException();
    }

}
