<?php

/*
 * The MIT License
 *
 * Copyright 2015 fabien.sanchez.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Routeur;

/**
 * Description of Routeur
 *
 * @author fabien.sanchez
 */
class Routeur {

    const METHODES = "GET|POST|PATCH|PUT|DELETE";

    private static $routes_by_methode = array();
    private static $routes_by_name = array();
    private static $routes = array();

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
        array_push(self::$routes, $route);
        // enregistrement de la route dans le tableau des méthoes
        $lst_methodes = explode('|', $methodes);
        foreach ($lst_methodes as $methode) {
            if (!array_key_exists($methode, self::$routes_by_methode)) {
                self::$routes_by_methode[$methode] = array();
            }
            array_push(self::$routes_by_methode[$methode], $route);
        }
        // enregistrement de la route dans le tableau de nom
        if (!is_null($name)) {
            self::$routes_by_name[$name] = $route;
        } else {
            if (is_string($action)) {
                self::$routes_by_name[$action] = $route;
            }
        }
        return $route;
    }

    public static function mapControleur($path, $controleurNom) {
        if (!class_exists($controleurNom, true)) {
            throw new RouteurException("Le controleur '{$controleurNom}' n'est pas connu !");
        }
        $defControleur = new \ReflectionClass($controleurNom);
        $ctrlNomElem = explode('\\', $controleurNom);
        $controleurNomCourt = array_pop($ctrlNomElem);
        foreach ($defControleur->getMethods(\ReflectionMethod::IS_PUBLIC) as $defMethode) {
            static::mapMethode($path, $controleurNomCourt, $defMethode);
        }
    }

    private static function mapMethode($path, $controleurNom, \ReflectionMethod $defMethode) {
        $name = $defMethode->getName();
        $regex = "/^((?:_?(?:get|put|post|patch|delete))+)_(.+)$/";
        $matchs = array();
        if (preg_match($regex, $name, $matchs) > 0) {
            $methodes = explode('_', $matchs[1]);
            $actionNom = $matchs[2];
            static::mapAction($path, $controleurNom, $name, $methodes, $actionNom, $defMethode->getParameters());
        } elseif ($name == 'index') {
            static::mapAction($path, $controleurNom, 'index', ['GET'], 'index', $defMethode->getParameters());
        }
    }

    private static function mapAction($path, $controleurNom, $methodeNom, array $methodes, $actionNom, array $parametres) {
        $routeNom = $controleurNom . '.' . $actionNom;
        $action = $controleurNom . '@' . $methodeNom;
        if ($actionNom != 'index') {
            $routeBasePath = $path . WS . $actionNom;
        } else {
            $routeBasePath = $path;
        }
        $routeParametre = '';
        foreach ($parametres as $parametre) {
            $paramNom = $parametre->getName();
            $routeParametre .= WS . '{' . $paramNom . '}';
        }
        static::add(strtoupper(implode('|', $methodes)), $routeBasePath . $routeParametre, $action, $routeNom);
    }

    public static function getUrl($routeName, array $parametres = []) {
        if (!array_key_exists($routeName, self::$routes_by_name)) {
            throw new RouteurException("La route '{$routeName}' n'est pas connue");
        }
        return self::$routes_by_name[$routeName]->getUrl($parametres);
    }

    public static function reparti($methode, $uri) {
        if (preg_match('`^' . self::METHODES . '$`', $methode) == 0) {
            throw new RouteurException("La méthode '{$methode}' est inconnue !");
        }
        if (!array_key_exists($methode, self::$routes_by_methode)) {
            throw new RouteNotFoundException();
        }
        foreach (self::$routes_by_methode[$methode] as $route) {
            if ($route->match($uri)) {
                return $route->call();
            }
        }
        throw new RouteNotFoundException();
    }

    public static function listRoutes() {
        $infosRoutes = array();
        foreach (self::$routes_by_methode as $methode => $routes) {
            foreach ($routes as $route) {
                $infos = $route->getInfos();
                $infos['methodes'] = $methode;
                $infos['nom'] = '';
                $chemin = $infos['chemin'];
                if (!isset($infosRoutes[$chemin])) {
                    $infosRoutes[$chemin] = $infos;
                } else {
                    $infosRoutes[$chemin]['methodes'] .= ', ' . $methode;
                }
            }
        }
        foreach (self::$routes_by_name as $nom => $route) {
            $infos = $route->getInfos();
            $chemin = $infos['chemin'];
            $infosRoutes[$chemin]['nom'] = $nom;
        }
        return array_values($infosRoutes);
    }

    public static function debug($param) {
        var_dump(static::$routes);
    }

}
