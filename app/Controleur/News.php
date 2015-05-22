<?php

namespace App\Controleur;

use App\Database\Db;
use App\Routeur\Routeur;

/**
 * Description of News
 *
 * @author fzed51
 */
class News {

	private $page = "<html>"
		. "<head><titre>News - {titre}</titre></head>"
		. "<body>"
		. "<h1>News</h1>"
		. "{content}"
		. "</body>"
		. "</html>";

	function get_All() {
		$news = Db::getAllTable('NEWS');
		$content = "<a href=\"" . Routeur::getUrl('home') . "\">Home</a>" . PHP_EOL;
		foreach ($news as $new) {
			$content .= "<h2>{$new->titre}</h2>" . PHP_EOL;
			$content .= "<p>{$new->text}</p><nr/>" . PHP_EOL;
		}
		$page = str_replace('{titre}', 'tout', $this->page);
		$page = str_replace('{content}', $content, $page);
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
