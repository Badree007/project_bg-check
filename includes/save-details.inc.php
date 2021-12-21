<?php
	header("Content-Type: application/json");
	$method = $_SERVER['REQUEST_METHOD'];

	if($method === 'POST') {
		session_start();
		require_once 'dbh.inc.php';
	
		$uid = $_SESSION['id'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$gender = $_POST['gender'];
		$birth = $_POST['birthday'];
		$stno= $_POST['stno'];
		$stname = $_POST['stname'];
		$city = $_POST['city'];
		$postcode = $_POST['postal'];
		$state = $_POST['state'];

		$verify = false;
		
		if($_POST['addressVerify'] === "success") {
			$verify = true;
		}

		if($_POST['addressVerify'] === "failed") {
			$verify = false;
		}

		$address = $stno.' '. $stname.', '.$city.', '.$postcode.', '. $state;

		$sql = "INSERT INTO `user_details` (`u_id`, `fname`, `lname`, `birthday`, `gender`, `address`) VALUES (?, ?, ?, ?, ?, ?);";
		$stmt = mysqli_stmt_init($conn);
		
		try {
			if (mysqli_stmt_prepare($stmt, $sql)) {
				mysqli_stmt_bind_param($stmt, "ssssss", $uid, $fname, $lname, $birth, $gender, $address);
				mysqli_stmt_execute($stmt);
	
				$last_id = mysqli_stmt_insert_id($stmt);
	  
				mysqli_stmt_close($stmt);
	
				mkdir('../images/ID_Cards/'.$last_id);
				$file_name = $_FILES['photoid']['name'];
				$file_temp = $_FILES['photoid']['tmp_name']; 
				$store_loc = '../images/ID_Cards/'.$last_id.'/'.$file_name;
				$get_loc = 'images/ID_Cards/'.$last_id.'/'.$file_name;
				
				if(move_uploaded_file($file_temp, $store_loc)) {
					$query = "UPDATE `user_details` SET `photo_id`='".$get_loc. "' WHERE id =". $last_id .";";
					mysqli_query($conn, $query);	
				}
	
				$query1 = "INSERT INTO `background_checks` (`detail_id`, `bg_status`) 
							Values(".$last_id.", ". $verify .");";
				mysqli_query($conn, $query1);
	
				$query2 = "SELECT * FROM `user_details` WHERE id =" . $last_id .";";
				$queryconn2 = mysqli_query($conn, $query2);
	
				$items = mysqli_fetch_assoc($queryconn2);
				
				mysqli_close($conn);
				echo json_encode($items);
	
			}
			else {
				// If there is an error we send the user back to the signup page.
				echo "sqlerror";
				exit();	
			}	
		} catch (Exception $e) {
			echo "Caught error: " . $e->getMessage();
		}			
	} else {
		echo "wrongMethod";
		exit();
	}
