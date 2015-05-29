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
     * Nom du layout par defaut
     * @static
     * @var string
     */
    static $DefautLayout = "";

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
    protected $Titre;

    /**
     * fichier contenant le modele de la vue
     * @var string
     */
    protected $Layout;

    /**
     * fichier contenant la vue
     * @var string
     */
    protected $Vue;

    /**
     * tableau contenant les meta de la vue
     * @var array
     */
    protected $Meta = [];

    /**
     * tableau contenant les style de la vue
     * @var array
     */
    protected $Style = [];

    /**
     *
     * @var string
     */
    protected $Content;

    /**
     *
     * @var array
     */
    protected $Script = [];

    /*  Donnée dynamiques */

    /**
     *
     * @var array
     */
    protected $Data = [];

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
            } else {
                $this->setLayout(self::$DefautLayout);
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
        $this->Titre = $titre;
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
        $this->Layout = $fileName;
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
        if (empty($this->Titre)) {
            $this->titre($slug);
        }
        $this->Vue = $fileName;
        return $this;
    }

    public function addStyle($style) {
        $key = md5($style);
        $this->Style[$key] = '<style>' . $style . '</style>';
        return $this;
    }

    public function addFileStyle($file_style) {
        $key = md5($file_style);
        $web_file_style = \concatPath('/style', $file_style . '.css');
        $sys_file_style = \concatPath(self::$DossierVue . DIRECTORY_SEPARATOR . 'style', $file_style . '.css');
        if (file_exists($sys_file_style)) {
            $this->Style[$key] = '<link type="text/css" href="' . $file_style . '" rel="stylesheet" />';
        }
        return $this;
    }

    public function style() {
        return $this->Style;
    }

    public function addMeta($name, $value) {
        $key = md5('name' . $name);
        $this->Meta[$key] = "<meta name=\"{$name}\" content=\"{$value}\" />";
        return $this;
    }

    public function addMetaHttp($name, $value) {
        $key = md5('http-equiv' . $name);
        $this->Meta[$key] = "<meta http-equiv=\"{$name}\" content=\"{$value}\" />";
        return $this;
    }

    public function meta() {
        return $this->Meta;
    }

    public function prependContent($content) {
        if (!empty($this->Vue)) {
            throw new VueException("Impossible de modifier le contenu de la vue '{$this->Vue}'");
        }
        $this->Content = $content . $this->Content;
        return $this;
    }

    public function setContent($content) {
        if (!empty($this->Vue)) {
            throw new VueException("Impossible de modifier le contenu de la vue '{$this->Vue}'");
        }
        $this->Content = $content;
        return $this;
    }

    public function appendContent($content) {
        if (!empty($this->Vue)) {
            throw new VueException("Impossible de modifier le contenu de la vue '{$this->Vue}'");
        }
        $this->Content .= $content;
        return $this;
    }

    public function content() {
        return $this->Content;
    }

    public function addScript($script) {
        $key = md5($script);
        $this->Script[$key] = '<script type="text/javascript" >' . $script . '</style>';
        return $this;
    }

    public function addFileScript($file_script) {
        $key = md5($file_script);
        $file_script = concatPath('./script', $file_script . '.js');
        if (file_exists(self::$DossierVue . DIRECTORY_SEPARATOR . $file_script)) {
            $this->Script[$key] = '<script type="text/javascript" src="' . $file_script . '"></style>';
        }
        return $this;
    }

    public function script() {
        return $this->Script;
    }

    public function addData($data, $value) {
        $this->Data[$data] = $value;
        return $this;
    }

    public function addDatas(array $datas) {
        foreach ($datas as $data => $value) {
            $this->addData($data, $value);
        }
        return $this;
    }

    public function data() {
        return $this->Data;
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
            $compiledData = array_merge($this->Data, $data);
        } else {
            $compiledData = $this->Data;
        }
        $this->extractData($compiledData);
        if (count($this->Meta) > 0 || !isset($data['meta'])) {
            $compiledData['meta'] = $this->compileDataArray($this->Meta);
        }
        if (count($this->Script) > 0 || !isset($data['script'])) {
            $compiledData['script'] = $this->compileDataArray($this->Script);
        }
        if (count($this->Style) > 0 || !isset($data['style'])) {
            $compiledData['style'] = $this->compileDataArray($this->Style);
        }
        if (!empty($this->Titre) || !isset($data['titre'])) {
            $compiledData['titre'] = $this->Titre;
        }
        if (empty($this->Vue)) {
            if (!empty($this->Content)) {
                $content = $this->Content;
            } else {
                $content = $compiledData['content'];
                unset($compiledData['content']);
            }
            $compiledData['content'] = renderString($content, $compiledData);
        } else {
            $compiledData['content'] = self::renderFile($this->Vue, $compiledData);
            if ($this->Layout != '') {
                $compiledData['content'] = self::renderFile($this->Layout, $compiledData);
            }
        }
        return $compiledData['content'];
    }

    private function extractData(array $compiledData) {
        foreach ($compiledData as $data) {
            if (is_object($data) && is_a($data, 'Vue')) {
                $this->extractMeta($data);
                $this->extractScript($data);
                $this->extractStyle($data);
            }
        }
    }

    public function extractScript(Vue $data) {
        $this->Script = array_merge($this->Script, $data->script());
    }

    public function extractMeta(Vue $data) {
        $this->Meta = array_merge($this->Meta, $data->meta());
    }

    public function extractStyle(Vue $data) {
        $this->Style = array_merge($this->Style, $data->style());
    }

}
