<?php
define("SMS_USER", "thabathi");
define("SMS_PASSWORD", "thabathi@we1234");
define("SMS_SID", "RATION");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author ragavendran
 */

class User 
{
   function addPhoneNumber($con,$phoneno)
   {
       $status_code = $this->isPhoneNumberExists($con, $phoneno);
       
       // send SMS and go to OTP confirmation page
       if($status_code == 100) 
       {
          $status = 100;
          $response = $this->updateOTPForPhoneno($con,$phoneno,$status);
       }
       // Phone Number already confirmed but account not created so reconfirm
       else if($status_code == 101)
       {
           $status = 100;
           $response = $this->updateOTPForPhoneno($con,$phoneno,$status);   
       }
       //Phone Number already confirmed and account created so prompt for login
       else if($status_code == 102)
       {
           //$response = array("status"=>"Exists","message"=>"Account Already Exists");
		   $status = 100;
           $response = $this->updateOTPForPhoneno($con,$phoneno,$status);   
       }
       // Phone Number not found so add it to db
       else
       {
           $status = 100;
           $response = $this->addOTPForPhoneno($con,$phoneno,$status);
       }
       return $response;
   }
   
   function addOTPForPhoneno($con,$phoneno,$status)
   {
           $randomkey = $this->generateOTP();
           $query = "insert into otpkeys(user_mobileno,user_random_key,otpkey_status) values('$phoneno','$randomkey','$status')";
           $result = mysqli_query($con, $query);
           if($result)
           {
               $this->sendSMS($phoneno, $randomkey);
               $response = array("status"=>"Success","message"=>"Phone Number Added Successfully");
           }
           else
           {
               $response = array("status"=>$con->error,"message"=>"Failed to add Phone Number");
           }
           return $response;
   }
   
   function updateOTPForPhoneno($con,$phoneno,$status)
   {
           $randomkey = $this->generateOTP();
           $query     = "update otpkeys set user_random_key = '$randomkey',otpkey_status='$status' where user_mobileno = '$phoneno'";
           $result = mysqli_query($con, $query);
           if($result)
           {
               $this->sendSMS($phoneno, $randomkey);
               $response = array("status"=>"Success","message"=>"Phone Number Added Successfully");
           }
           else
           {
                $response = array("status"=>"Failure","message"=>"Failed to add Phone Number");
           }
           return $response;
   }
   
  
   function generateOTP()
   {
       $digits = 5;
       return rand(pow(10, $digits-1), pow(10, $digits)-1);
   }
   
   function sendSMS($mobile_no,$randomkey)
   {       
      $message = "Your OTP Code is : ".$randomkey;
		$message_url = trim(str_replace(' ', '%20', $message));

		$url='http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user='.SMS_USER.'&password='.SMS_PASSWORD.'&msisdn=91'.$mobile_no.'&sid='.SMS_SID.'&msg='.$message_url.'&fl=0';
		
		$curl = curl_init();
		
		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		
		
		$results = curl_exec($curl);
		
		//$we_logger->info("Response from SMS Gateway". json_decode($results));
		
		// close cURL resource, and free up system resources
		curl_close($curl);
		return $results;
   }
   
   function isPhoneNumberExists($con,$phoneno)
   {
       $query = "select * from otpkeys where user_mobileno = '$phoneno'";
       $result = mysqli_query($con,$query);
       $count = mysqli_num_rows($result);
       if($count > 0)
       {
            while($row = mysqli_fetch_array($result))
            {
                $status_code = $row["otpkey_status"];
            }
       }
       else
       {
           $status_code = 404;
       }
       return $status_code;
   }
   
   function verifyOTP($con,$mobileno,$otpkey)
   {
       $query = "select * from otpkeys where user_mobileno = '$mobileno' and user_random_key = '$otpkey'";
       $result = mysqli_query($con,$query);
       $count = mysqli_num_rows($result);
       if($count > 0)
       {
           if($this->changeOTPStatusToVerified($con,$mobileno,$otpkey) == 1)
           {
               if($this->addProfileInfo($con, $mobileno))
               {
                   $response = array("status"=>"Success","message"=>"Mobile Code Verified and Profile Created");
               }
               else
               {
                   $response = array("status"=>"Success","message"=>"Mobile Code Verified Successfully");
               }
           }
           else
           {
               $response = array("status"=>"Faiure","message"=>"Problem With DB");
           }
           
       }
       else
       {
           $response = array("status"=>"Failure","message"=>"Enter Valid Mobile Code");
       }
       return $response;
   }
   
   
   function changeOTPStatusToVerified($con,$mobileno,$otpkey)
   {
       $query = "update otpkeys set otpkey_status = '101' where user_mobileno = '$mobileno' and user_random_key = '$otpkey'";
       $result = mysqli_query($con,$query);
        if($result)
        {
            return 1;
        }
        else
        {
            return 0;
        }
   }
   
   function isMobileNumberAuthenticated($con,$user_mobileno)
   {
       
   }
   
   
   function getGiftandAmount($con)
   {
       $query = "select a.id as amount_id,a.amount_from,a.amount_to from amount_range a";
       $result = mysqli_query($con, $query);
       while($row = mysqli_fetch_array($result))
       {
           $response[] = array("amount_id"=>$row["amount_id"],"amount_from"=>$row["amount_from"],"amount_to"=>$row["amount_to"],"gift"=>$this->getGift($con, $row["amount_id"])); 
       }
       return $response;
       
   }
   
   function getGift($con,$amount_id)
   {
       $query = "select g.id as gift_id,g.gift_name from gift g join gift_amount ga on g.id = ga.gift_id where ga.amount_id = '$amount_id'";
       $result = mysqli_query($con, $query);
       while($row = mysqli_fetch_array($result))
       {
           $response[] = array("gift_id"=>$row["gift_id"],"gift_name"=>$row["gift_name"]);
       }
       return $response;
   }
   function addPassword($con, $user_mobileno,$user_password)
   {
        $query = "insert into users(user_mobileno,user_password) values('$user_mobileno','$user_password')";
        $result = mysqli_query($con, $query);
        if($result)
        {
            $response = array("status"=>"Success","message"=>"User profile Created Successfully");
        }
        else
        {
            $response = array("status"=>"Failure","message"=>"Failed to Create User Profile");
        }
        return $response;
   }
   
   function getUserID($con,$mobileno)
   {
       $query = "select user_id from users where user_mobileno = '$mobileno'";
       $result = mysqli_query($con, $query);
       $count = mysqli_num_rows($result);
       
       if($count > 0)
       {
            while($row = mysqli_fetch_array($result))
            {
                $id = $row['user_id'];
            }
       }
       else
       {
           $id = 'empty';
       }
       return $id;
   }
   
   function getUserType($conn)
   {
       $query = "select * from usertype where usertype_status = '103'";
       $result = mysqli_query($conn, $query);
       while($row  = mysqli_fetch_array($result))
       {
           $response[] = array("usertype_id"=>$row["usertype_id"],"usertype_name"=>$row["usertype_name"]);
       }
       return $response;
   }

   function addUserTypeData($conn,$usertype_id,$user_name,$dealer_code,$mobileno,$email)
   {
       echo "MOB",$mobileno;
       if($this->addProfileInfo($conn, $mobileno))
       {
            $uid = $this->getUserID($conn, $mobileno);
            $query = "CALL updateusertype('$uid','$usertype_id','$user_name','$dealer_code','$email')";
            $result = mysqli_query($conn, $query);
            if($result)
            {
                $response = array("status"=>"Success","message"=>"User profile Updated Successfully");
            }
            else
            {
                $response = array("status"=>$conn->error,"message"=>"User profile Updation Failure");
            }
       }        
       else
       {
           $response = array("status"=>$conn->error,"message"=>"User profile Creation Failure");
       }
       
       return $response;
   }
   
   function addProfileInfo($conn,$mobileno)
   { 
       $user_type = 1; //default customer
       $query = "insert into users(user_mobileno,user_type) values('$mobileno','$user_type')";
       $result = mysqli_query($conn, $query);
       if($result)
       {
           return true;
       }
       else
       {
           return false;
       }
   }
   
   
   function addUserDetails($con, $user_first_name,$user_email,$user_address_line_1,$user_address_line_2,$user_city,$user_state,$user_country,$user_mobileno,$user_pincode,$user_landmark)
   {
        $user_id = $this->getUserID($con, $user_mobileno);
        if($user_id == 'empty')
        {
            $response = array("status"=>"Failure","message"=>"User ID Incorrect");
        }
        else
        {
            $query = "CALL addUserProfile($user_id,$user_first_name,$user_email,$user_mobileno,$user_address_line_1,$user_address_line_2,$user_country,$user_state,$user_city,$user_pincode,$user_landmark)";
            $result = mysqli_query($con, $query);
            if($result)
            {
                $response = array("status"=>"Success","message"=>"User profile Updated Successfully");
            }
            else
            {
                $response = array("status"=>"Failure","message"=>"Failed to Update User Profile");
            }  
        }
        
        return $response;
   }
   
   
   function getPreloadData()
   {
       $query = "select c.*,s.*,ci.* from country c join geolocation g on c.id = g.country_id join state s on s.id = g.state_id join city ci where ci.id = g.city_id";
   }
   
  function getUserPreloadData($conn,$mobileno)
  {
      $query = "select * from users where user_mobileno = '$mobileno'";
      $result = mysqli_query($conn, $query);
      $count = mysqli_num_rows($result);
      if($count > 0)
      {
          while($row = mysqli_fetch_array($result))
          {
             $response = array("status"=>"success","user_first_name"=>$row["user_first_name"],"user_email"=>$row["user_email"],"user_type"=>$row["user_type"],"user_address"=>$row["user_address"],"user_pincode"=>$row["user_pincode"],"user_landmark"=>$row["user_landmark"]);
          }
      }
      else
      {
           $response = array("status"=>$conn->error,"message"=>"Failed");
 
      }
      return $response;
  }
 
   function getCountry($con)
   {
       $query = "select * from country";
       $result = mysqli_query($con, $query);
       $country = array();
       
       while($row = mysqli_fetch_array($result))
       {
           $country["country_id"] = $row['id'];
           $country["country_name"] = $row['name'];
           $country["states"] = $this->getStateByCountry($con, $row['id']);
       }
       return $country;
   }
   
   function getStateByCountry($con,$country_id)
   {
       $query = "select s.id as state_id , s.name as state_name from state s join geolocation g on s.id = g.state_id where g.country_id = $country_id group by s.id";
       $result = mysqli_query($con, $query);
       $state = array();
       
       while($row = mysqli_fetch_array($result))
       {
           $state[] = array("state_id"=>$row["state_id"],"state_name"=>$row["state_name"],"city"=>$this->getCityByState($con, $row['state_id']));
       }    
       return $state;
   }
   
   function getCityByState($con,$state_id)
   {
       $query = "select ci.id as city_id , ci.name as city_name from city ci join geolocation g on ci.id = g.city_id where g.state_id = $state_id ";
       $result = mysqli_query($con, $query);
       $city = array();
       
       while($row = mysqli_fetch_array($result))
       {
           $city[] = array("city_id"=>$row["city_id"],"city_name"=>$row["city_name"]);
       }
       return $city;
   }
   
   
   function getOrderID($con,$mobileno)
   {
       $query = "select * from orders";
       $result = mysqli_query($con, $query);
       
       if($result)
       {
           $count = mysqli_num_rows($result);
       }
       else
       {
           $count = 0;
       }
       return $count;
   }
   
   function  createOrder($con,$mobileno)
   {
        $order_id = $this->getOrderID($con, $mobileno) + 1;
        $user_id = $this->getUserID($con, $mobileno);
        $now = date("Y-m-d H:i:s");
        $query = "insert into orders(order_id,order_date,user_id,order_status) values('$order_id','$now','$user_id','104')";
        $result = mysqli_query($con, $query);
        if($result)
        {
            $response = array("status"=>"Success","message"=>"Order Created Successfully","order_id"=>$order_id);
        }
        else
        {
            $response = array("status"=>"Failure","message"=>"Order Creation Failure");
        }
        return $response;  
   }
   
   function placeorder($conn,$order_id,$file_name,$path)
   {
       $query = "insert into orderitem(item_name,item_url,order_id) values('$file_name','$path','$order_id')";
       $result = mysqli_query($conn, $query);
       if($result)
       {
           return true;
       }
       else
       {
           return false;
       }
   }
   
   function updateAddressDetails($con,$user_order_id,$user_mobileno,$user_first_name,$user_email,$user_address,$user_pincode,$user_landmark,$user_account_type,$user_dealercode,$user_comments,$user_amount_range,$user_gift)
   {
       $user_id = $this->getUserID($con, $user_mobileno);
       $query = "CALL createorder('$user_order_id','$user_id','$user_first_name','$user_email','$user_address','$user_pincode','$user_landmark','$user_account_type','$user_dealercode','$user_comments','$user_amount_range','$user_gift')";
       $result = mysqli_query($con, $query);
       if($result)
       {
           $response = array("status"=>"Success","message"=>"Details Updated Successfully");
       }
       else 
       {
           $response = array("status"=>$con->error,"message"=>"Details Updation Failure");
       }
       return $response;
   }
   
   function addEmployeeDetails($conn,$bh_user_name,$user_email,$user_mobileno,$user_password,$user_bh_id)
   {
       $status="103";
       $query = "insert into employee(bh_id,bh_user_name,bh_user_password,bh_mobileno,bh_emailid,bh_status) values('$user_bh_id','$bh_user_name','$user_password','$user_mobileno','$user_email',$status)";
       $result = mysqli_query($conn,$query); 
       
        if($result)
        {
            $response = array("status"=>"Success","message"=>"Employee profile Created Successfully");
        }
        else
        {
            $response = array("status"=>$conn->error,"message"=>"Employee profile Creation Failure");
        }  
        
        return $response;
   }   
   function validateEmployee($conn,$bh_user_name,$bh_user_password)
   {
       $query = "select id from employee where bh_user_name = '$bh_user_name' and bh_user_password = '$bh_user_password'";
       $result = mysqli_query($conn, $query);
        
        if($result)
        {
          while($row = mysqli_fetch_array($result))
          {
           // $response[] = array("id"=>$row["id"],"menu_url"=>$this->getMenuItems($conn,$row["id"])); 
            $id =$row["id"];
           $response = $this->getMenuItems($conn, $id);
          } 
         
        }
        else
        {
             $response[] = array("status"=>"Failure","message"=>"Failure");
        }
      
       return $response;
   }   
   function getMenuItems($con,$userid)
   {
       $query="select m.name,m.url,e.id,e.user_role from employee e
join roles r on r.id  = e.user_role
join menu_role_mapping mr on mr.role_id = e.user_role
join menu m on m.id = mr.menu_id
where e.id='$userid'";
             $result = mysqli_query($con, $query);
             $response =  array();
        if($result)
        {
          while($row = mysqli_fetch_array($result))
          {
            $response[] = array("id"=>$userid,"menu_name"=>$row["name"],"menu_url"=>$row["url"],"user_role"=>$row["user_role"]);
          } 
         
        }
        else 
        {
             $response[] = array("menu_name"=>"null","menu_url"=>"null");
        }
        return $response;
       
   }
    function  listEmployee($conn)
   {
       $query ="select id,bh_id,bh_user_name,bh_mobileno,bh_emailid,bh_status from employee where bh_status='103'";
       $result = mysqli_query($conn, $query);
        
        if($result)
        {
          while($row = mysqli_fetch_array($result))
          {
            $response[] = array("id"=>$row["id"],"bh_id"=>$row["bh_id"],"bh_user_name"=>$row["bh_user_name"],"bh_mobileno"=>$row["bh_mobileno"],"bh_emailid"=>$row["bh_emailid"],"bh_status"=>$row["bh_status"]); 
          } 
         
        }
        else
        {
             $response[] = array("status"=>"Failure","message"=>"Failure");
        }
      
       return $response;
       
   }
   function  getEmpDetails($conn,$empid)
   {
       $query ="select id,bh_id,bh_user_name,bh_mobileno,bh_emailid,bh_status from employee where id='$empid'";
       $result = mysqli_query($conn, $query);
        
        if($result)
        {
          while($row = mysqli_fetch_array($result))
          {
            $response[] = array("id"=>$row["id"],"bh_id"=>$row["bh_id"],"bh_user_name"=>$row["bh_user_name"],"bh_mobileno"=>$row["bh_mobileno"],"bh_emailid"=>$row["bh_emailid"],"bh_status"=>$row["bh_status"]); 
          } 
         
        }
        else
        {
             $response[] = array("status"=>"Failure","message"=>"Failure");
        }
      
       return $response;
       
       
   }
   function  updateEmployee($con,$id,$bh_id,$name,$email,$mobile)
   {
        $query = "update employee set bh_id = '$bh_id',bh_user_name='$name',bh_mobileno='$mobile',bh_emailid='$email'  where id = '$id'";
       $result = mysqli_query($con,$query);
        if($result)
        {
            $response[] = array("status"=>"Success","message"=>"Update Success");
        }
        else
        {
         $response[] = array("status"=>"Failure","message"=>"Failure");
        }
       return $response;
   }
   function  deleteEmployee($con,$id)
   {
      $query = "update employee set bh_status = '104' where id = '$id'";
       $result = mysqli_query($con,$query);
        if($result)
        {
            $response[] = array("status"=>"Success","message"=>"Delete Success");
        }
        else
        {
         $response[] = array("status"=>"Failure","message"=>"Failure");
        }
       return $response; 
       
   }
  
   
   function checkmobileRegisteredforPush($conn,$mobileno)
   {
       $query = "select * from registerpush where mobile_no = '$mobileno'";
       $result = mysqli_query($conn, $query);
       $count = mysqli_num_rows($result);
       if($count > 0)
       {
           return true;
       }
       else
       {
           return false;
       }
   }
   
   function registerPush($conn,$mobileno,$devicetoken)
   {
       if($this->checkmobileRegisteredforPush($conn, $mobileno))
       {
           $query  = "update registerpush set token_id = '$devicetoken' where mobile_no = '$mobileno'";
           $result = mysqli_query($conn, $query);
           if($result)
           {               
               $response[] = array("status"=>"Success","message"=>"Device Token Updated Successfully");
           }
           else
           {
               $response[] = array("status"=>"failure","message"=>"Failed to update Device Tokens");
           }
       }
       else
       {
           $query  = "insert into registerpush(mobile_no,token_id) values('$mobileno','$devicetoken')";
           $result = mysqli_query($conn, $query);
           if($result)
           {               
               $response[] = array("status"=>"Success","message"=>"Device Token Added Successfully");
           }
           else
           {
               $response[] = array("status"=>"failure","message"=>"Failed to Add Device Tokens");
           }
       }
       return $response;
   }
   
   function getToken($conn,$mobileno)
   {
       $query = "select * from registerpush where mobile_no = '$mobileno'";
       $result = mysqli_query($conn, $query);
       $count = mysqli_num_rows($result);
       if($count > 0)
       {
           while($row = mysqli_fetch_array($result))
           {
               $response = $row["token_id"];
           }
       }
       else
       {
           $response = "empty";
       }
       return $response;
   }
   
}
