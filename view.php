<?php include('./config.php'); ?>
<html>
<head>
<?php
  include('./head.php');

  //Labels
$dayLabels 			= array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
$dayMiniLabels		= array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
$monthLables 		= array("January","February","March","April","May","June","July","August","September","October","November","December");

$forceMonth = $_GET['m'];
$forceYear = $_GET['y'];

$currentDate			= date("Y-m-d");
$explodeDate		= explode("-", $currentDate);

//Currents
if(isset($forceMonth)) {
  if(strlen($forceMonth) == 1) {
    $forceMonth 	= sprintf("%02d", $forceMonth);
  };
  $currentMonth	= $forceMonth;
} else {
  $currentMonth	= date("m");
};

if(isset($forceYear)) {
  if(strlen($forceYear) == 2) {
    $dt 				= DateTime::createFromFormat('y', $forceYear);
    $forceYear 	= $dt->format('Y');
  };
  $currentYear		= $forceYear;
} else {
  $currentYear		= date("Y");
};

//variables
$monthStart 			= date($currentYear. '-' .$currentMonth. '-01');
$monthEnd   			= date($currentYear. '-' .$currentMonth. '-t');

$prevMonth 			= sprintf("%02d", $currentMonth - 1);
$nextMonth 			= sprintf("%02d", $currentMonth + 1);
$prevYear 			= sprintf("%02d", $currentYear - 1);
$nextYear 			= sprintf("%02d", $currentYear + 1);

$daysInMonth 		= cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
$firstDayofMonth	= date("D", strtotime("01-$currentMonth-$currentYear"));
$firstDayofMonth	= array_search($firstDayofMonth, $dayMiniLabels);
$firstDayofMonth	= $firstDayofMonth;

if($firstDayofMonth == 0) {
  $firstDayofMonth = 7;
};

$bookings 			= array();
$s 						= new DateTime($monthStart);
$e 						= new DateTime("$monthEnd + 1 days");
$oneday 				= new DateInterval('P1D');
$dp 						= new DatePeriod($s, $oneday, $e);

foreach ($dp as $d) {
  $bookings[$d->format('Y-m-d')] = '';
};

$sql = "SELECT userID
     , bookingStart
     , bookingEnd
     , projectNumber
    FROM bookings
    WHERE machineID = ?
      AND bookingStart <= ?
      AND bookingEnd >= ?";
$getBookings = $con->prepare($sql);
$getBookings->bind_param('iss', $_GET['id'], $monthEnd, $monthStart);
$getBookings->execute();
$getBookings->bind_result($uid, $bstart, $bend, $projectNumber);

while ($getBookings->fetch()) {
  $s 					= new DateTime(max($bstart, $monthStart));
  $e 					= new DateTime(min($bend, $monthEnd));
  $e->modify('+1 day');
  $dp = new DatePeriod($s, $oneday, $e);
  foreach ($dp as $d) {
    $bookings[$d->format('Y-m-d')]['user'] = $uid;
    $bookings[$d->format('Y-m-d')]['project'] = $projectNumber;
  };
};

//counters
$dayCount 	= 0;
$startMonth 	= 0;
$calDate 		= 0;

?>
<script>
  $(document).ready(function() {
    var toggler = $('a.date-bookNow');
    var innerToggle = $('form a.date-closeNow');

    toggler.on('click', function() {
      $(this).siblings('form').toggle();
    });

    innerToggle.on('click', function() {
      $(this).parent().parent().toggle();
    });
  });
</script>
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
<div class="clr">
  <h1 class="mrg-btm-x-lrg"><?php echo $hostName; ?> / <?php echo $hostIP; ?></h1>
</div>
<table class="full outline">
  <tr class="head">
    <td colspan="7">
      <p>Machines</p>
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
              if($hostID != NULL) {
                $getHost = $con->prepare("SELECT hostID,hostIP,hostName FROM hosts WHERE hostID=?");
                $getHost->bind_param("i", $hostID);
                $getHost->execute();
                $getHost->store_result();
                $getHost->bind_result($hostID,$hostIP,$hostName);
                while($getHost->fetch()) {
                  $hostID = $hostID;
                  $hostIP = $hostIP;
                  $hostName = $hostName;
                };
                $getHost->close();
              };

              $getMachineDetails = $con->prepare("SELECT machineMemory,machineDiskSpace,machineCores,machinePurpose,machineUsage,machineOS FROM machinedetails WHERE machineID=?");
              $getMachineDetails->bind_param("i", $machineID);
              $getMachineDetails->execute();
              $getMachineDetails->store_result();
              $getMachineDetails->bind_result($machineMemory,$machineDiskSpace,$machineCores,$machinePurpose,$machineUsage,$machineOS);
              while($getMachineDetails->fetch()) {
                $machineMemory = $machineMemory;
                $machineDiskSpace = $machineDiskSpace;
                $machineCores = $machineCores;
                $machinePurpose = $machinePurpose;
                $machineUsage = $machineUsage;
                $machineOS = $machineOS;
              };
              $getMachineDetails->close();

              if($machineOS != NULL) {
                $getMachineOS = $con->prepare("SELECT machineosName FROM machineos WHERE machineosID=?");
                $getMachineOS->bind_param("i", $machineOS);
                $getMachineOS->execute();
                $getMachineOS->store_result();
                $getMachineOS->bind_result($machineosName);
                while($getMachineOS->fetch()) {
                  $machineosName = $machineosName;
                };
                $getMachineOS->close();
              };

              if($machinePurpose != NULL) {
                $getMachinePurpose = $con->prepare("SELECT machinePurposeName FROM machinePurposes WHERE machinePurposeID=?");
                $getMachinePurpose->bind_param("i", $machinePurpose);
                $getMachinePurpose->execute();
                $getMachinePurpose->store_result();
                $getMachinePurpose->bind_result($machinePurposeName);
                while($getMachinePurpose->fetch()) {
                  $machinePurposeName = $machinePurposeName;
                };
                $getMachinePurpose->close();
              };

              if($machineUsage != NULL) {
                $getMachineUsage = $con->prepare("SELECT machineUsageName FROM machineUsages WHERE machineUsageID=?");
                $getMachineUsage->bind_param("i", $machineUsage);
                $getMachineUsage->execute();
                $getMachineUsage->store_result();
                $getMachineUsage->bind_result($machineUsageName);
                while($getMachineUsage->fetch()) {
                  $machineUsageName = $machineUsageName;
                };
                $getMachineUsage->close();
              };
?>
<div class="clr mrg-btm-x-lrg">
  <h1><?php echo $machineName; ?> / <?php echo $machineIP; ?></h1>
</div>
<div class="clr mrg-btm-x-lrg">
  <table class="fixed full outline">
    <tr class="head">
      <td colspan="6"><p>Machine Details</p></td>
    </tr>
    <tr>
      <td><p><b>Host IP</b></p></td>
      <td>
<?php
              if($hostID != NULL) {
?>
        <a href="./view.php?t=h&id=<?php echo $hostID; ?>"><?php echo $hostIP; ?></a>
<?php
              } else {
?>
        <p class="danger">Undefined</p>
<?php
              };
?>
      </td>

      <td><p><b>Machine OS</b></p></td>
      <td>
<?php
              if($machineosName != NULL) {
?>
        <p><?php echo $machineosName; ?></p>
<?php
              } else {
?>
        <p class="danger">Undefined</p>
<?php
              };
?>
      </td>

      <td><p><b>Machine Memory</b></p></td>
      <td><p><?php echo $machineMemory; ?> MB</p></td>
    </tr>
    <tr>
      <td><p><b>Machine IP</b></p></td>
      <td><p><?php echo $machineIP; ?></p></td>

      <td><p><b>Machine Purpose</b></p></td>
      <td>
<?php
              if($machinePurposeName != NULL) {
?>
        <p><?php echo $machinePurposeName; ?></p>
<?php
              } else {
?>
        <p class="danger">Undefined</p>
<?php
              };
?>
      </td>

      <td><p><b>Machine Disk Space</b></p></td>
      <td><p><?php echo $machineDiskSpace; ?> GB</p></td>
    </tr>
    <tr>
      <td><p><b>Machine Name</b></p></td>
      <td><p><?php echo $machineName; ?></p></td>

      <td><p><b>Machine Usage</b></p></td>
      <td>
<?php
              if($machineUsageName != NULL) {
?>
        <p><?php echo $machineUsageName; ?></p>
<?php
              } else {
?>
        <p class="danger">Undefined</p>
<?php
              };
?>
      </td>

      <td><p><b>Machine Cores</b></p></td>
      <td><p><?php echo $machineCores; ?></p></td>
    </tr>
  </table>
</div>
<?php
            };
?>
<div id="date" class="date-controls clr">
  <table class="mrg-btm-med">
    <tr>
      <td><p class="month"><?php echo $monthLables[$currentMonth - 1]; ?></p></td>
      <td><p><?php echo $currentMonth. '-' .$currentYear ?></p></td>
      <td>
        <a class="btn btn-grp fa fa-angle-double-left" href="./view.php?t=m&id=<?php echo $machineID; ?>&m=<?php echo $currentMonth; ?>&y=<?php echo $prevYear; ?>#date" title="Prev Year"></a>
        <a class="btn btn-grp fa fa-angle-left"
        <?php
          if($prevMonth == 0) {
            echo 'href="./view.php?t=m&id=' .$machineID. '&m=12&y=' .$prevYear. '#date" ';
          } else {
            echo 'href="./view.php?t=m&id=' .$machineID. '&m=' .$prevMonth. '&y=' .$currentYear. '#date" ';
          };
        ?>
        title="Prev Month"></a>
        <a class="btn btn-grp fa fa-home" href="./view.php?t=m&id=<?php echo $machineID; ?>&m=<?php echo $explodeDate[1]; ?>&y=<?php echo $explodeDate[0]; ?>#date" title="Current Date"></a>
        <a class="btn btn-grp fa fa-angle-right"
        <?php
          if($nextMonth == 13) {
            echo 'href="./view.php?t=m&id=' .$machineID. '&m=01&y=' .$nextYear. '#date" ';
          } else {
            echo 'href="./view.php?t=m&id=' .$machineID. '&m=' .$nextMonth. '&y=' .$currentYear. '#date" ';
          };
        ?>
        title="Next Month"></a>
        <a class="btn btn-grp fa fa-angle-double-right" href="./view.php?t=m&id=<?php echo $machineID; ?>&m=<?php echo $currentMonth; ?>&y=<?php echo $nextYear; ?>#date" title="Next Year"></a>
      </td>
    </tr>
  </table>
</div>
<table class="date-days fixed full">
  <tr class="head">
  <?php
    foreach($dayLabels as $day) {
      echo '<td class="day"><p>' .$day. '</p></td>';
    };
  ?>
  </tr>
</table>
<table id="calendar" class="date-calendar fixed full">
  <?php
    foreach($bookings as $key=>$date) {
      $dayCount++;
      $calDate++;
      $calDate = sprintf("%02d", $calDate);

      if($dayCount == 1) {
        echo '<tr>';
      };

      if($firstDayofMonth != 7) {
        while($startMonth < $firstDayofMonth) {
          echo '<td></td>';
          $startMonth++;
          $dayCount++;
          $temp_dayCount = sprintf("%02d", $dayCount);
          $dayCount = $temp_dayCount;
        };
      };
?>
  <script>
    $(document).ready(function() {
      console.log("<?php echo $key; ?> : <?php echo $date; ?>");
    });
  </script>
<?php
      if($date != "") {
        if($getUserName = $con->prepare("SELECT userFirst,userLast,userEmail FROM users WHERE userID=?")) {
          $getUserName->bind_param("i", $date['user']);
          $getUserName->execute();
          $getUserName->bind_result($userFirst,$userLast,$userEmail);
          while($getUserName->fetch()) {
            echo '
              <td class="date booked">
                <p>' .$calDate. '</p>
                <p class="mrg-top">' .$userFirst. ' ' .$userLast. '</p>
                <p class="mrg-top">' .$userEmail. '</p>
              </td>
            ';
          };
        };
        $getUserName->close();
      } else {
?>
<td class="date">
  <p><?php echo $calDate; ?></p>
  <a class="date-bookNow"></a>
  <form class="date-book" method="post" action="./action.php?a=book">
    <input name="userID" type="hidden" value="<?php echo $_SESSION['vm_userID']; ?>" required />
    <input name="machineID" type="hidden" value="<?php echo $machineID; ?>" required />

    <div class="clr mrg-btm-med options">
      <a class="date-closeNow"><i class="fa fa-fw fa-close"></i></a>
    </div>
    <table class="full">
      <tr>
        <td colspan="2">
          <h2>Book Dates</h2>
        </td>
      </tr>
      <tr>
        <td><p>Start Date</p></td>
        <td>
          <input name="formStart" type="text" value="<?php echo $calDate. '-' .$currentMonth. '-' .$currentYear; ?>" required />
        </td>
      </tr>
      <tr>
        <td><p>End Date</p></td>
        <td>
          <input name="formEnd" type="text" value="<?php echo $calDate. '-' .$currentMonth. '-' .$currentYear; ?>" required />
        </td>
      </tr>
      <tr>
        <td></td>
        <td><button class="confirm btn-default">Submit</button></td>
      </tr>
      <tr>
        <td></td>
        <td><p class="subtitle">Make sure dates are dd-mm-yyy!</p>
      </tr>
    </table>
  </form>
</td>
<?php
      };

      if($dayCount == 7) {
        echo '</tr>';
        $dayCount = 0;
      };
    };
  ?>
</table>
<div class="clr mrg-top-x-lrg">
  <table class="full outline">
    <tr class="head">
      <td colspan="4"><p>Bookings</p></td>
    </tr>
<?php
  $getBookings = $con->prepare("SELECT userID,bookingStart,bookingEnd FROM bookings WHERE machineID=?");
  $getBookings->bind_param("i", $machineID);
  $getBookings->execute();
  $getBookings->store_result();
  if($getBookings->num_rows > 0) {
    $getBookings->bind_result($userID,$bookingStart,$bookingEnd);
    while($getBookings->fetch()) {
      $getUserDetails = $con->prepare("SELECT userFirst,userLast,userEmail FROM users WHERE userID=?");
      $getUserDetails->bind_param("i", $userID);
      $getUserDetails->execute();
      $getUserDetails->store_result();
      $getUserDetails->bind_result($userFirst,$userLast,$userEmail);
      while($getUserDetails->fetch()) {
?>
    <tr>
      <td><p><?php echo $userFirst. ' ' .$userLast; ?></p></td>
      <td><a class="mailto:<?php echo $userEmail; ?>"><?php echo $userEmail; ?></a></td>
      <td><p><?php echo $bookingStart; ?> => <?php echo $bookingEnd; ?></p></td>
      <td class="fixed-100 options">

      </td>
    </tr>
<?php
      };
      $getUserDetails->close();
    };
  } else {
?>
    <tr>
      <td><p class="alert">No Bookings Found</p></td>
    </tr>
<?php
  };
  $getBookings->close();
?>
  </table>
</div>
<?php
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
