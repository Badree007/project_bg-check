<?php
	if (isset($_SESSION['id'])) 
  {
	  require 'dbh.inc.php';
	  
  }
  
    else if (!isset($_SESSION['id'])) {
	  header('location: signup.php?error=loginfirst');
  }