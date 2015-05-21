<?php

/*
 * The MIT License
 *
 * Copyright 2015 fabien.sanchez.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Database;

/**
 * Description of Db
 *
 * @author fabien.sanchez
 */
class Db extends \PDO {

	private static $instance = null;
	protected $fileData = __DIR__ . "db.sqlite";

	private function __construct() {
		parent::__construct('sqlite:' . $this->fileData);
	}

	public static function getInstance() {
		if (is_null(static::$instance)) {
			$classeName = get_called_class();
			static::$instance = new $classeName();
		}
		return static::$instance;
	}

	public static function getAllTable($tableName) {
		$cnx = static::getInstance();
		$stmt = $cnx->query("SELECT * FROM $tableName");
		return $stmt->fetchAll(PDO::FETCH_CLASS);
	}

	public static function getIdTable($tableName, $id) {
		$cnx = static::getInstance();
		$stmt = $cnx->query("SELECT * FROM $tableName WHERE `id` = $id");
		return $stmt->fetchAll(PDO::FETCH_CLASS);
	}

	public static function insertInTable($tableName, array $datas) {
		$cnx = static::getInstance();
		$ds = [];
		$es = [];
		$ms = [];
		foreach ($datas as $f => $v) {
			if ($f == 'id') {
				continue;
			}
			array_push($fields, '`' . $f . '`');
			array_push($params, '?');
			array_push($values, $v);
		}

		$stmt = $cnx->prepare("INSERT INTO $tableName (" . implode(',', $f) . ") VALUES (" . implode(',', $params) . ")");
		$stmt->execut($values);
	}

	public static function updateTable($table, $id, array $datas) {
		$cnx = static::getInstance();
		$fields = [];
		$values = [];
		foreach ($datas as $f => $v) {
			if ($f == 'id') {
				continue;
			}
			array_push($fields, '`' . $f . '` = ?');
			array_push($values, $v);
		}

		$stmt = $cnx->prepare("UPDATE $tableName SET " . implode(',', $f) . " WHERE `id` = $id");
		$stmt->execut($values);
	}

}
