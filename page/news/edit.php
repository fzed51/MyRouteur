<?php
if (isset($new)) {
    $new_titre = $new->titre;
    $new_text = $new->text;
} else {
    $new_titre = '';
    $new_text = '';
}
?>
<h1>Nouvelle news?</h1>
<a href="<?= Routeur::getUrl('News.index') ?>">&Lt;</a><br>
<a href="<?= Routeur::getUrl('home') ?>">Home</a><br>
<form action="<?= Routeur::getUrl('News.Create'); ?>" method="POST">
    <input type="hidden" name="_METHODE" value="POST" />
    <div>
        <label>
            Titre
            <input type="text" name="data[titre]" value="<?= $new_titre; ?>"/>
        </label>
    </div>
    <div>
        <label>
            Commentaire
            <textarea name="data[text]">
                <?= $new_titre; ?>
            </textarea>
        </label>
    </div>
    <div>
        <button type="submit">Ajouter</button>
    </div>
</form>