<?php

namespace App\Controleur;

use App\Routeur\Routeur;

/**
 * Description of controleurPage
 *
 * @author fabien.sanchez
 */
class ControleurPage extends Controleur {

    function about() {
        echo "<h1>A-propos de MyRoute</h1>" . PHP_EOL
        . "<a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
    }

}
