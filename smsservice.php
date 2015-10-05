<?php
error_reporting(E_ALL);
ob_implicit_flush(true);

include_once "class.curl.php";
include_once "class.sms.php";
include_once "cprint.php";

$smsapp=new sms();
$smsapp->setGateway('way2sms');
$myno=$_POST['fromno'];
$p=$_POST['pass'];
$tonum=$_POST['tono'];
$mess=$_POST['message'];


$ret=$smsapp->login($myno,$p);

if (!$ret) {
   cprint("Error Logging In");
   echo "Error Logging In",$ret;
  // exit(1);
}

echo "Logged In Successfully";

echo "Send SMS";
$ret=$smsapp->send($tonum,$mess);

if (!$ret) {
   echo "Error in sending message";
   exit(1);
}

echo "Message sent";

?>