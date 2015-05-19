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

/**
 * Description of Requete
 *
 * @author fabien.sanchez
 */
class Requete {

    static private $_instance = NULL;
    public $serveur = [];
    public $client = [];
    public $input = [];

    public function __construct() {
        $this->initServeurData();
        $this->initClientData();
        $this->initInputData();
        self::$_instance = $this;
    }

    private function initServeurData() {
        // TODO : Init la partie serveur de la requete
    }

    private function initClientData() {
        $this->client['IP'] = $_SERVER['REMOTE_ADDR'];
        $this->client['PORT'] = $_SERVER['REMOTE_PORT'];
        $this->client['METHODE'] = $_SERVER['REQUEST_METHOD'];
        if (isset($_POST['_METHODE'])) {
            if (in_array($_POST['_METHODE'], ['GET', 'POST', 'PUT', 'DELETE'])) {
                $this->client['METHODE'] = $_POST['_METHODE'];
            }
        }
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $this->client['URI'] = array_shift($uri);
        unset($uri);
        $this->client['ACCEPT'] = $_SERVER['HTTP_ACCEPT'];
        $this->client['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        $this->client['ACCEPT_ENCODING'] = $_SERVER['HTTP_ACCEPT_ENCODING'];
        $this->client['ACCEPT_LANGUAGE'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        //$this->client[''] = $_SERVER[''];
    }

    private function initInputData() {
        if (isset($_GET)) {
            $this->input = array_merge($this->input, $_GET);
        }
        if (isset($_COOKIE)) {
            $this->input = array_merge($this->input, $_COOKIE);
        }
        if (isset($_POST)) {
            $this->input = array_merge($this->input, $_POST);
        }
    }

    static public function serveur($param) {

        return self::returnVal('serveur', $param);
    }

    static public function client($param) {
        return self::returnVal('client', $param);
    }

    static public function input($param) {
        return self::returnVal('input', $param);
    }

    static private function getInstance() {
        if (is_null(self::$_instance)) {
            throw new Exception("La Requete n'est pas initialisée et ne peut pas retourner de données !");
        }
        return self::$_instance;
    }

    static private function returnVal($arrayName, $param) {
        $requete = self::getInstance();
        if (isset($requete->$arrayName[$param])) {
            return $requete->$arrayName[$param];
        }
    }

}
