<?php
	session_start();
	
	$con = new mysqli("localhost", "root", "Password1", "vm_auto");
	if ($con->connect_errno) {
		printf("Connect failed: %s\n", $con->connect_error);
		exit();
	};

	$cryptSalt = '---';
?>
