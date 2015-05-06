<?php

use App\Requete;
use App\Route;
use App\Routeur;

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

Routeur::add((new Route('GET', '', function() {
	Echo "index<br>" .
	"<a href=\"" . Routeur::getUrl('bonjour', ['name' => 'Fabien']) . "\">bonjour Fabien</a>" .
	"<a href=\"" . Routeur::getUrl('user_do', ['action' => 'update', 'id' => 95]) . "\">bonjour Fabien</a>";
}))->setName('home'));

Routeur::add((new Route('GET', 'helo/{name}', function($name) {
	Echo "bonjour $name<br><a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
}))->setName('bonjour'));

Routeur::add(
		(new Route('GET', 'user/{action}/{id}', function($id, $action) {
			Echo "l'utilisateur n°$id fait $action<br><a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
		}))
				->setName('user_do')
				->setValidation('id', '[0-9]')
				->setValidation('action', 'delete|edit')
);

Routeur::add((new Route('GET', 'contact', 'Page@Contact'))->setName('contact'));

Routeur::reparti($requete);

