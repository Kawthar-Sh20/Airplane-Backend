<?php

require __DIR__ . '/../models/model.php';

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
			while ((($limit === null || $limit > 0)) && $row = $result->fetch_assoc()) {
				$rows[] = $row;
				$limit--;
			}
			echo json_encode(["products" => $rows]);
		} else {
			echo json_encode(["message" => "Product not found"]);
		}
	}

	public static function selectAll($conn, $table) {
		$query = selectModel::selectAll($table);
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$rows = [];
			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
			echo json_encode(["products" => $rows]);
		} else {
			echo json_encode(["message" => "Products not found"]);
		}
	}
}