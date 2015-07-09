<?php include('./config.php'); ?>
<html>
<head>
<?php include('./head.php'); ?>
</head>
<body>
  <?php include('./nav.php'); ?>
  <div class="main wrp">
<?php
  if($_SESSION['vm_userPerms'] > 3) {
    if(isset($_GET['t'])) {
      if(isset($_GET['id'])) {
        $editType = $_GET['t'];
        $editID = $_GET['id'];

        if($editType == "h") {
          $checkID = $con->prepare("SELECT * FROM hosts WHERE hostID=?");
          $checkID->bind_param("i", $editID);
          $checkID->execute();
          $checkID->store_result();
          if($checkID->num_rows > 0) {
            $getHost = $con->prepare("SELECT hostID,hostIP,hostName,hostPerms FROM hosts WHERE hostID=?");
            $getHost->bind_param("i", $editID);
            $getHost->execute();
            $getHost->store_result();
            $getHost->bind_result($hostID,$hostIP,$hostName,$hostPerms);
            while($getHost->fetch()) {
              $hostID = $hostID;
              $hostIP = $hostIP;
              $hostName = $hostName;
              $hostPerms = $hostPerms;
            };
            $getHost->close();
?>
    <form method="post" action="./action.php?a=edit">
      <input name="formID" type="hidden" value="<?php echo $hostID; ?>" required />
      <input name="editType" type="hidden" value="<?php echo $editType; ?>" required />
      <h1 class="mrg-btm-x-lrg">Edit <?php echo $hostName; ?> / <?php echo $hostIP; ?></h1>
      <table class="fixed">
        <tr>
          <td><p>Edit Host Name</p></td>
          <td><input name="formName" type="text" value="<?php echo $hostName; ?>" placeholder="Host Name" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Edit Host IP</p></td>
          <td><input name="formIP" type="text" value="<?php echo $hostIP; ?>" placeholder="Host IP" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Edit Visibility</p></td>
          <td>
            <select name="formPerms" required>
              <option disabled>Select an Option</option>
<?php
            if($hostPerms == 1) {
?>
              <option value="1" selected>Visible</option>
<?php
            } else {
?>
              <option value="1">Visible</option>
<?php
            };
            if($hostPerms == 0) {
?>
              <option value="0" selected>Not Visible</option>
<?php
            } else {
?>
              <option value="0">Not Visible</option>
<?php
            };
?>
            </select>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><button class="btn-warning confirm" type="submit">Submit</button></td>
        </tr>
      </table>
    </form>
<?php
          } else {
?>
    <p class="alert">invalid host id, redirecting...</p>
<?php
            redirect("./admin.php");
          };
          $checkID->close();
        } else if($editType == "m") {
          $checkID = $con->prepare("SELECT * FROM machines WHERE machineID=?");
          $checkID->bind_param("i", $editID);
          $checkID->execute();
          $checkID->store_result();
          if($checkID->num_rows > 0) {
            $getMachine = $con->prepare("SELECT machineID,machineIP,machineName,hostID,machinePerms FROM machines WHERE machineID=?");
            $getMachine->bind_param("i", $editID);
            $getMachine->execute();
            $getMachine->store_result();
            $getMachine->bind_result($machineID,$machineIP,$machineName,$machineHostID,$machinePerms);
            while($getMachine->fetch()) {
              $machineID = $machineID;
              $machineHostID = $machineHostID;
              $machinePerms = $machinePerms;
            };
            $getMachine->close();

            $getMachineDetails = $con->prepare("SELECT machinePurpose,machineUsage FROM machinedetails WHERE machineID=?");
            $getMachineDetails->bind_param("i", $machineID);
            $getMachineDetails->execute();
            $getMachineDetails->store_result();
            $getMachineDetails->bind_result($machinePurposeID,$machineUsageID);
            while($getMachineDetails->fetch()) {
              $machinePurposeID = $machinePurposeID;
              $machineUsageID = $machineUsageID;
            };
            $getMachineDetails->close();
?>
    <form method="post" action="./action.php?a=edit">
      <input name="formID" type="hidden" value="<?php echo $machineID; ?>" required />
      <input name="editType" type="hidden" value="<?php echo $editType; ?>" required />
      <h1 class="mrg-btm-x-lrg">Edit <?php echo $machineName; ?> / <?php echo $machineIP; ?></h1>
      <table class="fixed">
        <tr>
          <td><p>Edit Machine Host</p></td>
          <td>
            <select name="formHost" required />
<?php
            if($machineHostID == NULL) {
?>
            <option selected disabled>Select a Host</option>
<?php
            } else {
?>
            <option disabled>Select a Host</option>
<?php
            };
            $getHosts = $con->prepare("SELECT hostID,hostIP,hostName FROM hosts WHERE hostPerms=1");
            $getHosts->execute();
            $getHosts->store_result();
            $getHosts->bind_result($hostID,$hostIP,$hostName);
            while($getHosts->fetch()) {
              if($hostID == $machinePurposeID) {
?>
              <option value="<?php echo $hostID; ?>" selected><?php echo $hostName; ?> / <?php echo $hostIP; ?></option>
<?php
              } else {
?>
              <option value="<?php echo $hostID; ?>"><?php echo $hostName; ?> / <?php echo $hostIP; ?></option>
<?php
              };
            };
            $getHosts->close();
?>
            </select>
          </td>
        </tr>
        <tr>
          <td><p>Edit Machine Purpose</p></td>
          <td>
            <select name="formPurpose" required>
<?php
            if($machinePurposeID == NULL) {
?>
            <option selected disabled>Select a Purpose</option>
<?php
            } else {
?>
            <option disabled>Select a Purpose</option>
<?php
            };
            $getPurposes = $con->prepare("SELECT purposeID,machinePurpose FROM machinepurposes WHERE machineID=?");
            $getPurposes->bind_param("i", $editID);
            $getPurposes->execute();
            $getPurposes->store_result();
            $getPurposes->bind_result($purposeID,$purposeName);
            while($getPurposes->fetch()) {
              if($purposeID == $machinePurposeID) {
?>
              <option value="<?php echo $purposeID; ?>" selected><?php echo $purposeName; ?></option>
<?php
              } else {
?>
              <option value="<?php echo $purposeID; ?>"><?php echo $purposeName; ?></option>
<?php
              };
            };
            $getPurposes->close();
?>
            </select>
          </td>
        </tr>
        <tr>
          <td><p>Edit Machine Usage</p></td>
          <td>
            <select name="formUsage" required>
<?php
            if($machinePurposeID == NULL) {
?>
            <option selected disabled>Select a Usage</option>
<?php
            } else {
?>
            <option disabled>Select a Usage</option>
<?php
            };
            $getUsages = $con->prepare("SELECT usageID,machineUsage FROM machineUsages");
            $getUsages->bind_param("i", $editID);
            $getUsages->execute();
            $getUsages->store_result();
            $getUsages->bind_result($usageID,$usageName);
            while($getUsages->fetch()) {
              if($usageID == $machineUsageID) {
?>
              <option value="<?php echo $usageID; ?>" selected><?php echo $usageName; ?></option>
<?php
              } else {
?>
              <option value="<?php echo $usageID; ?>"><?php echo $usageName; ?></option>
<?php
              };
            };
            $getUsages->close();
?>
            </select>
          </td>
        </tr>
        <tr>
          <td><p>Edit Visibility</p></td>
          <td>
            <select name="formPerms" required>
              <option disabled>Select an Option</option>
<?php
            if($machinePerms == 1) {
?>
              <option value="1" selected>Visible</option>
<?php
            } else {
?>
              <option value="1">Visible</option>
<?php
            };
            if($machinePerms == 0) {
?>
              <option value="0" selected>Not Visible</option>
<?php
            } else {
?>
              <option value="0">Not Visible</option>
<?php
            };
?>
            </select>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><button class="btn-warning confirm">Submit</button></td>
        </tr>
      </table>
    </form>
<?php
          } else {
?>
    <p class="alert">invalid machine id, redirecting...</p>
<?php
            redirect("./admin.php");
          };
          $checkID->close();
        } else if($editType == "u") {
          $checkID = $con->prepare("SELECT userID,userName,userFirst,userLast,userEmail FROM users WHERE userID=?");
          $checkID->bind_param("i", $editID);
          $checkID->execute();
          $checkID->store_result();
          if($checkID->num_rows > 0) {
            $checkID->bind_result($userID,$userName,$userFirst,$userLast,$userEmail);
            while($checkID->fetch()) {
              $userID = $userID;
              $userName = $userName;
              $userFirst = $userFirst;
              $userLast = $userLast;
              $userEmail = $userEmail;
            };
?>
    <form class="mrg-btm-x-lrg" method="post" action="./action.php?a=edit">
      <input name="formID" type="hidden" value="<?php echo $userID; ?>" required />
      <input name="editType" type="hidden" value="<?php echo $editType; ?>" required />
      <h1 class="mrg-btm-x-lrg">Edit <?php echo $userName; ?> / <?php echo $userFirst; ?> <?php echo $userLast; ?></h1>
      <table class="fixed">
        <tr>
          <td><p>Edit Username</p></td>
          <td><input name="formName" type="text" value="<?php echo $userName; ?>" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Edit First Name(s)</p></td>
          <td><input name="formFirst" type="text" value="<?php echo $userFirst; ?>" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Edit Last Name(s)</p></td>
          <td><input name="formLast" type="text" value="<?php echo $userLast; ?>" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Edit Email Address</p></td>
          <td><input name="formEmail" type="email" value="<?php echo $userEmail; ?>" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td></td>
          <td><button class="btn-warning confirm">Submit</button></td>
        </tr>
      </table>
    </form>
    <div class="clr">
      <form class="mrg-btm-x-lrg" method="post" action="./action.php?a=edit">
        <input name="formID" type="hidden" value="<?php echo $userID; ?>" required />
        <input name="editType" type="hidden" value="up" required />
        <table class="fixed">
          <tr>
            <td><p>New Password</p></td>
            <td><input name="formPass" type="password" placeholder="Password" autocomplete="off" autofocus required /></td>
          </tr>
          <tr>
            <td><p>Retype Password</p></td>
            <td><input name="rePass" type="password" placeholder="Retype Password" autocomplete="off" autofocus required /></td>
          </tr>
          <tr>
            <td></td>
            <td><button class="btn-warning confirm">Submit</button></td>
          </tr>
        </table>
      </form>
  </div>
<?php
          } else {
?>
    <p class="alert">invalid user id, redirecting...</p>
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
    <p class="alert">an edit id is reuqired, redirecting...</p>
<?php
        redirect("./admin.php");
      };
    } else {
?>
    <p class="alert">an edit type is required, redirecting...</p>
<?php
      redirect("./admin.php");
    };
  } else {
?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
<?php
    redirect("./");
  };
?>
  </div>
</body>
</html>
