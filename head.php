<title>FMA VM Booking</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" type="text/css" href="./styles/default.css" />
<link rel="stylesheet" type="text/css" href="./styles/font-awesome.min.css" />
<script src="./scripts/jquery-1.11.3.min.js"></script>
<script src="./scripts/global.js"></script>
<script>
	$(document).ready(function() {
		$('.confirm').on('click', function () {
			return confirm('Are you sure?');
		});
	});
</script>
<?php
  if(isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    $getPerms = $users->prepare("SELECT userPerms FROM users WHERE userID=?");
    $getPerms->bind_param("i", $userID);
    $getPerms->execute();
    $getPerms->store_result();
    $getPerms->bind_result($userPerms);
    while($getPerms->fetch()) {
      $_SESSION['userPerms'] = $userPerms;
    };
    $getPerms->close();
  };

	if(!function_exists('hash_equals')) {
		function hash_equals($str1, $str2) {
			if(strlen($str1) != strlen($str2)) {
				return false;
			} else {
				$res = $str1 ^ $str2;
				$ret = 0;
				for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
				return !$ret;
			}
		}
	};

	function better_crypt($input) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./';
		$charactersLength = strlen($characters);
		$saltString = '';
		for ($i = 0; $i < $length; $i++) {
				$saltString .= $characters[rand(0, $charactersLength - 1)];
		};

		$salt = '$6$rounds=5000$';
		$salt .= $saltString;
		return crypt($input, $salt);
	};
	function redirect($url) {
		$string 	= '<script type="text/javascript">';
		$string 	.= 'setTimeout(function() {';
		$string 	.= 'window.location = "' . $url . '"';
		$string	.= '}, 2000)';
		$string 	.= '</script>';
		echo $string;
	};
?>
