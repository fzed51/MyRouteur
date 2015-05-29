<a href="<?= Routeur::getUrl('home'); ?>">Home</a>
<div class="news">
    <?php foreach ($news as $new): ?>
        <div class="new">
            <h2><?= $new->titre; ?></h2>
            <p><?= $new->text; ?></p>
            <hr>
        </div>
    <?php endforeach; ?>
    <a href="<?= Routeur::getUrl('News.Create'); ?>">+</a>
</div>
<a href="<?= Routeur::getUrl('home'); ?>">Home</a>