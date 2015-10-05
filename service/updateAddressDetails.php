<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$user_order_id = $request->user_order_id;
$user_mobileno = $request->user_mobileno;
$user_first_name = $request->user_first_name;
$user_email = $request->user_email;
$user_address = $request->user_address;
$user_pincode = $request->user_pincode;
$user_landmark = $request->user_landmark;
$user_account_type = $request->user_account_type;
$user_dealercode = $request->user_dealercode;
$user_comments = $request->user_comments;
$user_amount_range = $request->user_amount_range;
$user_gift = $request->user_gift;


/*echo "ORDER ID".$user_order_id.'<br/>';
echo "MOBILE NO".$user_mobileno.'<br/>';
echo "FNAME".$user_first_name.'<br/>';
echo "EMAIL".$user_email.'<br/>';
echo "ADDRESS".$user_address.'<br/>';
echo "PINCODE".$user_pincode.'<br/>';
echo "LANDMARK".$user_landmark.'<br/>';
echo "ACCOUNT TYPE".$user_account_type.'<br/>';
echo "DEALER CODE".$user_dealercode.'<br/>';
echo "COMMENTS".$user_comments.'<br/>';
echo "AMOUTN RANGE".$user_amount_range.'<br/>';
echo "GIFT".$user_gift.'<br/>';*/

echo json_encode($userobj->updateAddressDetails($conn,$user_order_id,$user_mobileno,$user_first_name,$user_email,$user_address,$user_pincode,$user_landmark,$user_account_type,$user_dealercode,$user_comments,$user_amount_range,$user_gift));
    
