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

    if($action == "register") {
      $formName = strtolower($_POST['formName']);
      $formEmail = $_POST['formEmail'];
      $formFirst = $_POST['formFirst'];
      $formLast = $_POST['formLast'];
      $formPass = $_POST['formPass'];
      $formPass = crypt($formPass, $cryptSalt);

      $checkUsername = $con->prepare("SELECT * FROM users WHERE userName=?");
      $checkUsername->bind_param("s", $formName);
      $checkUsername->execute();
      $checkUsername->store_result();
      if($checkUsername->num_rows > 0) {
        $okay = false;
      } else {
        $okay = true;
      };

      if($okay == true) {
        $createUser = $con->prepare("INSERT INTO users(userName,userEmail,userFirst,userLast,userPass) VALUES(?,?,?,?,?)");
        $createUser->bind_param("sssss", $formName,$formEmail,$formFirst,$formLast,$formPass);
        if($createUser->execute()) {
  ?>
    <p class="alert">User Registered...</p>
  <?php
        } else {
  ?>
    <p class="alert">User Not Registered...</p>
  <?php
        };
      } else {
  ?>
    <p class="alert">Username is Already Taken...</p>
  <?php
      };
    } else if($action == "login") {
      $formName = strtolower($_POST['formName']);
      $formPass = $_POST['formPass'];

      $checkUsername = $con->prepare("SELECT * FROM users WHERE userName=?");
      $checkUsername->bind_param("s", $formName);
      $checkUsername->execute();
      $checkUsername->store_result();
      if($checkUsername->num_rows > 0) {
        $okay = true;
      } else {
        $okay = false;
      };

      
    } else {
  ?>
    <p class="alert">A Valid Action is Required...</p>
  <?php
    };
  } else {
  ?>
    <p class="alert">An Action is Required...</p>
  <?php
  };
  ?>
  </div>
</body>
</html>
