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
?>
    <div class="clr mrg-btm-x-lrg">
      <h1>Admin Control Panel</h1>
    </div>
    <div class="admin-controls clr mrg-btm">
      <table>
        <tr>
          <td>
            <p>Create: </p>
          </td>
          <td>
            <a class="btn btn-grp" href="./create.php?t=h">Host</a>
            <a class="btn btn-grp" href="./create.php?t=us">Usage</a>
            <a class="btn btn-grp" href="./register.php">User</a>
          </td>
          <td>
            <p>View: </p>
          </td>
          <td>
            <a class="btn btn-grp" href="./admin.php?v=h">Hosts</a>
            <a class="btn btn-grp" href="./admin.php?v=m">Machines</a>
            <a class="btn btn-grp" href="./admin.php?v=u">Users</a>
            <a class="btn btn-grp" href="./admin.php">Default</a>
          </td>
        </tr>
      </table>
    </div>
<?php
  if(!isset($_GET['v']) || $_GET['v'] == "h") {
?>
    <table class="full outline mrg-btm-med">
      <tr class="head">
        <td colspan="3"><p>Hosts</p></td>
      </tr>
<?php
        $listHosts = $con->prepare("SELECT hostID,hostIP,hostName FROM hosts");
        $listHosts->execute();
        $listHosts->store_result();
        if($listHosts->num_rows > 0) {
          $listHosts->bind_result($hostID,$hostIP,$hostName);
          while($listHosts->fetch()) {
?>
      <tr>
        <td class="fixed-100">
          <a href="./view.php?t=h&id=<?php echo $hostID; ?>"><?php echo $hostIP; ?></a>
        </td>
        <td>
          <a href="./view.php?t=h&id=<?php echo $hostID; ?>"><?php echo $hostName; ?></a>
        </td>
        <td class="fixed-100 options">
          <a href="./edit.php?t=h&id=<?php echo $hostID; ?>"><i class="fa fa-fw fa-wrench"></i></a>
          <a href="./view.php?t=h&id=<?php echo $hostID; ?>"><i class="fa fa-fw fa-eye"></i></a>
        </td>
      </tr>
<?php
          };
        } else {
?>
      <tr><td><p class="alert">No Hosts Found</p></td></tr>
<?php
        };
$listHosts->close();
?>
    </table>
<?php
  };
  if(!isset($_GET['v']) || $_GET['v'] == "m") {
?>
    <table class="full outline mrg-btm-med">
      <tr class="head">
        <td colspan="7"><p>Machines</p></td>
      </tr>
<?php
        $listMachines = $con->prepare("SELECT machineID,machineIP,machineName,hostID FROM machines");
        $listMachines->execute();
        $listMachines->store_result();
        if($listMachines->num_rows > 0) {
          $listMachines->bind_result($machineID,$machineIP,$machineName,$hostID);
          while($listMachines->fetch()) {
?>
      <tr>
        <td class="fixed-100">
<?php
            $getHostName = $con->prepare("SELECT hostName FROM hosts WHERE hostID=?");
            $getHostName->bind_param("i", $hostID);
            $getHostName->execute();
            $getHostName->store_result();
            $getHostName->bind_result($hostName);
            while($getHostName->fetch()) {
?>

          <a href="./view.php?t=h&id=<?php echo $hostID; ?>"><?php echo $hostName; ?></a>

<?php
            };
            $getHostName->close();
  ?>
        </td>
        <td>
          <a href="./view.php?t=m&id=<?php echo $machineID; ?>"><?php echo $machineIP; ?></a>
        </td>
        <td>
          <a href="./view.php?t=m&id=<?php echo $machineID; ?>"><?php echo $machineName; ?></a>
        </td>
<?php
            $getMachineDetails = $con->prepare("SELECT machineOS,machinePurpose,machineUsage FROM machinedetails WHERE machineID=?");
            $getMachineDetails->bind_param("i", $machineID);
            $getMachineDetails->execute();
            $getMachineDetails->store_result();
            $getMachineDetails->bind_result($machineosID,$machinePurposeID,$machineUsageID);
            while($getMachineDetails->fetch()) {
?>
        <td>
<?php
              if($machineosID != NULL) {
                $getMachineOSName = $con->preparE("SELECT machineosName FROM machineos WHERE machineosID=?");
                $getMachineOSName->bind_param("i", $machineosID);
                $getMachineOSName->execute();
                $getMachineOSName->store_result();
                $getMachineOSName->bind_result($machineosName);
                while($getMachineOSName->fetch()) {
?>
          <p><?php echo $machineosName; ?></p>
<?php
                };
                $getMachineOSName->close();
              } else {
?>
          <p class="danger">Undefined</p>
<?php
              };
?>
        </td>
        <td>
<?php
              if($machinePurposeID != NULL) {
                $getMachinePurposeName = $con->prepare("SELECT machinePurpose FROM machinePurposes WHERE purposeID=?");
                $getMachinePurposeName->bind_param("i", $machinePurposeID);
                $getMachinePurposeName->execute();
                $getMachinePurposeName->store_result();
                $getMachinePurposeName->bind_result($machinePurposeName);
                while($getMachinePurposeName->fetch()) {
?>
          <p><?php echo $machinePurposeName; ?></p>
<?php
                };
                $getMachinePurposeName->close();
              } else {
?>
          <p class="danger">Undefined</p>
<?php
              };
?>
        </td>
        <td>
<?php
              if($machineUsageID != NULL) {
                $getMachineUsageName = $con->prepare("SELECT machineUsage FROM machineUsages WHERE usageID=?");
                $getMachineUsageName->bind_param("i", $machineUsageID);
                $getMachineUsageName->execute();
                $getMachineUsageName->store_result();
                $getMachineUsageName->bind_result($machineUsageName);
                while($getMachineUsageName->fetch()) {
  ?>
          <p><?php echo $machineUsageName; ?></p>
  <?php
                };
                $getMachineUsageName->close();
              } else {
?>
          <p class="danger">Undefined</p>
<?php
              };
?>
        </td>
<?php
            };
            $getMachineDetails->close();
?>
        <td class="fixed-100 options">
          <a href="./edit.php?t=m&id=<?php echo $machineID; ?>"><i class="fa fa-fw fa-wrench"></i></a>
          <a href="./view.php?t=m&id=<?php echo $machineID; ?>"><i class="fa fa-fw fa-eye"></i></a>
        </td>
      </tr>
<?php
          };
        } else {
?>
          <tr><td><p class="alert">No Machines Found</p></td></tr>
<?php
        };
        $listMachines->close();
?>
    </table>
<?php
  };
  if(!isset($_GET['v']) || $_GET['v'] == "u") {
?>
    <table class="full outline">
      <tr class="head">
        <td colspan="4">
          <p>Users</p>
        </td>
      </tr>
<?php
        $getUsers = $con->prepare("SELECT userID,userName,userFirst,userLast,userEmail,userPerms FROM users");
        $getUsers->execute();
        $getUsers->store_result();
        if($getUsers->num_rows > 0) {
          $getUsers->bind_result($userID,$userName,$userFirst,$userLast,$userEmail,$userPerms);
          while($getUsers->fetch()) {
?>
      <tr>
        <td><p><?php echo $userName; ?></p></td>
        <td><p><?php echo $userFirst. ' ' .$userLast; ?></p></td>
        <td><a href="mailto:<?php echo $userEmail; ?>"><?php echo $userEmail; ?></a></td>
        <td class="fixed-100 options">
<?php
            if($userPerms != 5) {
?>
          <a href="./edit.php?t=u&id=<?php echo $userID; ?>"><i class="fa fa-fw fa-wrench"></i></a>
<?php
              if($userPerms == 4) {
?>
          <a href="./action.php?a=demote&id=<?php echo $userID; ?>"><i class="fa fa-fw fa-angle-double-down"></i></a>
<?php
              };
              if($userPerms < 4 && $userPerms > 0) {
?>
          <a href="./action.php?a=promote&id=<?php echo $userID; ?>"><i class="fa fa-fw fa-angle-double-up"></i></a>
          <a href="./action.php?a=demote&id=<?php echo $userID; ?>"><i class="fa fa-fw fa-angle-double-down"></i></a>
<?php
              };
              if($userPerms == 0) {
?>
          <a href="./action.php?a=promote&id=<?php echo $userID; ?>"><i class="fa fa-fw fa-angle-double-up"></i></a>
<?php
              };
            };
?>
        </td>
      </tr>
<?php
          };
        } else {
?>
      <tr><td><p class="alert">No Users Found</p></td></tr>
<?php
        };
        $getUsers->close();
?>
    </table>
<?php
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
