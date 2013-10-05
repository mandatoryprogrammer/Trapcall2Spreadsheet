<?php
/*
	Script generating using the Metafid Code Generation Engine - created by Matthew Bryant (mandatory)

	Any unauthorized use of this code is forbidden, please contact the creator with any questions.

	Contact details:
		Matthew Bryant
		mandatory@gmail.com

	Thank you for playing fair.

																						-mandatory
*/

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$currentDirectory = dirname(__FILE__)."/";
$currentFilePath = __FILE__;

include $currentDirectory."LIB_metafid/LIB_http.php";
include $currentDirectory."LIB_metafid/LIB_parse.php";

$http = new http_request();
$parser = new http_parse();

// Add your phone number and PIN here
$phone_number = "(555) 555-5555";
$pin = "5555";

$http->clear_cookies();
$http->set_url("http://trapcall.com/");
$http->set_get();
$request1 = $http->run();

$http->set_url("http://www.trapcall.com/");
$http->set_get();
$request2 = $http->run();

$http->set_url("http://www.trapcall.com/phones/authenticate");
$POST_data = array();
$POST_data["phone_number"] = $phone_number;
$POST_data["pin"] = $pin;
$POST_data["remember_me"] = "0";
$http->set_referer("http://www.trapcall.com/");
$http->set_post($POST_data);
$request37 = $http->run();

$http->set_url("http://www.trapcall.com/phones");
$http->set_get();
$http->set_referer("http://www.trapcall.com/");
$request38 = $http->run();

$http->set_url("http://www.trapcall.com/calls");
$http->set_get();
$http->set_referer("http://www.trapcall.com/phones");
$request65 = $http->run();

$http->set_url("http://www.trapcall.com/calls/print");
$http->set_get();
$http->set_referer("http://www.trapcall.com/calls");
$request78 = $http->run();
$json_String = $parser->return_between($request78['body'], "var data = ", "var filter", "FALSE");
$json_String = trim($json_String);
$json_String = substr($json_String, 0, -1);
$call_Array = json_decode($json_String, true);
$call_Array = $call_Array['resources'];

$fp = fopen("Trapcall_Logs_".date("Y-m-d_h.iA").".csv", 'w');

$header_Array = array(
		"Recording",
		"Is Blacklisted?",
		"Calling Name",
		"Insert Time",
		"Is Unmasked?",
		"Phone Number",
		"Is Blacklisted?",
		"City",
		"First Name",
		"Last Name",
		"State",
		"Address",
		"Zip Code",
		"Caller Unique ID",
		"Name",
		"ID",
		"Calling Number"
);

fputcsv($fp, $header_Array);

foreach($call_Array as $key => $call_data_Array)
{
	$tmp_Array = array();

	$tmp_Array[0] = $call_data_Array['recording'];
	$tmp_Array[1] = $call_data_Array['is_blacklisted'];
	$tmp_Array[2] = $call_data_Array['calling_name'];
	$tmp_Array[3] = $call_data_Array['insert_time'];
	$tmp_Array[4] = $call_data_Array['is_unmasked'];
	$tmp_Array[5] = $call_data_Array['caller']['phone_number'];
	$tmp_Array[6] = $call_data_Array['caller']['is_blacklisted'];
	$tmp_Array[7] = $call_data_Array['caller']['bna']['city'];
	$tmp_Array[8] = $call_data_Array['caller']['bna']['first_name'];
	$tmp_Array[9] = $call_data_Array['caller']['bna']['last_name'];
	$tmp_Array[10] = $call_data_Array['caller']['bna']['state'];
	$tmp_Array[11] = $call_data_Array['caller']['bna']['address'];
	$tmp_Array[12] = $call_data_Array['caller']['bna']['zip_code'];
	$tmp_Array[13] = $call_data_Array['caller']['id'];
	$tmp_Array[14] = $call_data_Array['caller']['name'];
	$tmp_Array[15] = $call_data_Array['id'];
	$tmp_Array[16] = $call_data_Array['calling_number'];

	print_r($tmp_Array);
	fputcsv($fp, $tmp_Array);
	unset($tmp_Array);
}
fclose($fp);

?>