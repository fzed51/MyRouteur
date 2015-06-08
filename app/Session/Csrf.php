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

namespace App\Session;

/**
 * Description of Csrf
 *
 * @author fabien.sanchez
 */
class Csrf {

    const IDSESSION = "__SECURITE__CSRF__";

    private $session;
    private $jeton;

    public function __construct(\Session $session) {
        $this->session = $session;
        $this->generate();
    }

    public function generate($force = false) {
        if (!$force && isset($this->session[self::IDSESSION])) {
            $this->jeton = $this->session[self::IDSESSION];
        } else {
            $this->jeton = hash('sha256', uniqid() . '-' . self::class . '-' . self::IDSESSION . '-' . (string) time());
            $this->session[self::IDSESSION] = $this->jeton;
        }
    }

    public function getAttrb() {
        return 'csrf=' . $this->jeton;
    }

    public function getInput() {
        return "<input type=\"hidden\" name=\"csrf\" value\"{$this->jeton}\" />";
    }

    function check($redirect = true, $csrfTest = null) {
        if (is_null($csrfTest) and ( isset($_POST['csrf']) xor isset($_GET['csrf']))) {
            $csrfTest = isset($_POST['csrf']) ? $_POST['csrf'] : $_GET['csrf'];
        }
        if ($redirect) {
            if ($this->jeton !== $csrfTest) {
                throw new InvalidCsrfTockenException();
            }
        }
    }

}
