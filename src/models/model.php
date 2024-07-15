<?php

class selectModel {
    private static $allowedColumns = ['id', 'rating', 'city', 'country', 'name', 'description', 'id_airport', 'id_user', 'id_flight', 'id_taxi'];
    private static $allowedTables = ['hotels', 'flights', 'airpots', 'users', 'taxis', 'hotel_bookings', 'flight_bookings', 'taxi_bookings'];
    public static function isValidColumn($column) {
        return in_array($column, self::$allowedColumns);
    }
    public static function isValidTable($table) {
        return in_array($table, self::$allowedTables);
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
    
    public static function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        return "INSERT INTO $table ($columns) VALUES ($placeholders);";
    }

    //////////////////////
    public static function delete($table, $param) {
        return "DELETE FROM $table WHERE $param = ?;";
    }
    
    /////////////////////
 
    
}