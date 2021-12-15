<?php
    require_once 'includes/dbh.inc.php';

    $id = $_GET['id'];

    $sql = "SELECT ud.*, bc.bg_status, bc.admin_aprv FROM user_details as ud INNER JOIN background_checks as bc ON ud.id = bc.detail_id WHERE ud.id=" . $id;
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document_PDF</title>
    <link rel="stylesheet" href="css/decorate.css">
</head>
<body>
    <main class="wrapper-main-report">
        <div class="report_content">
            <div class="header">
                <h1>NATIONAL POLICE CERTIFICATE</h1>
                <p>Reference ID: <b><?php echo $id ?></b></p>
                <p>Reason for Check: <b>JOB Application</b></p>
                <p>Date of Issue: <b><?php echo date_format(date_create($row['apply_date']), "M d, Y") ?></b></p>
                <p>Check Type: <b>Address Check Only</b></p>
            </div>
            <div class="body">
                <p>This document confirms that a search for the National Criminal History Check (Address Verify Only) has been successfully conducted on:<br><br>
                <b><?php echo $row['fname'] . ' ' . $row['lname']; ?>, born on <?php echo date_format(date_create($row['birthday']), "M d, Y"); }}?></b><br><br>
                At the date of issue.
                </p>
            </div>
            <div class="footer">
                <p><br><br>Authorised by: <br>
                ABC, Executive Officier NPC</p>
                <div class="sign">
                    <p class="signature">abc</p>
                    <p>Signature</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
