<?php

include '../config.php';
include '../webappClass/UserClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new UserClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$user_email = $request->emailid;
$user_password = $request->password;

echo json_encode($userobj->changeforgottenpassword($conn,$user_email,$user_password));

