<h1>index</h1><br>
<a href="<?= Routeur::getUrl('bonjour', ['name' => 'Fabien']); ?>">bonjour Fabien</a><br>
<a href="<?= Routeur::getUrl('user_do', ['action' => 'edit', 'id' => 95]); ?>">Edition de l'utilisateur n°95</a><br>
<a href="<?= Routeur::getUrl('liste_route'); ?>">liste des routes</a><br>
<a href="<?= Routeur::getUrl('phpinfo'); ?>">phpinfo</a><br>
<a href="<?= Routeur::getUrl('News.index'); ?>">les news</a><br>
<a href="<?= Routeur::getUrl('contact'); ?>">contactez moi</a>