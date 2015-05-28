<!DOCTYPE html>
<html>
    <head>
        <title><?= $titre; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $meta; ?>
        <?= $style; ?>
    </head>
    <body >
        <div id="master_content">
            <?= $content; ?>
        </div>
        <?= $script; ?>
    </body>
</html>
