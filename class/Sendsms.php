
<?php

define("SMS_USER", "thabathi");
define("SMS_PASSWORD", "thabathi@we1234");
define("SMS_SID", "RATION");

class SMS
{
	public static function send_message($mobile_no, $message, $promotional_sms=false)
	{

		// to replace the space in message with ‘%20’
		$message_url = trim(str_replace(' ', '%20', $message));

		$url='http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user='.SMS_USER.'&password='.SMS_PASSWORD.'&msisdn=91'.$mobile_no.'&sid='.SMS_SID.'&msg='.$message_url.'&fl=0';
		//$url='http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=thabathi&password=thabathi@we1234&msisdn=919790064270&sid=RATION&msg=Thankyou&fl=0';
		///$url='';

		

		// create a new cURL resource
		$curl = curl_init();
		
		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		
		// grab URL and pass it to the browser
		$results = curl_exec($curl);
		
		//$we_logger->info("Response from SMS Gateway". json_decode($results));
		
		// close cURL resource, and free up system resources
		curl_close($curl);
		$msg="Thanks";
		return $results;
	}
	
	//send SMS to customer if the order is completed
	public function send_stud_sms($mobile_no)
	{
		$msg = "Dear Customer, This is check mail. Thank You. ";
		 SMS::send_message($mobile_no, $msg);
	}
	
	
}


?>
