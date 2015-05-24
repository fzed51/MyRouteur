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
require './app/tools.php';

$requete = new Requete();

require './route.php';

Routeur::reparti($requete->client['METHODE'], $requete->client['URI']);
