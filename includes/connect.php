<?php    
    header("Content-Type: application/json");
	include('api.php');

    $method = $_SERVER['REQUEST_METHOD'];
	$function = new Main();

    $data = '';

    if($method === 'POST') {
		$obj_data = json_decode(file_get_contents("php://input"), true);
    }

    echo json_encode($data);

?>