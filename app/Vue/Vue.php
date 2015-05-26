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
 * Description of Vue
 *
 * @author fabien.sanchez
 */
class Vue {

    private static $_style;
    private static $_style_cache = [];
    private static $_nav;
    private static $_content;
    private static $_content_cache = [];
    private static $_script;
    private static $_script_cache = [];

    public static function addStyle($style) {
        $key = md5($style);
        if (!isset(self::$_style_cache[$key])) {
            self::$_style_cache[$key] = true;
            self::$_style .= '<style>' . $style . '</style>';
        }
    }

    public static function addFileStyle($file_style) {
        $key = md5($file_style);
        if (!isset(self::$_style_cache[$key])) {
            self::$_style_cache[$key] = true;
            $file_style = concatPath('./style', $file_style);
            if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $file_style)) {
                self::$_style .= '<link type="text/css" href="' . $file_style . '" rel="stylesheet" />';
            }
        }
    }

    public static function style() {
        return self::$_style;
    }

    public static function setNav($nav) {
        self::$_nav = $nav;
    }

    public static function nav() {
        return self::$_nav;
    }

    public static function prependContent($content) {
        $key = md5($content);
        if (!isset(self::$_content_cache[$key])) {
            self::$_content_cache[$key] = true;
            self::$_content = $content . self::$_content;
        }
    }

    public static function setContent($content) {
        $key = md5($content);
        if (!isset(self::$_content_cache[$key])) {
            self::$_content_cache = [];
            self::$_content_cache[$key] = true;
            self::$_content = $content;
        }
    }

    public static function appendContent($content) {
        $key = md5($content);
        if (!isset(self::$_content_cache[$key])) {
            self::$_content_cache[$key] = true;
            self::$_content .= $content;
        }
    }

    public static function content() {
        return self::$_content;
    }

    public static function addScript($script) {
        $key = md5($script);
        if (!isset(self::$_script_cache[$key])) {
            self::$_script_cache[$key] = true;
            self::$_script .= '<script type="text/javascript" >' . $script . '</style>';
        }
    }

    public static function addFileScript($file_script) {
        $key = md5($file_script);
        if (!isset(self::$_script_cache[$key])) {
            self::$_script_cache[$key] = true;
            $file_script = concatPath('./style', $file_script);
            if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $file_script)) {
                self::$_script .= '<script type="text/javascript" src="' . $file_script . '"></style>';
            }
        }
    }

    public static function script() {
        return self::$_script;
    }

}
