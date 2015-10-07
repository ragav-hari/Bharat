<?php

include '../config.php';
include '../webappClass/UserClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new UserClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$bh_id          = $request->bh_id;
$bh_name        = $request->bh_name;
$bh_user_name   = $request->bh_user_name;
$bh_password    = $request->bh_password;
$bh_mobno       = $request->bh_mobno;
$bh_email       = $request->bh_email; 
$bh_userrole     = $request->bh_userrole;

echo json_encode($userobj->addUserDetail($conn,$bh_id,$bh_name,$bh_user_name,$bh_password,$bh_mobno,$bh_email,$bh_userrole));