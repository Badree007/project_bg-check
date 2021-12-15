<?php
    if(isset($_SESSION['id'])) {
        $sql = "";
        if($_SESSION['uid'] == 'admin') {
            $sql = "SELECT ud.*, bc.bg_status, bc.admin_aprv, bc.feedback FROM user_details as ud INNER JOIN background_checks as bc ON ud.id = bc.detail_id";
        } else {
            $sql = "SELECT ud.*, bc.bg_status, bc.admin_aprv, bc.feedback FROM user_details as ud INNER JOIN background_checks as bc ON ud.id = bc.detail_id WHERE ud.u_id=" . $_SESSION['id'];
        }

        $result = mysqli_query($conn, $sql);

        $adminBtn = "";

        $userBtn = "";

        if (mysqli_num_rows($result) > 0) {           
           
            while($row = mysqli_fetch_assoc($result)) {
                $feedbackText = "";
                if($_SESSION['uid'] == 'admin') {
                    $status = ($row['bg_status'] == 0) ? "<span style='color: #ad8c44;'>Address Verification Failed</span>": "<span style='color: #68b547;'>Address Verified</span>";
                    if ($row['feedback'] != null) {
                        $feedbackText = "<br><p>Feedback: <span>". $row['feedback']. "</span></p>";
                    }
                    if ($row['admin_aprv'] == null) {
                        $adminBtn = "<div class='action_btns'>
                                        <button class='action_btn btn-aprv'>Approve</button>
                                        <button class='action_btn btn-rej'>Reject</button>
                                    </div>";
                    }
                    else if($row['admin_aprv'] == 1) {
                        $adminBtn = "<div class='action_btns'>
                                        <h4 style='color:green; text-align:center;'>Approved</h4>
                                    </div>";
                    } elseif ($row['admin_aprv'] == 0) {
                        $adminBtn = "<div class='action_btns'>
                                        <h4 style='color:red; text-align:center;'>Rejected</h4>
                                    </div>";
                    }
                } else {
                    if ($row['admin_aprv'] == null) {
                        $status = "<span style='color: #ad8c44;'>Pending</span>";
                        $userBtn = "";
                    } elseif ($row['admin_aprv'] == 1) {
                        $status = "<span style='color: green;'>Approved</span>";
                        $feedback = $row['feedback'] != null ? "<p>Feedback submitted</p>" : "<button class='action_btn feedback_btn'>Leave Feedback</button>";
                        $userBtn = "<div class='action_btns'>
                                        <button class='action_btn download_btn'>View Report</button>"
                                        . $feedback . "</div>"
                                        ;
                    } elseif ($row['admin_aprv'] == 0){
                        $status = "<span style='color: red;'>Rejected</span>";
                        $userBtn = "<p style='text-align:center; font-style:italic; color:#f55d42;'>Your Application is rejected due to insufficient/invalid details. Contact Admin.</p>";
                    }
                    
                }
                
                $applyDate = date_create($row['apply_date']);
                $formattedDate = date_format($applyDate, "d M Y");
                echo "<div id = ".$row['id']." class=\"application\">
                        <div class='app_content'>
                            <h3>Application " . $row['id']. "</h3>
                            <p>Applicant: <span>" . $row['fname']. " " . $row['lname']. "</span></p>
                            <p>Gender: <span>" . $row['gender']. "</span></p>
                            <p>Address: <span>" . $row['address']. "</span></p>
                            <p>Applied on: <span>" . $formattedDate. "</span></p>
                            <p>Status: " . $status. "</p>
                            <p><u>Photo ID:</u><img class='photoid' src='".$row['photo_id']."' style='width: 30%; height: auto;'></p>
                            ". $feedbackText . "                          
                        </div>";
                if($_SESSION['uid'] == 'admin') {
                    echo $adminBtn;
                } else {
                    echo $userBtn;
                }

                echo "</div>";
            }
        } else {
            echo "<p>No active Applications.</p>";
        }
        mysqli_close($conn);
    }

?>