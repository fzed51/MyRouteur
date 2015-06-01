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
class Vue implements VueGenericInterface, VueInterface, VueLayoutInterface, VueHtmlElementInterface {

    use TraitVueLayout,
        TraitVueModel,
        TraitVueTitre,
        TraitVueMeta,
        TraitVueStyle,
        TraitVueScript;

    /**
     * Conteneur des données de la vue
     * @var array
     */
    protected $data = array();

    /**
     *
     * @param string $modelSlug
     * @param string $layoutSlug
     */
    public function __construct($modelSlug, $layoutSlug = '') {
        $this->setModel($modelSlug);
        if (!empty($layoutSlug)) {
            $this->setLayout($layoutSlug);
        } else {
            $this->setLayout(self::$DefautLayout);
        }
    }

    public function __unset($field) {
        if ($this->dataExist($field)) {
            unset($this->data[$field]);
        }
    }

    private function dataExist($field) {
        return isset($this->data[$field]);
    }

    public function __get($field) {
        switch ($field) {
            case 'titre':
                return $this->renderTitre();
            case 'meta':
                return $this->renderMeta();
            case 'style':
                return $this->renderStyle();
            case 'script':
                return $this->renderScript();
            default:
                return $this->get($field);
        }
    }

    public function __isset($field) {
        return $this->dataExist($field);
    }

    public function __set($field, $value) {
        $this->setData($field, $value);
    }

    public function setData($field, $value) {
        $this->data[$field] = $value;
    }

    public function setDatas(array $datas) {
        foreach ($datas as $field => $value) {
            $this->setData($field, $value);
        }
    }

    public function get($field, $default = "") {
        if ($this->dataExist($field)) {
            return $this->data[$field];
        } else {
            return $default;
        }
    }

    private function renderFile($__file, array $__data) {
        extract($__data);
        unset($__data);
        ob_start();
        include $__file;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    public function render(array $data = []) {
        $data = array_merge($this->data, $data);
        $this->content = $this->renderFile($this->getModelFile(), $data);
        if (!empty($this->getLayout())) {
            $this->content = $this->renderFile($this->getLayoutFile(), $data);
        }
        return $this->content;
    }

}
