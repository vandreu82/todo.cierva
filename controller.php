<?php
require "todo.php";
require "DB.php";

function return_response($status, $statusMessage, $data)
{
	header("HTTP/1.1 $status $statusMessage");
	header("Content-Type: application/json; charset=UTF-8");
	echo json_encode($data);
}

try {
	$db = new DB();
	$bodyRequest = file_get_contents("php://input");
	$decodedRequest = json_decode($bodyRequest, true);

	switch ($_SERVER['REQUEST_METHOD']) {
		case 'POST':
			$new_todo = new Todo;
			$new_todo->jsonConstruct($bodyRequest);
			$new_todo->DB_insert($db->connection);
			break;
		case 'PUT':
			$updated_todo = new Todo;
			$updated_todo->parametersConstruct($decodedRequest['item_id'], $decodedRequest['content']);
			$updated_todo->DB_update($db->connection);
			break;
		case 'DELETE':
			$new_todo = new Todo;
			$new_todo->jsonConstruct($bodyRequest);
			$new_todo->DB_delete($db->connection);
			break;
		default:
			return_response(405, "Method Not Allowed", null);
			exit;
	}

	$todo_list = Todo::DB_selectAll($db->connection);
	return_response(200, "OK", $todo_list);

} catch (Exception $e) {
	return_response(500, "Internal Server Error", ["error" => $e->getMessage()]);
}
?>