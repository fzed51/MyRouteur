<?php

namespace App\Controleur;

/**
 * Description of News
 *
 * @author fzed51
 */
class News extends Controleur {

    public function index() {
        $news = \Db::getAllTable('news');
        $vue = new \Vue('news.index');
        $vue->addData('news', $news);
        echo $vue->render();
    }

    function get_Create() {
        ?>
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
        <?php
    }

    function post_Create() {
        \DB::insertInTable('NEWS', \Requete::input('data'));
        $this->index();
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
