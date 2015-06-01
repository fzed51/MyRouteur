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

namespace App\Vue;

/**
 *
 * @author fabien.sanchez
 */
trait TraitVueStyle {

    private $Styles = array();

    public function addStyle($style) {
        $key = md5($style);
        $this->Styles[$key] = '<style>' . $style . '</style>';
        return $this;
    }

    public function addFileStyle($fileName) {
        $key = md5($fileName);
        $path_file = $fileName . '.css';
        $web_file = concatPath(WEBROOT . '/style', $path_file, WS);
        $sys_file = concatPath(ROOT . '/style', $path_file, DS);
        if (file_exists($sys_file)) {
            $this->Styles[$key] = '<link type="text/css" href="' . $web_file . '" rel="stylesheet" />';
        }
        return $this;
    }

    public function getStyle() {
        return $this->Styles;
    }

    public function renderStyle() {
        return implode(PHP_EOL, $this->Styles);
    }

}
