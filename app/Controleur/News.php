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
        \DB::insertInTable('NEWS', \Requete::input('data'));
        $this->index();
    }

    function get_Read($id) {
        $new = \Db::getIdTable('NEWS', $id);
        $vue = new \Vue('news.read');
        $vue->addData('news', $new);
        echo $vue->render();
    }

    function get_Update($id) {
        $new = \Db::getIdTable('NEWS', $id);
        $vue = new \Vue('news.edit');
        $vue->addData('news', $new);
        echo $vue->render();
    }

    function post_Update($id) {
        \DB::updateTable('NEWS', $id, \Requete::input('data'));
        $this->index();
    }

    function post_delete() {
        $data = \Requete::input('data');
        if (isset($data['id'])) {
            $id = $data['id'];
        }
    }

}
