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

    if($action == "book") {
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
								redirect("./view.php?t=m&id=$machineID");
							};
						};
						$createBooking->close();
					};
				} else {
					echo "<p class='alert'>Execution Error: Check Dates, Redirecting...</p>";
          redirect("./view.php?t=m&id=$machineID");
				};
			};
			$checkDates->close();
    } else if($action == "edit") {
      if($_SESSION['userPerms'] > 3) {
        $editType = $_POST['editType'];
        $formID = $_POST['formID'];
        $formIP = $_POST['formIP'];
        $formName = $_POST['formName'];
        $formHost = $_POST['formHost'];
        $formPurpose = $_POST['formPurpose'];
        $formUsage = $_POST['formUsage'];
        $formPerms = $_POST['formPerms'];

        $formFirst = $_POST['formFirst'];
        $formLast = $_POST['formLast'];
        $formEmail = $_POST['formEmail'];

        if($editType == "h") {
          $checkID = $con->prepare("SELECT * FROM hosts WHERE hostID=?");
          $checkID->bind_param("i", $formID);
          $checkID->execute();
          $checkID->store_result();
          if($checkID->num_rows > 0) {
            $editHost = $con->prepare("UPDATE hosts SET hostIP=?,hostName=?,hostPerms=? WHERE hostID=?");
            $editHost->bind_param("ssii", $formIP,$formName,$formPerms,$formID);
            if($editHost->execute()) {
?>
    <p class="alert">Host successfully updated, redirecting...</p>
<?php
              redirect("./admin.php");
            } else {
?>
    <p class="alert">Host unsuccessfully updated, redirecting...</p>
<?php
              redirect("./edit.php?t=h&id=$formID");
            };
            $editHost->close();
          } else {
?>
    <p class="alert">invalid host id, Redirecting...</p>
<?php
            redirect("./admin.php");
          };
          $checkID->close();
        } else if($editType == "m") {
          $checkID = $con->prepare("SELECT * FROM machines WHERE machineID=?");
          $checkID->bind_param("i", $formID);
          $checkID->execute();
          $checkID->store_result();
          if($checkID->num_rows > 0) {
            $editMachine = $con->prepare("UPDATE machines SET hostID=?,machinePerms=? WHERE machineID=?");
            $editMachine->bind_param("iii", $formHost,$formPerms,$formID);
            if($editMachine->execute()) {
              $editMachine->store_result();
              $editMachineDetails = $con->prepare("UPDATE machinedetails SET machinePurpose=?,machineUsage=? WHERE machineID=?");
              $editMachineDetails->bind_param("iii", $formPurpose,$formUsage,$formID);
              if($editMachineDetails->execute()) {
?>
    <p class="alert">Machine successfully updated, redirecting...</p>
<?php
                redirect("./admin.php");
              } else {
?>
    <p class="alert">Machine unsuccessfully updated[2], redirecting...</p>
<?php
                redirect("./edit.php?t=m&id=$formID");
              };
              $editMachineDetails->close();
            } else {
?>
    <p class="alert">Machine unsuccessfully updated[1], redirecting...</p>
<?php
              redirect("./edit.php?t=m&id=$formID");
            };
            $editMachine->close();
          } else {
?>
    <p class="alert">invalid machine id, redirecting...</p>
<?php
            redirect("./admin.php");
          };
          $checkID->close();
        } else if($editType == "u") {
          $checkID = $con->prepare("SELECT * FROM machineusages WHERE usageID=?");
          $checkID->bind_param("i", $formID);
          $checkID->execute();
          $checkID->store_result();
          if($checkID->num_rows > 0) {
            $editUsage = $con->prepare("UPDATE machineusages SET machineUsage=? WHERE usageID=?");
            $editUsage->bind_param("si", $formName,$formID);
            if($editUsage->execute()) {
?>
    <p class="alert">Usage Successfully updated, redirecting...</p>
<?php
              redirect("./admin.php");
            } else {
?>
    <p class="alert">Usage unsuccessfully updated, redirecting...</p>
<?php
              redirect("./edit.php?t=u&id=$formID");
            };
            $editUsage->close();
          } else {
?>
    <p class="alert">invalid usage id, redirecting...</p>
<?php
            redirect("./admin.php");
          };
          $checkID->close();
        } else {
?>
    <p class="alert">a valid edit type is required, redirecting...</p>
<?php
          redirect("./admin.php");
        };
      } else {
?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
<?php
        redirect("./");
      };
    } else if($action == "create") {
      $creationType = $_POST['creationType'];
      $formName = $_POST['formName'];
      $formIP = $_POST['formIP'];
      $formPerms = $_POST['formPerms'];

      if($_SESSION['userPerms'] > 3) {
        if($creationType == "h") {
          $checkName = $con->prepare("SELECT * FROM hosts WHERE hostName=?");
          $checkName->bind_param("s", $formName);
          $checkName->execute();
          $checkName->store_result();
          if($checkName->num_rows > 0) {
?>
      <p class="alert">that host name is already taken, redirecting...</p>
<?php
            redirect("./create.php?t=h");
          } else {
            $createHost = $con->prepare("INSERT INTO hosts(hostIP,hostName,hostPerms) VALUES(?,?,?)");
            $createHost->bind_param("ssi", $formIP,$formName,$formPerms);
            if($createHost->execute()) {
?>
    <p class="alert">Host successfully created, redirecting...</p>
<?php
                redirect("./admin.php");
            } else {
?>
    <p class="alert">Host unsuccessfully created, redirecting...</p>
<?php
              redirect("./create.php?t=h");
            };
            $createHost->close();
          };
          $checkName->close();
        } else if($creationType == "us") {
          $checkName = $con->prepare("SELECT * FROM machineUsages WHERE machineUsage=?");
          $checkName->bind_param("s", $formName);
          $checkName->execute();
          $checkName->store_result();
          if($checkName->num_rows > 0) {
?>
      <p class="alert">that usage name is already taken, redirecting...</p>
<?php
            redirect("./create.php?t=us");
          } else {
            $createUsage = $con->prepare("INSERT INTO machineUsages(machineUsage) VALUES(?)");
            $createUsage->bind_param("s", $formName);
            if($createUsage->execute()) {
?>
    <p class="alert">Usage successfully created, redirecting...</p>
<?php
                redirect("./admin.php");
            } else {
?>
    <p class="alert">Usage unsuccessfully created, redirecting...</p>
<?php
              redirect("./create.php?t=us");
            };
            $createUsage->close();
          };
          $checkName->close();
        } else {
?>
    <p class="alert">a valid creation type is required, redirecting...</p>
<?php
          redirect("./admin.php");
        };
      } else {
?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
<?php
        redirect("./");
      };
    } else if($action == "promote") {
      if($_SESSION['userPerms'] > 3) {
        if(isset($_GET['id'])) {
          $userID = $_GET['id'];

          $checkID = $users->prepare("SELECT userPerms FROM users WHERE userID=?");
          $checkID->bind_param("i", $userID);
          $checkID->execute();
          $checkID->store_result();
          if($checkID->num_rows > 0) {
            $checkID->bind_result($userPerms);
            while($checkID->fetch()) {
              if($userPerms == 1) {
                $promote = $users->prepare("UPDATE users SET userPerms=4 WHERE userID=?");
                $promote->bind_param("i", $userID);
                if($promote->execute()) {
?>
    <p class="alert">User Promoted to Admin, redirecting...</p>
<?php
                  redirect("./admin.php");
                } else {
?>
    <p class="alert">User could not be Promoted, redirecting...</p>
<?php
                  redirect("./admin.php");
                };
                $promote->close();
              } else if($userPerms == 0) {
                $promote = $users->prepare("UPDATE users SET userPerms=1 WHERE userID=?");
                $promote->bind_param("i", $userID);
                if($promote->execute()) {
?>
    <p class="alert">User Promoted to Normal User, redirecting...</p>
<?php
                  redirect("./admin.php");
                } else {
?>
    <p class="alert">User could not be Promoted, redirecting...</p>
<?php
                  redirect("./admin.php");
                };
                $promote->close();
              };
            };
          } else {
?>
    <p class="alert">invalid user ID, redirecting...</p>
<?php
            redirect("./admin.php");
          };
          $checkID->close();
    } else {
?>
    <p class="alert">a user id is required, redirecting...</p>
<?php
          redirect("./admin.php");
        };
      } else {
?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
<?php
        redirect("./");
      };
    } else if($action == "demote") {
      if($_SESSION['userPerms'] > 3) {
        if(isset($_GET['id'])) {
          $userID = $_GET['id'];

          $checkID = $users->prepare("SELECT userPerms FROM users WHERE userID=?");
          $checkID->bind_param("i", $userID);
          $checkID->execute();
          $checkID->store_result();
          if($checkID->num_rows > 0) {
            $checkID->bind_result($userPerms);
            while($checkID->fetch()) {
              if($userPerms == 4) {
                $demote = $users->prepare("UPDATE users SET userPerms=1 WHERE userID=?");
                $demote->bind_param("i", $userID);
                if($demote->execute()) {
  ?>
      <p class="alert">User Promoted to Normal User, redirecting...</p>
  <?php
                  redirect("./admin.php");
                } else {
  ?>
      <p class="alert">User could not be Demoted, redirecting...</p>
  <?php
                  redirect("./admin.php");
                };
                $demote->close();
              } else if($userPerms == 1) {
                $demote = $users->prepare("UPDATE users SET userPerms=0 WHERE userID=?");
                $demote->bind_param("i", $userID);
                if($demote->execute()) {
  ?>
      <p class="alert">User Promoted to banned User, redirecting...</p>
  <?php
                  redirect("./admin.php");
                } else {
  ?>
      <p class="alert">User could not be Demoted, redirecting...</p>
  <?php
                  redirect("./admin.php");
                };
                $demote->close();
              };
            };
        } else {
?>
    <p class="alert">invalid user ID, redirecting...</p>
<?php
            redirect("./admin.php");
        };
        $checkID->close();
      } else {
?>
    <p class="alert">a user id is required, redirecting...</p>
<?php
          redirect("./admin.php");
        };
      } else {
?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
<?php
        redirect("./");
      };
    } else if($action == "deletebooking") {
        if(isset($_GET['id'])) {
          $bookingID = $_GET['id'];
          $machineID = $_GET['vm'];

          if($_SESSION['userPerms'] > 3) {
            $checkID = $con->prepare("SELECT bookingID FROM bookings WHERE bookingID=?");
            $checkID->bind_param("i", $bookingID);
            $checkID->execute();
            $checkID->store_result();
            if($checkID->num_rows > 0) {
              $checkID->bind_result($bookingID);
              while($checkID->fetch()) {
                $deleteBooking = $con->prepare("DELETE FROM bookings WHERE bookingID=?");
                $deleteBooking->bind_param("i", $bookingID);
                if($deleteBooking->execute()) {
?>
    <p class="alert">Booking successfully deleted, redirecting...</p>
<?php
                  redirect("./view.php?t=m&id=$machineID");
                } else {
?>
    <p class="alert">Booking unsuccessfully deleted, redirecting...</p>
<?php
                  redirect("./view.php?t=m&id=$machineID");
                };
                $deleteBooking->close();
              };
            } else {
?>
    <p class="alert">invalid booking id, redirecting...</p>
<?php
              redirect("./browse.php");
            };
            $checkID->close();
          } else if($_SESSION['userPerms'] < 4) {
            $checkID = $con->prepare("SELECT bookingID FROM bookings WHERE bookingID=? AND userID=?");
            $checkID->bind_param("ii", $bookingID, $_SESSION['userID']);
            $checkID->execute();
            $checkID->store_result();
            if($checkID->num_rows > 0) {
              $checkID->bind_result($bookingID);
              while($checkID->fetch()) {
                $deleteBooking = $con->prepare("DELETE FROM bookings WHERE bookingID=?");
                $deleteBooking->bind_param("i", $bookingID);
                if($deleteBooking->execute()) {
?>
    <p class="alert">Booking successfully deleted, redirecting...</p>
<?php
                  redirect("./browse.php");
                } else {
?>
    <p class="alert">Booking unsuccessfully deleted, redirecting...</p>
<?php
                  redirect("./browse.php");
                };
                $deleteBooking->close();
              };
            } else {
?>
    <p class="alert">invalid booking id, redirecting...</p>
<?php
              redirect("./browse.php");
            };
            $checkID->close();
          } else {
?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
<?php
            redirect("./browse.php");
          };
        } else {
?>
    <p class="alert">a booking id is required, redirecting...</p>
<?php
          redirec("./browse.php");
        };
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
