<?php
//  $dBServername = "localhost";
//  $dBUsername = "root";
//  $dBPassword = "";
//  $dBName = "project_work";

// // Create connection
// $conn = mysqli_connect($dBServername, $dBUsername, $dBPassword, $dBName);

//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
// Connect to DB
$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);


// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}