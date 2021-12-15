<?php
	header("Content-Type: application/json");
	$method = $_SERVER['REQUEST_METHOD'];

    if($method === 'POST') {
		session_start();
		require 'dbh.inc.php';
        $getData = json_decode(file_get_contents("php://input"), true);

        $appId = $getData['appID'];
        $text = $getData['feedText'];

        $sql = "UPDATE `background_checks` SET `feedback` = ? WHERE `detail_id` = ?";
		$stmt = mysqli_stmt_init($conn);

        try  {
            mysqli_stmt_prepare($stmt, $sql);
			mysqli_stmt_bind_param($stmt, "ss", $text, $appId);
			mysqli_stmt_execute($stmt);

			$last_id = mysqli_stmt_insert_id($stmt);  
			mysqli_stmt_close($stmt);

            $query = "SELECT ud.*, bc.bg_status, bc.admin_aprv, bc.feedback FROM user_details as ud INNER JOIN background_checks as bc ON ud.id = bc.detail_id WHERE ud.id = $appId;";
			$queryconn = mysqli_query($conn, $query);

			$items = mysqli_fetch_assoc($queryconn);

			mysqli_close($conn);

			echo json_encode($items);
        }
        catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }


    } else {
		echo "wrongMethod";
		exit();
	}