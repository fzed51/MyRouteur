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

namespace Core\Routeur;

use ArrayObject;
use Exception;
use ReflectionFunction;
use ReflectionMethod;

/**
 * Description of Route
 *
 * @author fabien.sanchez
 */
class Route {

    /**
     * Chemin de la route
     * @var string
     */
    private $Path;

    /**
     * Action de la route à exécuter
     * @var callable|string
     */
    private $Action;

    /**
     * Liste des parametres de la route
     * @var array
     */
    private $Parametres;

    /**
     * Liste de patterne de validation des paramètres de la routes
     * @var array
     */
    private $Validations;

    /**
     * Liste des valeur des paramètres
     * @var array
     */
    private $MatchedParams;

    public function __construct($path, $action) {
        $this->Path = WEBROOT . trim($path, WS);
        $this->Action = $action;
        $this->Parametres = $this->getParametresName();
        $this->generateValidation();
    }

    /**
     *
     * @param array $parametres
     * @return string
     */
    public function getUrl(array $parametres = array()) {
        $url = $this->Path;
        if (!empty($parametres)) {
            foreach ($parametres as $parametre => $value) {
                $oldUrl = $url;
                $url = str_replace('{' . $parametre . '}', $value, $url);
                if (strcmp($oldUrl, $url) != 0) {
                    unset($parametres[$parametre]);
                }
            }
        }
        if (!empty($parametres)) {
            $url .= '?';
            $start = true;
            foreach ($parametres as $parametre => $value) {
                if (!$start) {
                    $url.='&';
                }
                $url .= urlencode($parametre) . '=' . urlencode($value);
            }
        }
        return $url;
    }

    private function generateValidation() {
        $this->Validations = array();
        $params = $this->Parametres;
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
// supprime les parentaises pour eviter les captures innattendus
        $validation = \preg_replace('`\((?=[^?][^:])`', '(?:', $validation);
        $this->Validations[$param] = $validation;
        return $this;
    }

    private function getRegEx() {
        $parametres = $this->getParametresName();
        $regex = preg_quote($this->Path, '`');
        foreach ($parametres as $parametre) {
            $regex = str_replace(preg_quote('{' . $parametre . '}', '`'), '(?P<' . $parametre . '>' . $this->Validations[$parametre] . ')', $regex);
        }
        return '`^' . $regex . '$`U';
    }

    public function match($uri) {
        $params = [];
        $regex = $this->getRegEx();
        if (preg_match($regex, str_replace('index.php', '', $uri), $params)) {
            foreach (array_keys($params) as $key) {
                if (in_array($key, $this->Parametres)) {
                    $this->MatchedParams[$key] = $params[$key];
                }
            }
            return true;
        }
        return false;
    }

    public function call() {
        if (is_callable($this->Action)) {
            return $this->executeCallable();
        } elseif (is_string($this->Action) && preg_match("`([a-zA-Z][a-zA-Z0-9_]*)@([a-zA-Z][a-zA-Z0-9_]*)`", $this->Action)) {
            return $this->executeControleurAction();
        } else {
            throw new RouteurException("L'action n'est pas reconnue");
        }
    }

    private function getParametresName() {
        $matchs = array();
        preg_match_all("`\{([a-zA-Z][a-zA-Z0-9-_]*)\}`", $this->Path, $matchs);
        return $matchs[1];
    }

    private function executeControleurAction() {
        $matchs = [];
        preg_match("`([a-zA-Z][a-zA-Z0-9_]*)@([a-zA-Z][a-zA-Z0-9_]*)`", $this->Action, $matchs);
        $ctrlNom = "App\\Controleur\\{$matchs[1]}";
        $actnNom = $matchs[2];
        if (class_exists($ctrlNom, true)) {
            $controleur = new $ctrlNom();
            $listeMethode = get_class_methods($controleur);
            if (array_search($actnNom, $listeMethode) !== false) {
                $refMethode = new ReflectionMethod($controleur, $actnNom);
                if ($refMethode->getNumberOfParameters() > 0) {
                    $refParametres = $refMethode->getParameters();
                    $parametresIndexes = self::indexArrayFor($this->MatchedParams, $refParametres);
                    return $refMethode->invokeArgs($controleur, $parametresIndexes);
                } else {
                    return $refMethode->invoke($controleur);
                }
            } else {
                throw new RouteurException("L'action '{$actnNom}' n'a pas été trouvée !");
            }
        } else {
            throw new RouteurException("Le controleur '{$ctrlNom}' n'a pas été trouvé !");
        }
    }

    private function executeCallable() {
        $refCallable = new ReflectionFunction($this->Action);
        if ($refCallable->getNumberOfParameters() > 0) {
            $refParametres = $refCallable->getParameters();
            $parametresIndexes = self::indexArrayFor($this->MatchedParams, $refParametres);
            return $refCallable->invokeArgs($parametresIndexes);
        } else {
            return $refCallable->invoke();
        }
    }

    static private function indexArrayFor(array $arrayAsso, array $paramModel) {
        $parametresIndexes = [];
        foreach ($paramModel as $refParam) {
            if (isset($arrayAsso[$refParam->getName()])) {
                $parametresIndexes[$refParam->getPosition()] = $arrayAsso[$refParam->getName()];
            } else {
                if (!$refParam->isOptional()) {
                    $parametresIndexes[$refParam->getPosition()] = $refParam->getDefaultValue();
                } else {
                    $name = ($this->_name <> '') ? $this->_name : $this->Path;
                    throw new Exception("Imposible d'executer la route {$name}, il manque le paramètre {$refParam->getName()}.");
                }
            }
        }
        return $parametresIndexes;
    }

    public function getInfos() {
        return [
            'chemin' => $this->Path,
            'parametres' => implode(', ', $this->Parametres),
            'action' => (is_callable($this->Action) ? 'Callable' : $this->Action),
        ];
    }

}