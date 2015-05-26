<?php

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
require './tools.php';

include_if_exist('./app/alias.php');

$requete = new Requete();

include_if_exist('./app/route.php');

Routeur::reparti($requete->client['METHODE'], $requete->client['URI']);
