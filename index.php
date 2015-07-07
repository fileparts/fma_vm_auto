<?php include('./config.php'); ?>
<html>
<head>
<?php include('./head.php'); ?>
</head>
<body>
  <?php include('./nav.php'); ?>
  <div class="main wrp">
    <div class="clr mrg-btm-x-lrg">
      <h1 class="mrg-btm-med">VM Booking</h1>
      <p class="mrg-btm-med"><b>How it works:</b> Each VM <i>should</i> have an application running on them that sends data to a server, once the server recieves the information it then will update the MySQL Database. Information such as: Host, Purpose and Usage will be updated by Website Admins when needs be.</p>
      <p><b>Are the Users connected to the Active Directory?</b> At this moment in time, no.</p>
    </div>
    <div class="clr">
<?php
if(!isset($_SESSION['vm_userID'])) {
?>
      <a class="btn btn-x-lrg btn-success" href="./login.php"><i class="fa fa-fw fa-user"></i> Login</a>
      <a class="btn btn-x-lrg btn-info" href="./register.php"><i class="fa fa-fw fa-user-plus"></i> Register</a>
<?php
} else {
?>
      <a class="btn btn-x-lrg btn-danger" href="./logout.php"><i class="fa fa-fw fa-unlock-alt"></i> Logout</a>
<?php
};
?>
    </div>
  </div>
</body>
</html>
