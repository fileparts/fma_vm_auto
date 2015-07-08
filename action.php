<?php include('./config.php'); ?>
<html>
<head>
<?php include('./head.php'); ?>
</head>
<body>
  <?php include('./nav.php'); ?>
  <div class="main wrp">
  <?php
  if(isset($_GET['a'])) {
    $action = $_GET['a'];
    $okay = false;

    if($action == "register") {
      $formName = strtolower($_POST['formName']);
      $formEmail = $_POST['formEmail'];
      $formFirst = $_POST['formFirst'];
      $formLast = $_POST['formLast'];
      $formPass = $_POST['formPass'];
      $formPass = crypt($formPass, $cryptSalt);

      $checkUsername = $con->prepare("SELECT * FROM users WHERE userName=?");
      $checkUsername->bind_param("s", $formName);
      $checkUsername->execute();
      $checkUsername->store_result();
      if($checkUsername->num_rows > 0) {
  ?>
    <p class="alert">Username is already taken, redirecting...</p>
  <?php
        redirect("./login.php");
      } else {
        $okay = true;
      };
      $checkUsername->close();

      if($okay == true) {
        $createUser = $con->prepare("INSERT INTO users(userName,userEmail,userFirst,userLast,userPass) VALUES(?,?,?,?,?)");
        $createUser->bind_param("sssss", $formName,$formEmail,$formFirst,$formLast,$formPass);
        if($createUser->execute()) {
  ?>
    <p class="alert">User successfully created, redirecting...</p>
  <?php
          redirect("./login.php");
        } else {
  ?>
    <p class="alert">Users unsuccessfully created, redirecting...</p>
  <?php
          redirect("./register.php");
        };
        $createUser->close();
      };
    } else if($action == "login") {
      $formName = strtolower($_POST['formName']);
      $formPass = $_POST['formPass'];
      $formPass = crypt($formPass, $cryptSalt);

      $checkUsername = $con->prepare("SELECT * FROM users WHERE userName=?");
      $checkUsername->bind_param("s", $formName);
      $checkUsername->execute();
      $checkUsername->store_result();
      if($checkUsername->num_rows > 0) {
        $okay = true;
      } else {
  ?>
    <p class="alert">Username not found, redirecting...</p>
  <?php
        redirect("./register.php");
      };
      $checkUsername->close();

      if($okay == true) {
        $checkPassword = $con->prepare("SELECT userID,userPass,userPerms FROM users WHERE userName=?");
        $checkPassword->bind_param("s", $formName);
        $checkPassword->execute();
        $checkPassword->bind_result($userID,$userPass,$userPerms);
        while($checkPassword->fetch()) {
          if($userPass == $formPass) {
  ?>
    <p class="alert">Logging In, redirecting...</p>
  <?php
            $_SESSION['vm_userID'] = $userID;
            $_SESSION['vm_userPerms'] = $userPerms;
            redirect("./");
          } else {
  ?>
    <p class="alert">Incorrect Password, redirecting...</p>
  <?php
            redirect("./login.php");
          };
        };
        $checkPassword->close();
      };
    } else if($action == "book") {
      $userID 						= $_POST['userID'];
			$machineID 					= $_POST['machineID'];
			$bookingStartDate 		= $_POST['formStart'];

			if(strlen($bookingStartDate) == 10) {
				if(strpos($bookingStartDate, '-') !== FALSE) {
					$temp_start			= explode('-', $bookingStartDate);
					$bookingStartDate	= $temp_start[2]. '-' .$temp_start[1]. '-' .$temp_start[0];
				} else {
					echo "<p class='alert'>Date Conversion Error, Redirecting...</p>";
					redirect("./view.php?t=m&id=$machineID");
					$okay 					= false;
				};
			} else {
        echo "<p class='alert'>Date Conversion Error, Redirecting...</p>";
        redirect("./view.php?t=m&id=$machineID");
				$okay 						= false;
			};

			$bookingEndDate 			= $_POST['formEnd'];

			if(strlen($bookingEndDate) == 10) {
				if(strpos($bookingEndDate, '-') !== FALSE) {
					$temp_end			= explode('-', $bookingEndDate);
					$bookingEndDate	= $temp_end[2]. '-' .$temp_end[1]. '-' .$temp_end[0];
				} else {
					echo "<p class='alert'>Date Conversion Error, Redirecting...</p>";
					redirect("./view.php?t=m&id=$machineID");
					$okay 					= false;
				};
			} else {
        echo "<p class='alert'>Date Conversion Error, Redirecting...</p>";
        redirect("./view.php?t=m&id=$machineID");
				$okay 						= false;
			};

			$temp_bookingStart 		= strtotime($bookingStartDate);
			$temp_bookingEnd			= strtotime($bookingEndDate);

			if($checkDates = $con->prepare("SELECT bookingStart,bookingEnd FROM bookings WHERE machineID=?")) {
				$checkDates->bind_param("i", $machineID);
				if($checkDates->execute()) {
					$checkDates->store_result();
					if($checkDates->num_rows > 0) {
						$checkDates->bind_result($bookingStart,$bookingEnd);
						while($checkDates->fetch()) {
							$bookingStart 	= strtotime($bookingStart);
							$bookingEnd 		= strtotime($bookingEnd);

							if(($bookingStart >= $temp_bookingStart) && ($bookingStart <= $temp_bookingEnd)) {
								echo "<p class='alert'>Date is Taken, Redirecting...</p>";
								redirect("./view.php?t=m&id=$machineID");
							} else {
								if(($bookingEnd >= $temp_bookingStart)  && ($bookingEnd <= $temp_bookingEnd)) {
									echo "<p class='alert'>Date is Taken, Redirecting...</p>";
									redirect("./view.php?t=m&id=$machineID");
								} else {
									if(($temp_bookingStart >= $bookingStart) && ($temp_bookingStart <= $bookingEnd)) {
										echo "<p class='alert'>Date is Taken, Redirecting...</p>";
										redirect("./view.php?t=m&id=$machineID");
									} else {
										if(($temp_bookingEnd >= $bookingStart)  && ($temp_bookingEnd <= $bookingEnd)) {
											echo "<p class='alert'>Date is Taken, Redirecting...</p>";
											redirect("./view.php?t=m&id=$machineID");
										} else {
											$okay = true;
										};
									};
								};
							};
						};
					} else {
						$okay = true;
					};

					if($okay == true) {
						if($createBooking = $con->prepare("INSERT INTO bookings(userID,machineID,bookingStart,bookingEnd) VALUES(?,?,?,?)")) {
							$createBooking->bind_param("iiss", $userID,$machineID,$bookingStartDate,$bookingEndDate);
							if($createBooking->execute()) {
								echo '<p class="alert">Booking Successfully Created, Redirecting...</p>';
								redirect("./view.php?t=m&id=$machineID");
							} else {
								echo '<p class="alert">Execution Error: Booking Creation, Redirecting...</p>';
								redirect("./view.php?t=vmm&id=$machineID");
							};
						};
						$createBooking->close();
					};
				} else {
					echo "<p class='alert'>Execution Error: Check Dates, Redirecting...</p>";
				};
			};
			$checkDates->close();
    } else {
  ?>
    <p class="alert">A Valid Action is Required...</p>
  <?php
      redirect("./");
    };
  } else {
  ?>
    <p class="alert">An Action is Required...</p>
  <?php
    redirect("./");
  };
  ?>
  </div>
</body>
</html>
