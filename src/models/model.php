<?php

class selectModel {
	public static function selectAll($table_name) {
		return "SELECT * FROM $table_name;";
	}

	public static function select($table_name, $param) {
		if ($param == 'id') {
			$column = substr($table_name, 0, -1);
			$param = 'id_' . $column;

			return "SELECT * FROM $table_name where $param = ?;";
		} else {
			return "SELECT * FROM $table_name where $param = ?;";
		}
	}
}
