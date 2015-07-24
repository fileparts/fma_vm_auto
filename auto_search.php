<?php
	include("./config.php");

	$needle = strtolower($_POST['input']);
	$found = false;

	if(strlen($needle) > 0) {
		$byMachineName = $con->prepare("SELECT hostID,machineID,machineIP,machineName FROM machines WHERE machineIP LIKE '%$needle%' ORDER BY hostID ASC");
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
  <?php }; ?>
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
<?php }; ?>
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
<?php }; ?>
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
			$byMachineIP = $con->prepare("SELECT hostID,machineID,machineIP,machineName FROM machines WHERE machineName LIKE '%$needle%' ORDER BY hostID ASC");
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
	  <?php }; ?>
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
	<?php }; ?>
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
	<?php }; ?>
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
			$byPurpose = $con->prepare("SELECT purposeID,machineID,machinePurpose FROM machinepurposes WHERE machinePurpose LIKE '%$needle%'");
			$byPurpose->execute();
			$byPurpose->store_result();
			if($byPurpose->num_rows > 0) {
				$found = true;

				$byPurpose->bind_result($purposeID,$machineID,$machinePurpose);
				while($byPurpose->fetch()) {
					$getMachine = $con->prepare("SELECT hostID,machineIP,machineName FROM machines WHERE machineID=?");
					$getMachine->bind_param("i", $machineID);
					$getMachine->execute();
					$getMachine->store_result();
					$getMachine->bind_result($hostID,$machineIP,$machineName);
					while($getMachine->fetch()) {
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
<?php }; ?>
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
<?php }; ?>
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
<?php }; ?>
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
				};
			} else {
				$found = false;
			};
			$byPurpose->close();
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
<?php }; ?>
