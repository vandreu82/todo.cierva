<?php
header("Access-Control-Allow-Origin: http://todo.terminus.lan");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	// Termina la ejecución para las solicitudes de verificación (OPTIONS) de CORS
	exit(0);
}



require "todo.php";
require "DB.php";


function return_response($status, $statusMessage, $data)
{
	header("HTTP/1.1 $status $statusMessage");
	header("Content-Type: application/json; charset=UTF-8");
	echo json_encode($data);
}

$bodyRequest = file_get_contents("php://input");

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$db = new DB();
		$new_todo = new Todo;
		$new_todo->jsonConstruct($bodyRequest);
		$new_todo->DB_insert($db->connection);
		$todo_list = Todo::DB_selectAll($db->connection);
		return_response(200, "OK", $todo_list);
		break;
	case 'DELETE':
		$db = new DB();
		$new_todo = new Todo;
		$new_todo->jsonConstruct($bodyRequest);
		$new_todo->DB_delete($db->connection);
		$todo_list = Todo::DB_selectAll($db->connection);
		return_response(200, "OK", $todo_list);
		break;
	default:
		return_response(405, "Method Not Allowed", null);
		break;
}



?>