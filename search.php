<?php include('./config.php'); ?>
<html>
<head>
<?php include('./head.php'); ?>
<script>
  $(document).ready(function() {
    var searcher = "input[name=search]";
    var searchInput = $(searcher).val();

    $(searcher).keyup(function() {
      searchInput = $(this).val();
      liveSearch();
    });

    function liveSearch() {
      $.ajax({
        method: "POST",
        url: "./auto_search.php",
        data: { input: searchInput }
      })
      .done(function(html) {
        $('table').html('<tr class="head">'
        + '<td colspan="7">'
        + '<p>Search Results</p>'
        + '</td>'
        + '</tr>'
        + html);
      });
    };
  });
</script>
</head>
<body>
  <?php include('./nav.php'); ?>
  <div class="main wrp">
  <?php
    if(isset($_SESSION['userID'])) {
  ?>
    <h1 class="mrg-btm-x-lrg">Search</h1>
    <form class="mrg-btm-med" method="post">
      <input name="search" type="text" placeholder="Search..." autocomplete="off" autofocus />
    </form>
    <table class="full outline">
      <tr class="head">
        <td colspan="7">
          <p>Search Results</p>
        </td>
      </tr>
      <tr>
        <td colspan="7">
          <p class="alert">Search Something...</p>
        </td>
      </tr>
    </table>
  <?php
    } else {
  ?>
    <p class="alert">You must be logged in to view this page, redirecting...</p>
  <?php
      redirect("../login.php");
    };
  ?>
  </div>
</body>
</html>
