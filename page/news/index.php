<a href="<?= Routeur::getUrl('home'); ?>">Home</a>
<?php foreach ($news as $new): ?>
    <h2><?= $new->titre; ?></h2>
    <p><?= $new->text; ?></p>
    <hr>
<?php endforeach; ?>
<a href="<?= Routeur::getUrl('home'); ?>">Home</a>