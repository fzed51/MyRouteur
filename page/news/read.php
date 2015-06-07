

<div class="new">
    <a href="<?= Routeur::getUrl('News.index'); ?>">&Lt;</a>
    <h2><?= string2Html($new->titre); ?>
        <a class="bouton" href="<?= Routeur::getUrl('News.Update', ['id' => $new->id]); ?>">&lt;edit&gt;</a>
        <a class="bouton" href="<?= Routeur::getUrl('News.Update', ['id' => $new->id]); ?>">&lt;edit&gt;</a>
    </h2>
    <p><?= string2Html($new->text); ?></p>
</div>