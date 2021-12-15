<?php

    class Main {
        private $conn;
		private $dBServername = "localhost";
		private $dBUsername = "root";
		private $dBPassword = "";
		private $dBName = "project_work";
		  
		function __construct(){
			$this->dBConnect();
		}

		function dBConnect(){
			$this->conn = mysqli_connect($this->dBServername, $this->dBUsername, $this->dBPassword,$this->dBName);
		}

        function signup() {
            $obj_data = json_decode(file_get_contents("php://input"), true);
  
            $username = $obj_data['uid'];
            $email = $obj_data['mail'];
            $password = $obj_data['pwd'];
                   
            $sql = "SELECT * FROM users WHERE username=? OR email=?;";
            $stmt = mysqli_stmt_init($this->conn);
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
                $stmt = mysqli_stmt_init($this->conn);
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
                $queryconn = mysqli_query($this->conn, $query);
        
                $items = mysqli_fetch_assoc($queryconn);
        
                echo json_encode($items);
              }
            }
            mysqli_close($this->conn);
        }

        function login() {
            $mailuid = $_POST['mailuid'];
	        $password = $_POST['pwd'];
	
            // We check for any empty inputs. 
            if (empty($mailuid) || empty($password)) {
                header("Location: ../signup.php?lgerror=emptyfields&mailuid=".$mailuid);
                exit();
            }
            else {
                $sql = "SELECT * FROM users WHERE username=? OR email=?;";
                $stmt = mysqli_stmt_init($this->conn);
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
            mysqli_close($this->conn);
        }

        function logout() {
            session_start();
            session_unset();
            session_destroy();
            header("Location: ../signup.php");
        }

        function admin_approve() {
            $values = json_decode(file_get_contents("php://input"), true);
            if(isset($values['actor']) && $values['actor'] === "admin") {
                $action = $values['do'];
                $approve = 0;
    
                if ($action === "approve") {
                    $approve = 1;
                } elseif ($action === "reject") {
                    $approve = 0;
                }
    
                $query = "UPDATE background_checks SET admin_aprv = " . $approve . " WHERE detail_id = ".$values['id'].";";
                $queryconn = mysqli_query($this->conn, $query);
    
                $query1 = "SELECT ud.*, bc.bg_status, bc.admin_aprv FROM user_details as ud INNER JOIN background_checks as bc ON ud.id = bc.detail_id WHERE ud.id= " . $values['id'];
                $queryconn1 = mysqli_query($this->conn, $query1);
    
                $items = mysqli_fetch_assoc($queryconn1);
    
                mysqli_close($this->conn);
    
                echo json_encode($items);
            }
        }

        function save_details() {
            session_start();
        
            $uid = $_SESSION['id'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $gender = $_POST['gender'];
            $stno= $_POST['stno'];
            $stname = $_POST['stname'];
            $city = $_POST['city'];
            $postcode = $_POST['postal'];
            $state = $_POST['state'];
            
            
            $address = $stno.' '. $stname.', '.$city.', '.$postcode.', '. $state;

            $sql = "INSERT INTO user_details (u_id, fname, lname, gender, address) VALUES (?, ?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($this->conn);
            
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssss", $uid, $fname, $lname, $gender, $address);
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
                    mysqli_query($this->conn, $query);	
                }

                $query = "INSERT INTO background_checks (detail_id) Values (".$last_id.");";
                $queryconn = mysqli_query($this->conn, $query);

                $query1 = "SELECT * FROM user_details WHERE id =" . $last_id .";";
                $queryconn1 = mysqli_query($this->conn, $query1);

                $items = mysqli_fetch_assoc($queryconn1);

                mysqli_close($this->conn);

                echo json_encode($items);
            }
            else {
                // If there is an error we send the user back to the signup page.
                echo "sqlerror";
                exit();	
            }
        }

        function view_app() {

        }
                
    }