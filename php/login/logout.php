<?php
	// init session
	session_start();
	
	// unset session variables
	$_SESSION = array();
	
	// destroy session
	session_destroy();
	
	// redirect to login page
	header('location:login.php');
	exit;
?>