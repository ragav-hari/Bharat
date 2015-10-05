<?php
include '../class/Sendsms.php';

$obj = new SMS();
$mobilenos = "8056598186";
echo "DATA".$obj->send_message($mobilenos,"Developer Test")."DD";