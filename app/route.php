<?php

Routeur::get('/', function() {
    echo (new Vue('home'))->render();
}, 'home');

Routeur::get('helo/{name}', function($name) {
    Echo "bonjour $name<br><a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
}, 'bonjour');
Routeur::get('info', function() {
    Echo "<h1>phpInfo</h1><br><a href=\"" . Routeur::getUrl('home') . "\">Home</a><br>";
    phpinfo();
    echo "<a href=\"" . Routeur::getUrl('home') . "\">Home</a><br>";
}, 'phpinfo');

Routeur::get('user/{action}/{id}', function($id, $action) {
    Echo "l'utilisateur n° $id fait $action<br><a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
}, 'user_do')->setValidation('id', '[0-9]+')->setValidation('action', 'delete|edit');

Routeur::get('liste_routes', function() {
    echo "<h1>Liste des routes</h1>";
    echo "<a href=\"" . Routeur::getUrl('home') . "\">Home</a>";
    echo "<pre>" . PHP_EOL;
    $table = Routeur::listRoutes();
    echo "<table border=1 >";
    echo "<tr>";
    foreach ($table[0] as $cell => $x) {
        echo "<th>$cell</th>";
    }
    echo "</tr>";
    foreach ($table as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>$cell</td>";
        }
        echo "</tr>";
    }
    echo"</table>";
    Routeur::debug('');
    echo PHP_EOL . "</pre>";
}, 'liste_route');

Routeur::mapControleur('post', 'App\Controleur\News');

Routeur::get('contact', 'ControleurPage@about', 'contact');
