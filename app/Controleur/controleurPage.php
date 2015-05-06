<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controleur;

use App\Routeur;

/**
 * Description of controleurPage
 *
 * @author fabien.sanchez
 */
class controleurPage extends Controleur {

	function about() {
		echo "<h1>A-propos de MyRoute</h1>" . PHP_EOL
		. "<a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
	}

}
