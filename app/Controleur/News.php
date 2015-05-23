<?php

namespace App\Controleur;

use App\Database\Db;
use App\Routeur\Routeur;

/**
 * Description of News
 *
 * @author fzed51
 */
class News extends Controleur {

    private $page = "<html>"
        . "<head><titre>News - {titre}</titre></head>"
        . "<body>"
        . "<h1>News</h1>"
        . "{content}"
        . "</body>"
        . "</html>";

    public function index() {
        $news = Db::getAllTable('NEWS');
        $content = "<a href=\"" . Routeur::getUrl('home') . "\">Home</a>" . PHP_EOL;
        foreach ($news as $new) {
            $content .= "<h2>{$new->titre}</h2>" . PHP_EOL;
            $content .= "<p>{$new->text}</p><nr/>" . PHP_EOL;
        }
        $content .= "<a href=\"" . Routeur::getUrl('News.Create') . "\">+</a>" . PHP_EOL;
        $page = str_replace('{titre}', 'tout', $this->page);
        $page = str_replace('{content}', $content, $page);
        echo $page;
    }

    function get_post_Create() {
        ?>
        <form action="<?= Routeur::getUrl(''); ?>" method="POST">
            <label>Titre<input type="text" name="titre" value=""/></label>
            <textarea name="text"></textarea>
        </form>
        <?php
    }

    function get_Read($id) {
        
    }

    function get_post_Update($id) {
        
    }

    function post_delete() {
        
    }

    private function editNew($id = -1, $titre = '', $text = '') {
        
    }

}
