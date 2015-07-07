<?php
  include('./config.php');

  $needle = strtolower($_POST['input']);

  if(strlen($needle) > 0) {
    $checkUsername = $con->prepare("SELECT * FROM users WHERE userName=?");
    $checkUsername->bind_param("s", $needle);
    $checkUsername->execute();
    $checkUsername->store_result();
    if($checkUsername->num_rows > 0) {
      echo 1;
    } else {
      echo 0;
    };
    $checkUsername->close();
  };
?>
