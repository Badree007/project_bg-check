<?php

if (isset($_POST['login-submit'])) 
{
	require 'dbh.inc.php';

	$mailuid = $_POST['mailuid'];
	$password = $_POST['pwd'];
	
	 // We check for any empty inputs. 
  if (empty($mailuid) || empty($password)) {
    header("Location: ../signup.php?lgerror=emptyfields&mailuid=".$mailuid);
    exit();
  }
  else {
	  $sql = "SELECT * FROM users WHERE username=? OR email=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      // If there is an error we send the user back to the signup page.
      header("Location: ../signup.php?lgerror=sqlerror");
      exit();
    }
    else {

      // If there is no error then we continue the script!

      mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result)) {
        // Then we match the password from the database with the password the user submitted. The result is returned as a boolean.
        $pwdCheck = password_verify($password, $row['password']);
        if ($pwdCheck == false) {
          header("Location: ../signup.php?lgerror=wrongpwd");
          exit();
        }
        else if ($pwdCheck == true) {

         session_start();
          // And NOW we create the session variables.
          $_SESSION['id'] = $row['u_id'];
          $_SESSION['uid'] = $row['username'];
          $_SESSION['email'] = $row['email'];
          // Now the user is registered as logged in and we can now take them back to the front page! :)
          header("Location: ../index.php?login=success");
          exit();
        }
      }
      else {
        header("Location: ../signup.php?lgerror=wronguidpwd");
        exit();
      }
    }
  }
	
	mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

else {
  header("Location: ../signup.php");
  exit();
}