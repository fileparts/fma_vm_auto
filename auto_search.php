<?php
	include("./config.php");

	$needle = strtolower($_POST['input']);
	$found = false;

	if(strlen($needle) > 0) {
		$byMachineName = $con->prepare("SELECT hostID,machineID,machineIP,machineName FROM machines WHERE machineName LIKE '%$needle%'");
		$byMachineName->execute();
		$byMachineName->store_result();
		if($byMachineName->num_rows > 0) {
			$found = true;

			$byMachineName->bind_result($hostID,$machineID,$machineIP,$machineName);
			while($byMachineName->fetch()) {
?>
	<tr>
		<td class="fixed-100">
<?php
				if($hostID != NULL) {
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
				} else {
?>
			<p class="danger">Undefined</p>
<?php
				};
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
					$getMachineosName = $con->prepare("SELECT machineosName FROM machineos WHERE machineosID=?");
					$getMachineosName->bind_param("i", $machineosID);
					$getMachineosName->execute();
					$getMachineosName->store_result();
					$getMachineosName->bind_result($machineosName);
					while($getMachineosName->fetch()) {
?>
			<p><?php echo $machineosName; ?></p>
<?php
					};
					$getMachineosName->close();
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
			$found = false;
		};
		$byMachineName->close();

		if($found == false) {
			$byMachineIP = $con->prepare("SELECT hostID,machineID,machineIP,machineName FROM machines WHERE machineIP LIKE '%$needle%'");
			$byMachineIP->execute();
			$byMachineIP->store_result();
			if($byMachineIP->num_rows > 0) {
				$found = true;

				$byMachineIP->bind_result($hostID,$machineID,$machineIP,$machineName);
				while($byMachineIP->fetch()) {
	?>
		<tr>
			<td class="fixed-100">
	<?php
					if($hostID != NULL) {
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
					} else {
	?>
				<p class="danger">Undefined</p>
	<?php
					};
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
						$getMachineosName = $con->prepare("SELECT machineosName FROM machineos WHERE machineosID=?");
						$getMachineosName->bind_param("i", $machineosID);
						$getMachineosName->execute();
						$getMachineosName->store_result();
						$getMachineosName->bind_result($machineosName);
						while($getMachineosName->fetch()) {
	?>
				<p><?php echo $machineosName; ?></p>
	<?php
						};
						$getMachineosName->close();
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
				$found = false;
			};
			$byMachineIP->close();
		};

		if($found == false) {
?>
	<tr>
		<td colspan="7">
			<p class="alert">Nothing Found...</p>
		</td>
	</tr>
<?php
		};
	} else {
?>
	<tr>
		<td colspan="7">
			<p class="alert">Search Something...</p>
		</td>
	</tr>
<?php
	};
?>
