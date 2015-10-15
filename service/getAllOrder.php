<?php

include '../config.php';
include '../class/Order.php';

$configobj = new config();
$conn = $configobj->getConnection();

$obj = new Order();
$mobileno = $_POST["user_mobileno"];
echo $mobileno;
