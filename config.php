<?php
	session_set_cookie_params(3600 * 24 * 7);
	session_start();

	$con = new mysqli("localhost", "root", "Password1", "vm_auto");
	if ($con->connect_errno) {
		printf("Connect failed: %s\n", $con->connect_error);
		exit();
	};

	$users = new mysqli("localhost", "root", "Password1", "web_users");
	if ($users->connect_errno) {
		printf("Connect failed: %s\n", $users->connect_error);
		exit();
	};
?>
