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
 * Description of Session
 *
 * @author fabien.sanchez
 */
class Session extends \App\Collection {

    public function __construct() {
        $this->start();
        parent::__construct($_SESSION);
    }

    public function __destruct() {
        $_SESSION = $this->items;
        session_commit();
    }

    private function start() {
        if ($this->status() !== PHP_SESSION_ACTIVE) {
            $file = '';
            $line = '';
            if (headers_sent($file, $line)) {
                throw new \SessionStartException("Impossible de démarrer une ssesion.\nHeader envoyer dans <$file>, ligne <$line>");
            }
            session_start();
        }
    }

    public function status() {
        return session_status();
    }

}
