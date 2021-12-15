<?php
	$method = $_SERVER['REQUEST_METHOD'];

	if($method === 'POST') {
		session_start();
		require 'dbh.inc.php';

        $uid = $_SESSION['id'];
		$subject = $_POST['subject'];
		$msgText = $_POST['msg'];

        $sql = "INSERT INTO inquiry (user_id, subject, message) VALUES (?, ?, ?);";
		$stmt = mysqli_stmt_init($conn);
		
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_bind_param($stmt, "sss", $uid, $subject, $msgText);
			mysqli_stmt_execute($stmt);

            header("Location: ../contact.php?success=submitted");
            exit();
        } 
        else {
            header("Location: ../contact.php?err=sqlerror");
            exit();
        }
    }