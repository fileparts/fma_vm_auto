<?php include('./config.php'); ?>
<html>
<head>
<?php include('./head.php'); ?>
<script>
$(document).ready(function() {
  var input = "input[name=formName]";
  var username;

  $(input).keyup(function() {
    username = $(this).val();
    console.log(username);

    if(username.length > 0) {
      liveSearch();
    } else {
      $("button[type=Submit]")
        .removeClass("btn-success")
        .removeClass("btn-danger")
        .addClass("btn-default")
        .removeAttr("disabled");
    };
  });

  function liveSearch() {
    $.ajax({
      method: "POST",
      url: "auto_user.php",
      data: { input: username }
    })
    .done(function(msg) {
      console.log(msg);

      if(msg == 1) {
        $("button[type=Submit]")
          .removeClass("btn-default")
          .removeClass("btn-danger")
          .addClass("btn-success")
          .removeAttr("disabled");
      } else if(msg == 0) {
        $("button[type=Submit]")
          .removeClass("btn-default")
          .removeClass("btn-success")
          .addClass("btn-danger")
          .attr("disabled","true");
      };
    });
  };
});
</script>
</head>
<body>
  <?php include('./nav.php'); ?>
  <div class="main wrp">
    <div class="clr">
    <?php
  if(!isset($_SESSION['vm_userID'])) {
    ?>
      <h1>Login</h1>
      <form class="mrg-top-lrg" method="post" action="./action.php?a=login">
        <table class="fixed">
          <tr>
            <td><p>Enter Your Username</p></td>
            <td><input name="formName" type="text" placeholder="Username" autocomplete="off" autofocus required /></td>
          </tr>
          <tr>
            <td><p>Enter Your Password</p></td>
            <td><input name="formPass" type="password" placeholder="Password" required /></td>
          </tr>
          <tr>
            <td></td>
            <td><button class="btn-default" type="submit">Login</button></td>
          </tr>
          <tr>
            <td></td>
            <td><a class="mrg-top-lrg" href="./register.php">Don't have an Account?</a></td>
          </tr>
        </table>
      </form>
    <?php
  } else {
    ?>
    <p class="alert">You are already logged in, redirecting...</p>
    <?php
    redirect("./");
  };
    ?>
    </div>
  </div>
</body>
</html>
