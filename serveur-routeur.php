<?php

/*
 * The MIT License
 *
 * Copyright 2015 Sandrine.
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

if (php_sapi_name() == 'cli-server') {
    $fullNamePublic = $_SERVER["DOCUMENT_ROOT"] . "/public" . $_SERVER["REQUEST_URI"];
    if (is_file($fullNamePublic)) {
        $path = pathinfo($fullNamePublic);
        switch ($path["extension"]) {
            case 'ico':
                header("Content-Type: image/x-icon");
                break;
            case 'css':
                header("Content-Type: text/css");
                break;
            case 'js':
                header("Content-Type: application/javascript");
                break;
            case 'json':
                header("Content-Type: application/json");
                break;
            case 'xml':
                header("Content-Type: application/xml");
                break;
            case 'jpg':
                header("Content-Type: image/jpeg");
                break;
            case 'png':
                header("Content-Type: image/png");
                break;
            case 'gif':
                header("Content-Type: image/gif");
                break;
            case 'svg':
                header("Content-Type: image/svg+xml");
                break;
            default:
                return FALSE;
        }
        readfile($fullNamePublic);
    } else {
        require "./public/index.php";
    }
}