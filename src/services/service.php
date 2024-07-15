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
}