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
            if (!array_key_exists($methode, self::$route_by_methode)) {
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

    public static function mapControleur($controleurNom) {
        if (!class_exists($controleurNom, true)) {
            throw new RouteurException("Le controleur '{$controleurNom}' n'est pas connu !");
        }
        $defControleur = new \ReflectionClass($controleurNom);
        $ctrlNomElem = explode('\\', $controleurNom);
        $controleurNomCourt = array_pop($ctrlNomElem);
        foreach ($defControleur->getMethods(\ReflectionMethod::IS_PUBLIC) as $defMethode) {
            static::mapMethode($controleurNomCourt, $defMethode);
        }
    }

    private static function mapMethode($controleurNom, ReflectionMethod $defMethode) {
        $name = $defMethode->getName();
        $regex = "/^((?:_?(?:get|put|post|patch|delete))+)_(.+)$/";
        $matchs = array();
        if (preg_match($regex, $name, $matchs) > 0) {
            $methodes = explode('_', $matchs[1]);
            $actionNom = $matchs[2];
            static::mapAction($controleurNom, $methodes, $actionNom, $defMethode->getParameters());
        }
    }

    private function mapAction($controleurNom, array $methodes, $actionNom, array $parametres) {
        $routeNom = $controleurNom . '.' . $actionNom;
        $action = $controleurNom . '@' . $actionNom;
        $routeBasePath = $controleurNom . WS . $actionNom;
        $routeParametre = '';
        foreach ($prametres as $parametre) {
            $paramNom = $parametre->getNom();
            $routeParametre .= WS . '{' . $paramNom . '}';
        }
        static::add(implode('|', $methodes), $routeBasePath . $routeParametre, $action, $routeNom);
    }

    public static function getUrl($routeName, array $parametres = []) {
        if (!array_key_exists($routeName, self::$route_by_name)) {
            throw new RouteurException("La route '{$routeName}' n'est pas connue");
        }
        return self::$route_by_name[$routeName]->getUrl($parametres);
    }

    public static function reparti($methode, $uri) {
        if (preg_match('`^' . self::METHODES . '$`', $methode) == 0) {
            throw new RouteurException("La méthode '{$methode}' est inconnue !");
        }
        if (!array_key_exists($methode, self::$route_by_methode)) {
            throw new RouteNotFoundException();
        }
        foreach (self::$route_by_methode[$methode] as $route) {
            if ($route->match($uri)) {
                return $route->call();
            }
        }
        throw new RouteNotFoundException();
    }

}
