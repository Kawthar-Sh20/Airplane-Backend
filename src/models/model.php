<?php

class selectModel {
    private static $allowedTables = ['hotels', 'flights', 'airpots', 'users', 'taxis'];
    private static $allowedColumns = ['id', 'rating', 'city', 'country', 'name', 'description', 'id_airport', 'id_user', 'id_flight', 'id_taxi'];

    public static function isValidTable($table) {
        return in_array($table, self::$allowedTables);
    }

    public static function isValidColumn($column) {
        return in_array($column, self::$allowedColumns);
    }

    public static function selectAll($table) {
        return "SELECT * FROM $table;";
    }

    public static function select($table, $param) {
		if ($param == 'id') {
			$column = substr($table, 0, -1);
			$param = 'id_' . $column;
		}
		return "SELECT * FROM $table WHERE $param = ?;";
	}


    /////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////
    
}