<?php

namespace App\Controleur;

/**
 * Description of controleurPage
 *
 * @author fabien.sanchez
 */
class ControleurPage extends Controleur {

    function index() {
        $this->about();
    }

    function about() {
        echo "<h1>A-propos de MyRoute</h1>" . PHP_EOL
        . "<a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
    }

}
