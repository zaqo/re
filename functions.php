<?php
function createTable($name, $query)
{
	queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
	echo "Таблица '$name' создана или уже существовала<br />";
}
function queryMysql($query)
{
	$result = mysql_query($query) or die(mysql_error());
	return $result;
}
function destroySession()
{

	$_SESSION=array();
	if (session_id() != "" || isset($_COOKIE[session_name()]))
	setcookie(session_name(), '', time()-2592000, '/');
	session_destroy();
}
function sanitizeString($var)
{
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return mysql_real_escape_string($var);
}
?>