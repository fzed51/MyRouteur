<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controleur;

use App\Database\Db;
use App\Routeur\Routeur;

/**
 * Description of News
 *
 * @author fzed51
 */
class News {

	function get_All() {
		$news = Db::getAllTable('NEWS');
		$content = "";
		foreach ($news as $nex) {
			$content .= "<h2>{$new->titre}</h2>" . PHP_EOL;
			$content .= "<p>{$new->text}</p><nr/>" . PHP_EOL;
		}
		$page = "<html>"
				. "<head><titre>News</titre></head>"
				. "<body>"
				. "<h1>News</h1>"
				. "<a href=\"" . Routeur::getUrl('home') . "\">home</a>"
				. $content
				. "</body>"
				. "</html>";
		echo $page;
	}

	function get_New() {

	}

	function get_One($id) {

	}

	function get_Update($id) {

	}

	private function editNew($id = -1, $titre = '', $text = '') {

	}

	function post_New($id = -1) {

	}

	function delete_New($id) {

	}

}
