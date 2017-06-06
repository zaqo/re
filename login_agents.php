<?php //login.php
	$db_hostname= 'localhost';
	$db_database= 'checkin_reg';
	$db_username= 'php';
	$db_password= '12345';
	
	$db_mssql='172.16.90.39';
	$db_mssql_dbname='NCG_PRODUCTION';
	$db_mssql_user='php';
	$db_mssql_pass='12345sql';
	$serverName = '172.16.90.39'; //serverName\instanceName
	$tablename="dbo.[NCG\$Route]";
	$connectionInfo = array( "Database"=>'NCG_PRODUCTION', "UID"=>"php", "PWD"=>"12345sql");
	$systems=array("status","DCS","Amadeus","Sabre","BA","Navitare","Troya","EK","Astra","Travelsky","AngeLight");
?>