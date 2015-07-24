<?php include('./config.php'); ?>
<html>
<head>
<?php include('./head.php'); ?>
</head>
<body>
  <?php include('./nav.php'); ?>
  <div class="main wrp">
<?php
    if($_SESSION['userPerms'] > 3) {
      if(isset($_GET['t'])) {
        $creationType = $_GET['t'];

        if($creationType == "h") {
?>
    <form method="post" action="./action.php?a=create">
      <input name="creationType" type="hidden" value="<?php echo $creationType; ?>" required />
      <h1 class="mrg-btm-x-lrg">Create a Host</h1>
      <table class="fixed">
        <tr>
          <td><p>Enter Host Name</p></td>
          <td><input name="formName" type="text" placeholder="Host Name" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Enter Host IP</p></td>
          <td><input name="formIP" type="text" placeholder="Host IP" autocomplete="off" required /></td>
        </tr>
        <tr>
          <td><p>Select Visibility</p></td>
          <td>
            <select name="formPerms" required>
              <option selected disabled>Select Visibility</option>
              <option value="1">Visible</option>
              <option value="0">Not Visible</option>
            </select>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><button class="btn-warning confirm" type="submit">Submit</button></td>
        </tr>
      </table>
    </form>
<?php
        } else if($creationType == "us") {
?>
    <form method="post" action="./action.php?a=create">
      <input name="creationType" type="hidden" value="<?php echo $creationType; ?>" required />
      <h1 class="mrg-btm-x-lrg">Create a Usage</h1>
      <table class="fixed">
        <tr>
          <td><p>Enter Usage Name</p></td>
          <td><input name="formName" type="text" placeholder="Usage Name" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td></td>
          <td><button class="btn-warning confirm" type="submit">Submit</button></td>
        </tr>
      </table>
    </form>
<?php
        } else {
?>
    <p class="alert">a valid creation type is required, redirecting...</p>
<?php
        redirect("./admin.php");
        };
      } else {
?>
    <p class="alert">a creation type is required, redirecting...</p>
<?php
        redirect("./admin.php");
      };
    } else {
?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
<?php
      redirect("./");
    };
?>
  </div>
</body>
</html>
