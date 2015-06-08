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
        $vue->addFileStyle('new');
        $vue->setData('news', $news);
        echo $vue->render();
    }

    function get_Create() {
        echo (new \Vue('news.edit'))->render();
    }

    function post_Create() {
        $csrf = new \App\Session\Csrf(new \App\Session\Session());
        $csrf->check();
        \DB::insertInTable('NEWS', \Requete::input('data'));
        $this->index();
    }

    function get_Read($id) {
        $new = \Db::getIdTable('NEWS', $id);
        $vue = new \Vue('news.read');
        $vue->setData('new', $new);
        echo $vue->render();
    }

    function get_Update($id) {
        $new = \Db::getIdTable('NEWS', $id);
        $vue = new \Vue('news.edit');
        $vue->setData('new', $new);
        echo $vue->render();
    }

    function post_Update($id) {
        $csrf = new \App\Session\Csrf(new \App\Session\Session());
        $csrf->check();
        \DB::updateIdTable('NEWS', $id, \Requete::input('data'));
        $this->index();
    }

    function get_delete($id) {
        $csrf = new \App\Session\Csrf(new \App\Session\Session());
        $csrf->check();
        \DB::deleteIdTable('NEWS', $id);
        $this->index();
    }

}
