<?php

require __DIR__ . '/../models/model.php';

class selectService {
	public static function selectQty($conn, $table, $param, $value, $total) {
		$query = selectModel::select($table, $param);

		$stmt = $conn->prepare($query);

		if ($param == 'id') {
			$stmt -> bind_param('i', $value);
		} else {
			$stmt -> bind_param('s', $value);
		}

		$stmt->execute();
		
		$result = $stmt->get_result();
	
		if ($result->num_rows > 0) {
			$rows = [];
			while ($total > 0 && $row = $result->fetch_assoc()) {
				$rows[] = $row;
				$total--;
			}
			echo json_encode(["products" => $rows]);
		} else {
			echo json_encode(["message" => "Product not found"]);
		}
	}

	public static function selectParamAll($conn, $table, $param, $value) {
		$query = selectModel::select($table, $param);

		$stmt = $conn->prepare($query);

		if ($param == 'id') {
			$stmt -> bind_param('i', $value);
		} else {
			$stmt -> bind_param('s', $value);
		}

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

