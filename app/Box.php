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

namespace App;

use Exception;
use ReflectionClass;
use ReflectionMethod;

/**
 * Description of Box
 *
 * @author fabien.sanchez
 */
class Box {

    /**
     * tableau de resolveur
     * @var array
     */
    private $constructeur = array();

    /**
     * instance retourné par les resolveurs
     * @var array
     */
    private $instances = array();

    /**
     * tableau de resolveur de generateur
     * @var array
     */
    private $generateur = array();

    /**
     * Ajoute un resolveur
     * @param string $nom
     * @param Callable $constructeur
     */
    public function set($nom, Callable $constructeur) {
        $this->constructeur[$nom] = $constructeur;
    }

    /**
     * Ajoute un resolveur de generateur
     * @param string $nom
     * @param Callable $generateur
     */
    public function setFactory($nom, Callable $generateur) {
        $this->generateur[$nom] = $generateur;
    }

    /**
     *
     * @param string $nom
     * @return mixed
     * @throws Exception
     */
    public function get($nom) {
        /**
         * @var mixed
         */
        $instance = null;
        if ($this->isConstructeur($nom)) {
            $instance = $this->getConstructeur($nom);
        } else if ($this->isGenerateur($nom)) {
            $instance = $this->getGenerateur($nom);
        } else if ($this->isClasse($nom)) {
            $instance = $this->getAutoClasse($nom);
        } else {
            throw new Exception('Impossible de résoudre ' . $nom);
        }
        return $instance;
    }

    /**
     *
     * @param string $nom
     * @return bool
     */
    private function isConstructeur($nom) {
        return isset($this->constructeur[$nom]);
    }

    /**
     *
     * @param string $nom
     * @return mixed
     */
    private function getConstructeur($nom) {
        if (!isset($this->instances[$nom])) {
            $this->instances[$nom] = call_user_func($this->constructeur[nom]);
        }
        return $this->instances[$nom];
    }

    /**
     *
     * @param string $nom
     * @return bool
     */
    private function isGenerateur($nom) {
        return isset($this->generateur[$nom]);
    }

    /**
     *
     * @param string $nom
     * @return mixed
     */
    private function getGenerateur($nom) {
        return call_user_func($this->generateur[$nom]);
    }

    /**
     *
     * @param string $nom
     * @return bool
     */
    private function isClasse($nom) {
        return class_exists($nom, true);
    }

    /**
     *
     * @param string $nom
     * @return mixed
     * @throws Exception
     */
    private function getAutoClasse($nom) {
        $refClasse = new ReflectionClass($nom);
        if ($refClasse->isInstantiable()) {
            $refConstructor = $refClasse->getConstructor();
            if ((!is_null($refConstructor)) && ($refConstructor->getNumberOfRequiredParameters() > 0)) {
                $params = $this->getAutoParams($refConstructor);
                return $refClasse->newInstanceArgs($params);
            } else {
                return $refClasse->newInstance();
            }
        } else {
            throw new Exception('Impossible de résoudre ' . $nom . ', ce n\'est pas une classe instanciable.');
        }
    }

    /**
     *
     * @param ReflectionMethod $refMethod
     * @return array
     * @throws Exception
     */
    private function getAutoParams(ReflectionMethod $refMethod) {
        $params = array();
        $refParams = $refMethod->getParameters();
        foreach ($refParams as $refParam) {
            $refClassParam = $refParam->getClass();
            if (!is_null($refClassParam)) {
                $classeName = $refClassParam->getName();
                try {
                    $params[] = $this->get($classeName);
                } catch (Exception $exc) {
                    //echo $exc->getTraceAsString();
                    throw new Exception('Impossible de résoudre les paramètre de ' . $refMethod->getDeclaringClass()->getName() . '->' . $refMethod->getName(), 0, $exc);
                }
            } else if ($refParam->isDefaultValueAvailable()) {
                $params[] = $refParam->getDefaultValue();
            } else {
                throw new Exception('Impossible de résoudre les paramètre de ' . $refMethod->getDeclaringClass()->getName() . '->' . $refMethod->getName());
            }
        }
        return $params;
    }

}
