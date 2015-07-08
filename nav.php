<div class="nav">
  <ul class="wrp">
    <li><a href="./"><i class="fa fa-fw fa-home"></i> Home</a></li>
    <?php
  if(isset($_SESSION['vm_userID'])) {
    ?>
    <li><a href="./browse.php"><i class="fa fa-fw fa-archive"></i> Browse</a></li>
    <li><a href="./search.php"><i class="fa fa-fw fa-search"></i> Search</a></li>
    <?php
  };
    ?>
    <ul>
      <?php
    if(!isset($_SESSION['vm_userID'])) {
      ?>
      <li><a href="./login.php"><i class="fa fa-fw fa-lock"></i> Login</a></li>
      <?php
    } else {
      ?>
      <li><a href="./logout.php"><i class="fa fa-fw fa-unlock-alt"></i> Logout</a></li>
      <?php
    };
      ?>
      <li><a href="mailto:dexter.marks-barber@fma.uk.com"><i class="fa fa-fw fa-envelope"></i> Feedback</a></li>
    </ul>
  </ul>
</div>
