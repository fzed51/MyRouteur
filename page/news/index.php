<a href="<?= Routeur::getUrl('home'); ?>">Home</a>
<div class="news">
    <?php foreach ($news as $new): ?>
        <div class="new">
            <h2><a href="<?= Routeur::getUrl('News.Read', ['id' => $new->id]); ?>"><?= $new->titre; ?></a></h2>
            <p><?= $new->text; ?></p>
        </div>
    <?php endforeach; ?>
    <a href="<?= Routeur::getUrl('News.Create'); ?>">+</a>
</div>
<a href="<?= Routeur::getUrl('home'); ?>">Home</a>