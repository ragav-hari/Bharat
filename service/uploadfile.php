<?php
include '../config.php';
include '../class/User.php';
$configobj = new config();
$conn = $configobj->getConnection();

$userobj = new User();

$target_path = "../uploads/";
$error = array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($_FILES["uploadedfile"]["error"] > 0){
	echo "Error Code: " . $_FILES["uploadedfile"]["error"] . "<br />";
}
else
{
	$tmp         = explode(".", $_FILES['uploadedfile']['name']);
        $digits = 5;
        $rand = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $newfilename = $rand.round(microtime(true)).".".end($tmp);

	$file_size = $_FILES['uploadedfile']['size'];
	$file_tmp  = $_FILES['uploadedfile']['tmp_name'];
	$file_type = $_FILES['uploadedfile']['type'];
        $order_id = $_POST["order_id"];
        $filetype = $_POST["filetype"];
        
	$target_path = $target_path . $newfilename; 
        

	if(move_uploaded_file($file_tmp, $target_path)) 
        {
		//echo "Response"."FILE NAME".$file_name+"TARGET PATH".$target_path ;
                //updateorder($conn,$order_id,$file_name,$target_path,$userobj)    
          //  $query = "insert into orderitem(item_name,item_url,order_id) values('$file_name','$target_path','$order_id')";
          //  $result = mysqli_query($conn, $query);
                echo json_encode(updateorder($conn,$order_id,$newfilename,$target_path,$userobj,$filetype));
                echo json_encode($file_type);
	} 
        else{
		echo "There was an error uploading the file, please try again!";
		echo $_FILES['uploadedfile'];
	} 
}


function updateorder($conn,$order_id,$file_name,$target_path,$userobj,$filetype)
{
    if($userobj->placeorder($conn,$order_id,$file_name,$target_path,$filetype))
                {
                    return "Success";
                }
                else
                {
                    return "Failure";
                }     
}