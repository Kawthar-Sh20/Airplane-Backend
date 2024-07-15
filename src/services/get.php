<?php

require 'src/models/model.php';

class selectService {
	public static function select($conn, $table, $param, $value, $limit = null) {
		if (!selectModel::isValidColumn($param)) {
            die("Invalid column name");
        }

		$query = selectModel::select($table, $param);
		$stmt = $conn->prepare($query);

		$param == 'id' ? $stmt -> bind_param('i', $value) : $stmt -> bind_param('s', $value);

		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$rows = [];
			while (($limit === null || $limit > 0) && $row = $result->fetch_assoc()) {
				$rows[] = $row;
				$limit--;
			}
			echo json_encode(["data" => $rows]);
		} else {
			echo json_encode(["message" => "Item not found"]);
		}
	}

	public static function selectAll($conn, $table, $limit = null) {
		$query = selectModel::selectAll($table);
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->get_result();
	
		if ($result->num_rows > 0) {
			$rows = [];
			while (($limit === null || $limit > 0) && $row = $result->fetch_assoc()) {
				$rows[] = $row;
				$limit--;
			}
			echo json_encode(["data" => $rows]);
		} else {
			echo json_encode(["message" => "Items not found"]);
		}
	}
/////////////////////////////////
	public static function insert($conn, $table_name, $data) {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_map(function($value) use ($conn) {
            return "'" . mysqli_real_escape_string($conn, $value) . "'";
        }, array_values($data)));
        
        $sql = "INSERT INTO $table_name ($columns) VALUES ($values)";
        
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["message" => "Record inserted successfully"]);
        } else {
            echo json_encode(["message" => "Error inserting record: " . mysqli_error($conn)]);
        }
    }
///////////////////
	public static function delete($conn, $table, $param, $value) {
		if (!selectModel::isValidColumn($param)) {
			die("Invalid column name");
		}
	
		$query = selectModel::delete($table, $param);
		$stmt = $conn->prepare($query);
	
		$param == 'id' ? $stmt -> bind_param('i', $value) : $stmt -> bind_param('s', $value);
	
		$stmt->execute();
	
		if ($stmt->affected_rows > 0) {
			echo json_encode(["message" => "Record deleted successfully"]);
		} else {
			echo json_encode(["message" => "Item not found or could not be deleted"]);
		}
	}
	
}