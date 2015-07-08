<?php include('./config.php'); ?>
<html>
<head>
<?php include('./head.php'); ?>
</head>
<body>
  <?php include('./nav.php'); ?>
  <div class="main wrp">
  <?php
  if(isset($_SESSION['vm_userID'])) {
    if(isset($_GET['t'])) {
      if(isset($_GET['id'])) {
        $type = $_GET['t'];
        $viewID = $_GET['id'];

        if($type == "h") {
          $getHostDetails = $con->prepare("SELECT hostID,hostIP,hostName FROM hosts WHERE hostID=? AND hostPerms=1");
          $getHostDetails->bind_param("i", $viewID);
          $getHostDetails->execute();
          $getHostDetails->store_result();
          if($getHostDetails->num_rows > 0) {
            $getHostDetails->bind_result($hostID,$hostIP,$hostName);
            while($getHostDetails->fetch()) {
?>
<table class="full outline">
  <tr class="head">
    <td colspan="7">
      <p><?php echo $hostName; ?> / <?php echo $hostIP; ?></p>
    </td>
  </tr>

<?php
              $getChildren = $con->prepare("SELECT hostID,machineID,machineIP,machineName FROM machines WHERE hostID=?");
              $getChildren->bind_param("i", $hostID);
              $getChildren->execute();
              $getChildren->store_result();
              if($getChildren->num_rows > 0) {
                $getChildren->bind_result($hostID,$machineID,$machineIP,$machineName);
                while($getChildren->fetch()) {
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

      <p><?php echo $hostName; ?></p>
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
                      $getMachinePurposeName = $con->prepare("SELECT machinePurposeName FROM machinePurposes WHERE machinePurposeID=?");
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
                      $getMachineUsageName = $con->prepare("SELECT machineUsageName FROM machineUsages WHERE machineUsageID=?");
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
      <a href="./view.php?t=m&id=<?php echo $machineID; ?>"><i class="fa fa-fw fa-eye"></i></a>
    </td>
  </tr>
<?php
                };
              } else {
?>
  <tr>
    <td><p class="alert">No Machines Found</p></td>
  </tr>
<?php
              };
              $getChildren->close();
?>
</table>
<?php
            };
          } else {
?>
    <p class="alert">invalid host id, redirecting...</p>
<?php
            redirect("./browse.php");
          };
          $getHostDetails->close();
        } else if($type == "m") {
          $getMachine = $con->prepare("SELECT machineID,machineIP,machineName,hostID FROM machines WHERE machineID=? AND machinePerms=1");
          $getMachine->bind_param("i", $viewID);
          $getMachine->execute();
          $getMachine->store_result();
          if($getMachine->num_rows > 0) {
            $getMachine->bind_result($machineID,$machineIP,$machineName,$hostID);
            while($getMachine->fetch()) {
?>
<div class="clr">
  <h1><?php echo $machineName; ?> / <?php echo $machineIP; ?></h1>
</div>
<div class="clr">

</div>
<?php
            };
          } else {
?>
    <p class="alert">invalid machine id, redirecting...</p>
<?php
            redirect("./browse.php");
          };
          $getMachine->close();
        } else {
?>
    <p class="alert">a valid view type is required, redirecting...</p>
<?php
      redirect("./browse.php");
        };
      } else {
  ?>
    <p class="alert">an id is required, redirecting...</p>
  <?php
        redirect("./browse.php");
      };
    } else {
  ?>
    <p class="alert">a view type is required, redirecting...</p>
  <?php
      redirect("./browse.php");
    };
  } else {
  ?>
    <p class="alert">you need to be logged in to vew this page, redirecting...</p>
  <?php
    redirect("./login.php");
  };
  ?>
  </div>
</body>
</html>
