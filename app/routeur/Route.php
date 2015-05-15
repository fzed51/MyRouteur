<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Routeur;

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

<<<<<<< HEAD
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

    public function getUrl($parametres = array()) {
        $url = $this->Path;
        if (!is_null($parametres)) {
            foreach ($parametres as $parametre => $value) {
                $url = str_replace('{' . $parametre . '}', $value, $url);
            }
        }
        return $url;
    }

    private function generateValidation() {
        $this->Validations = array();
        $params = $this->Parametres;
=======
    private $_action;
    private $_path;
    private $_validation;

    public function __construct($path, $action) {
        $this->setMethodes($methode);
        $this->_path = WEBROOT . $path;
        $this->generateValidation();
        $this->setAction($action);
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
        return '`' . $regex . '$`U';
    }

    private function getParametresName() {
        $matchs = array();
        preg_match_all("`\{([a-zA-Z][a-zA-Z0-9-_]*)\}`", $this->_path, $matchs);
        return $matchs[1];
    }

    function executeAction(array $parametres = []) {
        $this->sanitizParametres($parametres);
        switch ($this->_type_action) {
            case 'CALLABLE':
                return $this->executeCallable($parametres);
            case 'CONTROL_ACTION':
                return $this->executeControleurAction($parametres);
        }
    }

    private function generateValidation() {
        $this->_validation = array();
        $params = $this->getParametresName();
>>>>>>> origin/dev
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
<<<<<<< HEAD
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
=======
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
                $tempArray[$param] = $parametres[$param];
            }
        }
        $parametres = $tempArray;
    }

    private function executeControleurAction(array $parametres = []) {
        $matchs = [];
        preg_match("`([a-zA-Z][a-zA-Z0-9_]*)@([a-zA-Z][a-zA-Z0-9_]*)`", $this->_action, $matchs);
        $ctrlNom = "App\Controleur\controleur{$matchs[1]}";
>>>>>>> origin/dev
        $actnNom = $matchs[2];
        if (class_exists($ctrlNom, true)) {
            //echo "Exécute $ctrlNom->$actnNom()";
            $controleur = new $ctrlNom();
            $listeMethode = get_class_methods($controleur);
            if (array_search($actnNom, $listeMethode) !== false) {
                $refMethode = new ReflectionMethod($controleur, $actnNom);
                if ($refMethode->getNumberOfParameters() > 0) {
                    $refParametres = $refMethode->getParameters();
<<<<<<< HEAD
                    $parametresIndexes = self::indexArrayFor($this->MatchedParams, $refParametres);
=======
                    $parametresIndexes = self::indexArrayFor($parametres, $refParametres);
>>>>>>> origin/dev
                    return $refMethode->invokeArgs($controleur, $parametresIndexes);
                } else {
                    return $refMethode->invoke($controleur);
                }
<<<<<<< HEAD
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
=======
            }
        }
    }

    private function executeCallable(array $parametres = []) {
        $refCallable = new ReflectionFunction($this->_action);
        if ($refCallable->getNumberOfParameters() > 0) {
            $refParametres = $refCallable->getParameters();
            $parametresIndexes = self::indexArrayFor($parametres, $refParametres);
>>>>>>> origin/dev
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
<<<<<<< HEAD
                    $name = ($this->_name <> '') ? $this->_name : $this->Path;
=======
                    $name = ($this->_name <> '') ? $this->_name : $this->_path;
>>>>>>> origin/dev
                    throw new Exception("Imposible d'executer la route {$name}, il manque le paramètre {$refParam->getName()}.");
                }
            }
        }
        return $parametresIndexes;
    }

}
