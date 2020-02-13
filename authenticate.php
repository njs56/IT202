<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  
ini_set('display_errors' , 1);

include("account.php");

$db = mysqli_connect($hostname, $username, $password, $project);

if (mysqli_connect_errno())
  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
print "Successfully connected to MySQL.<br><br><br>";

mysqli_select_db( $db, $project ); 
function authenticate($ucid, $pass) {
	global $db;
	$s = "select * from users where ucid = '$ucid' and pass = '$pass'";
	echo "<br>SQL select is: $s";
	($t = mysqli_query($db, $s)) or die(mysqli_error($db));
	$num = mysqli_num_rows($t);
	if ($num == 0) {return false; }
	else {return true; }
}
$ucid = $_GET["ucid"]; echo "<br>ucid is $ucid";
$pass = $_GET["pass"]; echo "<br>pass is $pass";
$mail = $_GET["mail"];
authenticate($ucid, $pass);
if (! authenticate($ucid, $pass)) {
	echo "<br>Invalid credentials." ; 
	header ("refresh: 6 ; url = form.php");
	exit();
}
else {
	echo "<br>Valid credentials.<br><br><br>" ;
	see($ucid);
	exit();	
}

function see($ucid) {
	global $db;
	global $t;
	
	$s = "select * from transactions where ucid = '$ucid'";
	($t = mysqli_query($db, $s)) or die(mysqli_error($db));
	$num = mysqli_num_rows($t); 
	
	while ($r = mysqli_fetch_array($t, MYSQLI_ASSOC))
	{
		$account = $r["account"];
		$amount = $r["amount"];
		$timestamp = $r["timestamp"];
		$mail = $r["mail"];
		echo "account: $account || ";
		echo "amount: $$amount || ";
		echo "timestamp: $timestamp || ";
		echo "mail requested: $mail ||<br> ";
	}
	
	mysqli_free_result($t);
}

?>