
	<?php
			require "todo.php";
		
		
			function return_response($status, $statusMessage, $data) {
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
					$new_todo->insert($dbconn); //A falta de programar el insert
					$todo_list = Todo::DB_selectAll($db->connection);
					// $todo_list -> Convertir a json y pasarlo en return_response
					// return_response(200, "OK", $json_del_array_de_todo);
					break;
				default:
					return_response(405, "Method Not Allowed", null);
					break;
			}
	
		
		
	?>

