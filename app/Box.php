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

/**
 * Description of Box
 *
 * @author fabien.sanchez
 */
class Box {

    private $constructeur = array();
    private $instances = array();
    private $generateur = array();

    public function set($nom, \callable $constructeur) {
        $this->constructeur[$nom] = $constructeur;
    }

    public function setFactory($nom, \callable $generateur) {
        $this->generateur[$nom] = $generateur;
    }

    /**
     *
     * @param string $nom
     * @return mix_object
     * @throws Exception
     */
    public function get($nom) {
        /**
         * @var mix_object
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

    private function isConstructeur($nom) {
        return isset($this->constructeur[$nom]);
    }

    private function getConstructeur($nom) {
        if (!isset($this->instances[$nom])) {
            $this->instances[$nom] = call_user_func($this->constructeur[nom]);
        }
        return $this->instances[$nom];
    }

    private function isGenerateur($nom) {
        return isset($this->generateur[$nom]);
    }

    private function getGenerateur($nom) {
        return call_user_func($this->generateur[$nom]);
    }

    private function isClasse($nom) {
        return class_exists($nom, true);
    }

    private function getAutoClasse($nom) {
        $refClasse = new \ReflectionClass($nom);
        if ($refClasse->isInstantiable()) {

        } else {
            throw new Exception('Impossible de résoudre ' . $nom);
        }
    }

}
