<?php 
    if(isset($_GET['getMsg'])) {
        viewMsg();
    }

    if(isset($_POST)) {
        viewApplication();
    }

    function viewApplication() {
        require 'dbh.inc.php';
        
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
            $queryconn = mysqli_query($conn, $query);

            $query1 = "SELECT ud.*, bc.bg_status, bc.admin_aprv FROM user_details as ud INNER JOIN background_checks as bc ON ud.id = bc.detail_id WHERE ud.id= " . $values['id'];
            $queryconn1 = mysqli_query($conn, $query1);

            $items = mysqli_fetch_assoc($queryconn1);

            mysqli_close($conn);

            echo json_encode($items);
        }
    }

    function viewMsg() {
        if(isset($_SESSION['id'])) {
            if(($_SESSION['uid'] == "admin")) {
                require 'dbh.inc.php';

                $query = "SELECT inq.*, u.username, u.email FROM inquiry as inq INNER JOIN users as u WHERE inq.user_id = u.u_id;";
                $result = mysqli_query($conn, $query);
    
                if (mysqli_num_rows($result) > 0) {           
               
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='message' id=".$row['id'].">
                                <div class='user_detail'>
                                    <h3>".$row['username']."</h3>
                                    <p><a href=\"mailTO: ".$row['email']."\">".$row['email']."</a></p>
                                </div>
                                <p class='msgText'>".$row['message']."</p>
                              </div>";
                    }
                }
            }
        } else {
            header("Location: signup.php?err=login");
            exit();
        }
    }