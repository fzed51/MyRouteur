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
class Vue implements VueInterface {

    /**
     * Dossier où se trouve les vue
     * @static
     * @var string
     */
    static $DossierVue = __DIR__ . '\..\..';

    /**
     * Dossier où se trouve les layout
     * @static
     * @var string
     */
    static $DossierLayout = __DIR__ . '\..\..';

    /* Liste des données fixe */

    /**
     * titre de la vue
     * @var string
     */
    protected $titre;

    /**
     * fichier contenant le modele de la vue
     * @var string
     */
    protected $layout;

    /**
     * fichier contenant la vue
     * @var string
     */
    protected $vue;

    /**
     * tableau contenant les style de la vue
     * @var array
     */
    protected $style = [];

    /**
     *
     * @var string
     */
    protected $content;

    /**
     *
     * @var array
     */
    protected $script = [];

    /*  Donnée dynamiques */

    /**
     *
     * @var array
     */
    protected $data = [];

    /**
     *
     * @param string $vue
     * @param string $layout
     */
    public function __construct($vue = null, $layout = null) {
        if (!is_null($vue)) {
            $this->setVue($vue);
            if (!is_null($layout) && is_string($layout)) {
                $this->setLayout($layout);
            }
        }
    }

    /**
     *
     * @param string $vue
     * @param array $data
     * @param string $layout
     * @return \App\Vue\Vue
     */
    public static function get($vue, array $data = array(), $layout = null) {
        return new self($vue, $data, $layout);
    }

    /**
     * Modifie ou retourne le titre de la vue
     * @param string $titre titre donné à la vue
     * @return \App\Vue\Vue|string
     */
    public function titre($titre = null) {
        if (is_null($titre)) {
            return $titre;
        }
        $this->titre = $titre;
        return $this;
    }

    /**
     *
     * @param string $slug
     * @return \App\Vue\Vue
     * @throws VueException
     */
    public function setLayout($slug) {
        $fileName = self::$DossierLayout . "\\" . str_replace('.', "\\", $slug) . '.php';
        if (!file_exists($fileName)) {
            throw new VueException("Le layout '$slug' n'a pas été trouvé");
        }
        return $this;
    }

    /**
     *
     * @param string $slug
     * @return \App\Vue\Vue
     * @throws VueException
     */
    public function setVue($slug) {
        $fileName = self::$DossierVue . "\\" . str_replace('.', "\\", $slug) . '.php';
        if (!file_exists($fileName)) {
            throw new VueException("La vue '$slug' n'a pas été trouvée");
        }
        return $this;
    }

    public function addStyle($style) {
        $key = md5($style);
        $this->style[$key] = '<style>' . $style . '</style>';
        return $this;
    }

    public function addFileStyle($file_style) {
        $key = md5($file_style);
        $file_style = concatPath('./style', $file_style);
        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $file_style)) {
            $this->style[$key] = '<link type="text/css" href="' . $file_style . '" rel="stylesheet" />';
        }
        return $this;
    }

    public function style() {
        return $this->style;
    }

    public function prependContent($content) {
        $this->content = $content . $this->content;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function appendContent($content) {
        $this->content .= $content;
        return $this;
    }

    public function content() {
        return $this->content;
    }

    public function addScript($script) {
        $key = md5($script);
        $this->script[$key] = '<script type="text/javascript" >' . $script . '</style>';
        return $this;
    }

    public function addFileScript($file_script) {
        $key = md5($file_script);
        $file_script = concatPath('./style', $file_script);
        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $file_script)) {
            $this->script[$key] = '<script type="text/javascript" src="' . $file_script . '"></style>';
        }
        return $this;
    }

    public function script() {
        return $this->script;
    }

    public function addData($data, $value) {
        $this->data[$data] = $value;
        return $this;
    }

    public function addDatas(array $datas) {
        foreach ($datas as $data => $value) {
            $this->addData($data, $value);
        }
        return $this;
    }

    public function data() {
        return $this->data;
    }

    private function compileDataArray(array $data) {
        return implode("\n", $data);
    }

    final public function __toString() {
        return $this->render();
    }

    private static function renderString($__string, array $__data) {
        extract($__data);
        unset($__data);
        $contents = eval($__string);
        return $contents;
    }

    private static function renderFile($__file, array $__data) {
        extract($__data);
        unset($__data);
        ob_start();
        include $__file;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    public function render($data = null) {
        if (!is_null($data)) {
            $compiledData = array_merge($this->data, $data);
        } else {
            $compiledData = $this->data;
        }
        $this->extractMeta($compiledData);
        if (count($this->script) > 0 || !isset($data['script'])) {
            $compiledData['script'] = $this->compileDataArray($this->script);
        }
        if (count($this->style) > 0 || !isset($data['style'])) {
            $compiledData['style'] = $this->compileDataArray($this->style);
        }
        if (!empty($this->titre) || !isset($data['titre'])) {
            $compiledData['titre'] = $this->titre;
        }
        if ($this->vue == '') {
            if (!empty($this->content)) {
                $content = $this->content;
            } else {
                $content = $compiledData['content'];
                unset($compiledData['content']);
            }
            return renderString($content, $compiledData);
        } else {
            $compiledData['content'] = self::renderFile($this->vue, $compiledData);
            if ($this->layout != '') {
                $compiledData['content'] = self::renderFile($this->layout, $compiledData);
            }
            return $compiledData['content'];
        }
    }

    private function extractMeta(array $compiledData) {
        foreach ($compiledData as $data) {
            if (is_object($data) && is_a($data, 'Vue')) {
                $this->extractScript($data);
                $this->extractStyle($data);
            }
        }
    }

    public function extractScript(Vue $data) {
        $this->script = array_merge($this->script, $data->script());
    }

    public function extractStyle(Vue $data) {
        $this->style = array_merge($this->style, $data->style());
    }

}
