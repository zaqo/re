<?php
$_SESSION=array();
$sid= session_id();
$sname=session_name();
//echo "SID = $sid, SNAME = $sname"."<br>";
	if (session_id() != "" || isset($_COOKIE[session_name()]))
	setcookie(session_name(), '', time()-2592000, '/');

	//var_dump($_SESSION);
	if (isset($_SESSION['user']))
	{
		unset($_SESSION['user']);
	}
	//session_destroy();
header("location:\Agents\login.php");
?>