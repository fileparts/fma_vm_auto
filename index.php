<?php include('./config.php'); ?>
<html>
<head>
<?php include('./head.php'); ?>
</head>
<body>
  <?php include('./nav.php'); ?>
  <div class="main wrp">
    <div class="clr mrg-btm-x-lrg">
      <h1 class="mrg-btm-x-lrg">VM Booking</h1>
      <p class="mrg-btm-med"><b>How it works:</b> Each VM <i>should</i> have an application running that sends machine data back to a server, once the server receives and formats the information, it then sends a series of MySQL Queries to the VM database which then in turn is displayed on this website.</p>
      <p><b>Are the Users connected to the Active Directory?</b> At this moment in time, no.</p>
    </div>
    <div class="clr">
<?php
if(!isset($_SESSION['userID'])) {
?>
      <a class="btn btn-med btn-success" href="../"><i class="fa fa-fw fa-user"></i> Login</a>
      <a class="btn btn-med btn-info" href="../"><i class="fa fa-fw fa-user-plus"></i> Register</a>
<?php
} else {
  if($_SESSION['userPerms'] > 3) {
?>
      <a class="btn btn-med btn-warning" href="./admin.php"><i class="fa fa-fw fa-cog"></i> Admin</a>
<?php
  };
?>
      <a class="btn btn-med btn-danger" href="../logout.php"><i class="fa fa-fw fa-unlock-alt"></i> Logout</a>
<?php
};
?>
    </div>
  </div>
</body>
</html>
