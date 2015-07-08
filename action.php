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
  ?>
    <p class="alert">Username is already taken, redirecting...</p>
  <?php
        redirect("./login.php");
      } else {
        $okay = true;
      };
      $checkUsername->close();

      if($okay == true) {
        $createUser = $con->prepare("INSERT INTO users(userName,userEmail,userFirst,userLast,userPass) VALUES(?,?,?,?,?)");
        $createUser->bind_param("sssss", $formName,$formEmail,$formFirst,$formLast,$formPass);
        if($createUser->execute()) {
  ?>
    <p class="alert">User successfully created, redirecting...</p>
  <?php
          redirect("./login.php");
        } else {
  ?>
    <p class="alert">Users unsuccessfully created, redirecting...</p>
  <?php
          redirect("./register.php");
        };
        $createUser->close();
      };
    } else if($action == "login") {
      $formName = strtolower($_POST['formName']);
      $formPass = $_POST['formPass'];
      $formPass = crypt($formPass, $cryptSalt);

      $checkUsername = $con->prepare("SELECT * FROM users WHERE userName=?");
      $checkUsername->bind_param("s", $formName);
      $checkUsername->execute();
      $checkUsername->store_result();
      if($checkUsername->num_rows > 0) {
        $okay = true;
      } else {
  ?>
    <p class="alert">Username not found, redirecting...</p>
  <?php
        redirect("./register.php");
      };
      $checkUsername->close();

      if($okay == true) {
        $checkPassword = $con->prepare("SELECT userID,userPass,userPerms FROM users WHERE userName=?");
        $checkPassword->bind_param("s", $formName);
        $checkPassword->execute();
        $checkPassword->bind_result($userID,$userPass,$userPerms);
        while($checkPassword->fetch()) {
          if($userPass == $formPass) {
  ?>
    <p class="alert">Logging In, redirecting...</p>
  <?php
            $_SESSION['vm_userID'] = $userID;
            $_SESSION['vm_userPerms'] = $userPerms;
            redirect("./");
          } else {
  ?>
    <p class="alert">Incorrect Password, redirecting...</p>
  <?php
            redirect("./login.php");
          };
        };
        $checkPassword->close();
      };
    } else {
  ?>
    <p class="alert">A Valid Action is Required...</p>
  <?php
      redirect("./");
    };
  } else {
  ?>
    <p class="alert">An Action is Required...</p>
  <?php
    redirect("./");
  };
  ?>
  </div>
</body>
</html>
