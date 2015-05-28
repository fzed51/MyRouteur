<h1>Nouvelle news?</h1>
<a href="<?= Routeur::getUrl('News.index') ?>">&Lt;</a>
<a href="<?= Routeur::getUrl('home') ?>">Home</a>
<form action="<?= Routeur::getUrl('News.Create'); ?>" method="POST">
    <input type="hidden" name="_METHODE" value="POST" />
    <div>
        <label>
            Titre
            <input type="text" name="data[titre]" value=""/>
        </label>
    </div>
    <div>
        <textarea name="data[text]"></textarea>
    </div>
    <div>
        <button type="submit">Ajouter</button>
    </div>
</form>