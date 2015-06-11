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

// Parametrage des vues
\App\Vue\Vue::$DefautLayout = 'defaut';
\App\Vue\Vue::$DossierLayout = __DIR__ . DS . 'page' . DS . 'layout';
\App\Vue\Vue::$DossierModel = __DIR__ . DS . 'page';

include_if_exist('./app/route.php');

try {
    Routeur::reparti($requete->client['METHODE'], $requete->client['URI']);
} catch (\Core\Routeur\RouteNotFoundException $e) {
    header("HTTP/1.0 404 Not Found");
}