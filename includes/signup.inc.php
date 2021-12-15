<?php
  header("Content-Type: application/json");
  $method = $_SERVER['REQUEST_METHOD'];

  if ($method === "POST"){	
    require 'dbh.inc.php';
    $obj_data = json_decode(file_get_contents("php://input"), true);
  
    $username = $obj_data['uid'];
    $email = $obj_data['mail'];
    $password = $obj_data['pwd'];
     	  
    $sql = "SELECT * FROM users WHERE username=? OR email=?;";
    $stmt = mysqli_stmt_init($conn);
    $last_id = "";
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo(json_encode(["err" => "sql errror"]));
      exit();
    }  
    else {
      mysqli_stmt_bind_param($stmt, "ss", $username, $email);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCount = mysqli_stmt_num_rows($stmt);
      mysqli_stmt_close($stmt);
      // Here we check if the username exists.
      if ($resultCount > 0) {
        echo(json_encode(["err" => "username taken"]));
        exit();
      }    
      else {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          // If there is an error we send the user back to the signup page.
          echo(json_encode(["err" => "sql errror"]));
          exit();
        }      
        else {
          $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
          mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
          mysqli_stmt_execute($stmt);
          
          $last_id = mysqli_stmt_insert_id($stmt);
          
          mysqli_stmt_close($stmt);
        }
        $query = "SELECT * FROM users WHERE u_id = " . $last_id ;
        $queryconn = mysqli_query($conn, $query);

        $items = mysqli_fetch_assoc($queryconn);

        echo json_encode($items);
      }
    }
    mysqli_close($conn);
  }
  else {
    echo(json_encode(["err" => "data failed"]));
    exit();
  }