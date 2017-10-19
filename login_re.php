<?php //REAL ESTATE PROJECT
	$db_hostname= 'localhost';
	$db_database= 'realestate';
	$db_username= 'php';
	$db_password= '12345';
	
	$Currencies	=	array("","RUR","EUR","USD");
	$Schema		=	array("Фиксированный платеж","% от оборота","% или min","нараст. итогом (% или min)");
	
	//SAP web service
	$SAP_username= 'PHP_SERVICE';
	$SAP_password= 'Service5#';
	//DO NOT FORGET TO CHANGE WSDL's URL - must be A101!!!
	
    $wsdlurl='http://srvr-185.local.newpulkovo.ru:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zsd_order_znak_crud/110/zsd_order_znak_crud/zsd_order_znak_crud?sap-client=110';
	
?>