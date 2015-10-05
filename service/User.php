<?php

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
           $response = array("status"=>"Exists","message"=>"Account Already Exists");
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
               $response = array("status"=>"Failure","message"=>"Failed to add Phone Number");
           }
           return $response;
   }
   
   function updateOTPForPhoneno($con,$phoneno,$status)
   {
           $randomkey = $this->generateOTP();
           $query = "update otpkeys set user_random_key = '$randomkey',otpkey_status='$status' where user_mobileno = '$phoneno'";
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
   
   function sendSMS($phoneno,$randomkey)
   {
       // for temporary purpose
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
               $response = array("status"=>"Success","message"=>"Mobile Code Verified Successfully");
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
   
   function addUserDetails($con, $user_first_name,$user_last_name,$user_email,$user_address,$user_mobileno)
   {
        $query = "update users set user_first_name = '$user_first_name',user_last_name='$user_last_name',user_email='$user_email',user_address='$user_address' where user_mobileno = '$user_mobileno'";
        $result = mysqli_query($con, $query);
        if($result)
        {
            $response = array("status"=>"Success","message"=>"User profile Updated Successfully");
        }
        else
        {
            $response = array("status"=>"Failure","message"=>"Failed to Update User Profile");
        }
        return $response;
   }
   
}
