<?php

require 'src/models/model.php';

class selectService {
	public static function select($conn, $table, $param, $value, $limit = null) {
		if (!selectModel::isValidTable($table) || !selectModel::isValidColumn($param)) {
            die("Invalid table or column name");
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
			echo json_encode(["products" => $rows]);
		} else {
			echo json_encode(["message" => "Product not found"]);
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
			echo json_encode(["products" => $rows]);
		} else {
			echo json_encode(["message" => "Products not found"]);
		}

		
	}

////////////////////////////////////////////////////////////////
public static function insert($conn, $table_name, $data) {
	// Build the SQL query
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

		//////////////////////////
    }
	// public static function selectAll($conn, $table) {
	// 	$query = selectModel::selectAll($table);
	// 	$stmt = $conn->prepare($query);
	// 	$stmt->execute();
	// 	$result = $stmt->get_result();

	// 	if ($result->num_rows > 0) {
	// 		$rows = [];
	// 		while ($row = $result->fetch_assoc()) {
	// 			$rows[] = $row;
	// 		}
	// 		echo json_encode(["products" => $rows]);
	// 	} else {
	// 		echo json_encode(["message" => "Products not found"]);
	// 	}
	// }

