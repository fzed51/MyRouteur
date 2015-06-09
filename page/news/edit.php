<?php
if (isset($new)) {
    $action = Routeur::getUrl('News.Update', ['id' => $new->id]);
    $new_titre = $new->titre;
    $new_text = $new->text;
    $lib_btn = 'Modifier';
} else {
    $action = Routeur::getUrl('News.Create');
    $new_titre = '';
    $new_text = '';
    $lib_btn = 'Ajouter';
}
?>
<h1>Nouvelle news?</h1>
<a href="<?= Routeur::getUrl('News.index') ?>">&Lt;</a><br>
<form action="<?= $action ?>" method="POST">
    <?php
    $csrf = new \App\Session\Csrf(new \App\Session\Session());
    echo $csrf->getInput();
    ?>

    <input type="hidden" name="_METHODE" value="POST" />
    <div>
        <label>
            Titre
            <input type="text" name="data[titre]" value="<?= $new_titre; ?>"/>
        </label>
    </div>
    <div>
        <label>
            Commentaire<br>
            <textarea name="data[text]"><?= $new_text; ?></textarea>
        </label>
    </div>
    <div>
        <button type="submit"><?= $lib_btn; ?></button>
    </div>
</form>