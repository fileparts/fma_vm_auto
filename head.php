<title>FMA VM Booking</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" type="text/css" href="./styles/default.css" />
<link rel="stylesheet" type="text/css" href="./styles/font-awesome.min.css" />
<script src="./scripts/jquery-1.11.3.min.js"></script>
<script src="./scripts/global.js"></script>
<?php
	function redirect($url) {
		$string 	= '<script type="text/javascript">';
		$string 	.= 'setTimeout(function() {';
		$string 	.= 'window.location = "' . $url . '"';
		$string	.= '}, 2000)';
		$string 	.= '</script>';
		echo $string;
	};
?>
