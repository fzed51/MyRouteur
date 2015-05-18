<?php

use App\Requete;
use App\Routeur\Routeur;

define('DS', DIRECTORY_SEPARATOR);
define('WS', '/');
define('ROOT', __DIR__ . DS);
$dir = basename(ROOT);
$tabUrl = explode($dir, $_SERVER['REQUEST_URI']);
if (count($tabUrl) > 1) {
    define('WEBROOT', $tabUrl[0] . $dir . WS);
} else {
    define('WEBROOT', WS);
}

require './vendor/autoload.php';

$requete = new Requete();

Routeur::get('/', function() {
    Echo "index<br>" .
    "<a href=\"" . Routeur::getUrl('bonjour', ['name' => 'Fabien']) . "\">bonjour Fabien</a><br>" .
    "<a href=\"" . Routeur::getUrl('user_do', ['action' => 'edit', 'id' => 95]) . "\">Edition de l'utilisateur nÂ°95</a><br>" .
    "<a href=\"" . Routeur::getUrl('contact') . "\">contactez moi</a><br>";
}, 'home');

Routeur::get('helo/{name}', function($name) {
    Echo "bonjour $name<br><a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
}, 'bonjour');

Routeur::get('user/{action}/{id}', function($id, $action) {
    Echo "l'utilisateur n° $id fait $action<br><a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
}, 'user_do')->setValidation('id', '[0-9]+')->setValidation('action', 'delete|edit');

Routeur::get('contact', 'ControleurPage@about', 'contact');

Routeur::reparti($requete->client['METHODE'], $requete->client['URI']);
