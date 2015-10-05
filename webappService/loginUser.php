<?php
include '../config.php';
include '../webappClass/UserClass.php';

$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new UserClass();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$loginid  = $request->loginid;
$password = $request->password;

echo json_encode($userobj->userLogin($conn, $loginid, $password));