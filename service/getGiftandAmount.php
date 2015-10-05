<?php

include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();


echo json_encode($userobj->getGiftandAmount($conn));