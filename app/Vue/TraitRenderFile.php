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

namespace App\Vue;

/**
 * Description of AbstractVue
 *
 * @author Sandrine
 */
class TraitRenderFile {

    /**
     * Nom du layout par defaut
     * @static
     * @var string
     */
    static $DefautLayout = "";

    /**
     * Dossier où se trouve les layout
     * @static
     * @var string
     */
    static $DossierLayout = __DIR__ . '\..\..';

    /**
     * slug du layout
     * @var string
     */
    protected $Layout;

    /**
     * fichier contenant le modele du layout
     * @var string
     */
    protected $LayoutFile;

    /**
     *
     * @param string $slug
     * @return Vue
     * @throws VueException
     */
    public function setLayout($slug) {
        $fileName = self::$DossierLayout . "\\" . str_replace('.', "\\", $slug) . '.php';
        if (!file_exists($fileName)) {
            throw new VueException("Le layout '$slug' n'a pas été trouvé");
        }
        $this->Layout = $slug;
        $this->LayoutFile = $fileName;

        return $this;
    }

    /**
     * retourne le slug du layout
     * @return string
     */
    public function getLayout() {
        return $this->Layout;
    }

    /**
     * retourne le fichier du layout
     * @return string
     */
    public function getLayoutFile() {
        return $this->Layout;
    }

}
