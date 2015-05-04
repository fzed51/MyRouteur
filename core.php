<?php

use App\Requete;
use App\Route;
use App\Routeur;

require './vendor/autoload.php';

$requete = new Requete();

Routeur::add((new Route('GET', '/', function() {
	Echo "index<br><a href=\"" . Routeur::getUrl('bonjour', ['name' => 'Fabien']) . "\">bonjour Fabien</a>";
}))->setName('home'));

Routeur::add((new Route('GET', 'helo/{name}', function($name) {
	Echo "bonjour $name<br><a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
}))->setName('bonjour'));

Routeur::reparti($requete);

