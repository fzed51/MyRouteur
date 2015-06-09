<!DOCTYPE html>
<?php
$this->addFileStyle('bootstrap');
$this->addFileScript('jquery-1.11.3');
$this->addFileScript('bootstrap');
?>
<html>
    <head>
        <title><?= $this->titre; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $this->meta; ?>
        <?= $this->style; ?>
    </head>
    <body >
        <div id="master_content">
            <?= $this->content; ?>
        </div>
        <?= $this->script; ?>
    </body>
</html>
