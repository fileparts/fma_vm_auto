<?php include('./config.php');session_destroy(); ?>
<html>
<head>
<?php include('./head.php'); ?>
</head>
<body>
  <?php include('./nav.php'); ?>
  <div class="main wrp">
    <?php
  if(isset($_SESSION['userID'])) {
    ?>
    <p class="alert">Logging Out...</p>
    <?php
    redirect("./");
  } else {
    ?>
    <p class="alert">You must be Logged In to Logout</p>
    <?php
    redirect("./login.php");
  };
    ?>
  </div>
</body>
</html>
