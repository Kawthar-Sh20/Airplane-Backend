<?php

require __DIR__ . '/../models/model.php';

function selectService($id, $conn) {
	$query = selectModel::selectId('flights');
	
	$stmt = $conn->prepare($query);
	$stmt -> bind_param('i', $id);
	$stmt->execute();
	
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		echo json_encode(["product" => $row]);
	} else {
		echo json_encode(["message" => "Row not found"]);
	}
}