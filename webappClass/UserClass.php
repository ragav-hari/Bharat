<?php

class UserClass
{
    function userLogin($conn,$loginid,$password)
    {
        $query  = "select * from employee where bh_emailid = '$loginid' and bh_user_password = '$password' and bh_status = '103'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response = array("status"=>"Success",
                                    "id"=>$row["id"],
                                    "bh_id"=>$row["bh_id"],
                                    "name"=>$row["bh_name"],
                                    "username"=>$row["bh_user_name"],
                                    "mobileno"=>$row["bh_mobileno"],
                                    "emailid"=>$row["bh_emailid"],
                                    "preloaddata"=>$this->getPreLoadData($conn,$row["user_role"]));
            }
        }
        else
        {
            $response = array("status"=>"Failure","message"=>"Login Failure");
        }
        return $response;
    }
    
    function getPreLoadData($conn,$roleid)
    {
        $response = array("responsibilities"=>$this->getResponsibility($conn, $roleid),
                          "menus"=>$this->getMenu($conn, $roleid));
        return $response;
    }
    
    function getResponsibility($conn,$roleid)
    {
        $query = "select resp_id from role_resp_mapping where role_id = '$roleid'";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("id"=>$row["resp_id"]);
            }
        }
        else
        {
            $response[] = array();
        }
        
        return $response;
    }
    
    function getMenu($conn,$roleid)
    {
        $query = "select m.name as menu_name,m.url as menu_url,m.icon as menu_icon from menu m join menu_role_mapping mrp on m.id = mrp.menu_id where mrp.role_id = '$roleid'";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("name"=>$row["menu_name"],
                                    "url"=>$row["menu_url"],
                                    "icon"=>$row["menu_icon"]);
            }
        }
        else
        {
            $response[] = array();
        }
        
        return $response;
    }
    
    function getAllUserDetails($conn)
    {
        $query  = "select emp.*,status.code_description,r.name as role_name from employee emp join statuscode status on status.code_id = emp.bh_status join roles r on r.id = emp.user_role";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","id"=>$row["id"],"bh_id"=>$row["bh_id"],"bh_name"=>$row["bh_name"],
                                    "bh_user_name"=>$row["bh_user_name"],"bh_mobileno"=>$row["bh_mobileno"],
                                    "bh_emailid"=>$row["bh_emailid"],"code_description"=>$row["code_description"],"role_name"=>$row["role_name"]);
            }
        }
        else
        {
            $response[] = array("status"=>"Failure","message"=>$conn->error);
        }
        return $response;
    }
    
    
    function getUserPreloadData($conn)
    {
        $query  = "select * from roles";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","role_id"=>$row["id"],"role_name"=>$row["name"]);
            }
        }
        else
        {
            $response[] = array("status"=>"Failure");
        }
        return $response;
    }
    
    function addUserDetail($conn,$bh_id,$bh_name,$bh_user_name,$bh_password,$bh_mobno,$bh_email,$bh_userrole)
    {
        if($this->checkEmailExists($conn, $bh_email))
        {
            $response[] = array("status"=>"Failure","message"=>"Email Already Exists");
        }
        else
        {
            $query = "insert into employee(bh_id,bh_name,bh_user_name,bh_user_password,bh_mobileno,bh_emailid,user_role) values ('$bh_id','$bh_name','$bh_user_name','$bh_password','$bh_mobno','$bh_email','$bh_userrole')";
            $result = mysqli_query($conn, $query);
            if($result)
            {
                $response[] = array("status"=>"Success","message"=>"Profile Created Successfully");
            }
            else 
            {
               $response[] = array("status"=>"Failure","message"=>"Profile Creation Error","Source"=>$conn->error); 
            }
        }
        return $response;
        
    }
    
    function checkEmailExists($conn,$email)
    {
        $query = "select * from employee where bh_emailid = '$email'";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            $response = true;
        }
        else
        {
            $response = false;
        }
        return $response;
    }
    
    function getUserById($conn,$user_id)
    {
        $query = "select * from employee where id = '$user_id'";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($result))
        {
            $response[] = array("status"=>"Success","id"=>$row["id"],"bh_id"=>$row["bh_id"],"bh_name"=>$row["bh_name"],
                                    "bh_user_name"=>$row["bh_user_name"],"bh_mobileno"=>$row["bh_mobileno"],
                                    "bh_emailid"=>$row["bh_emailid"],"bh_user_role"=>$row["user_role"]);
        }
        return $response;
    }
    
    function editUserDetail($conn,$id,$bh_id,$bh_name,$bh_user_name,$bh_mobno,$bh_email,$bh_userrole)
    {
        $query = "update employee set bh_id = '$bh_id' , bh_name = '$bh_name' , bh_user_name = '$bh_user_name' , bh_mobileno = '$bh_mobno' , bh_emailid = '$bh_email' , user_role = '$bh_userrole' where id = '$id'";
        $result = mysqli_query($conn,$query);
        if($result)
        {
            $response[] = array("status"=>"Success","message"=>"Profile Updated Successfully");
        }
        else 
        {
           $response[] = array("status"=>"Failure","message"=>"Profile Updation Error","Source"=>$conn->error); 
        }
        return $response;
    }
    
    function deleteUserDetail($conn,$userid)
    {
        $query = "update employee set bh_status = '104' where id = '$userid'";
        $result = mysqli_query($conn, $query);
        
        if($result)
        {
            $response[] = array("status"=>"Success","message"=>"Profile Deactivated Successfully");
        }
        else 
        {
           $response[] = array("status"=>"Failure","message"=>"Profile Deactivation Error","Source"=>$conn->error); 
        }
        return $response;
    }
    
    function generateRandomCode()
    {
       $digits = 5;
       return rand(pow(10, $digits-1), pow(10, $digits)-1);
    }
    
    function isEmailExisits($conn,$email)
    {
        $query = "select * from employee where bh_emailid = '$email'";
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
    
    function forgotpassword($conn,$user_email)
    {
        $isemailexist = $this->isEmailExisits($conn,$user_email);
        
        if($isemailexist)
        {
            $randomcode = $this->generateRandomCode();
            $query = "insert into forgotpassword(email,code) values('$user_email','$randomcode')";
            $result = mysqli_query($conn, $query);
            if($result)
            {
                $response = array("status"=>"Success","message"=>"Random Code Inserted");
            }
            else
            {
                $response = array("status"=>"Failed","message"=>"Error please try again","error"=>$conn->error);
            }
        }
        else
        {
            $response = array("status"=>"Failed","message"=>"Please enter a valid email");
        }
        return $response;
    }
    
    function verifyCode($conn,$user_email,$user_code)
    {
        $query = "select * from forgotpassword where email = '$user_email' order by id desc limit 1";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $code = $row["code"];
                if($code == $user_code)
                {
                   $response = array("status"=>"Success","message"=>"Code Verified"); 
                }
                else
                {
                   $response = array("status"=>"Failure","message"=>"Please Enter a valid code");
                }
            }
        }
        else
        {
            $response = array("status"=>"Failure","message"=>"Please Enter a valid code");
        }
        return $response;
    }
    
    function changeforgottenpassword($conn,$user_email,$user_password)
    {
        $query = "update employee set bh_user_password = '$user_password' where bh_emailid = '$user_email'";
        $result = mysqli_query($conn, $query);
        if($result)
        {
           $response = array("status"=>"Success","message"=>"Password Changed Successfully");  
        }
        else
        {
            $response = array("status"=>"Failure","message"=>"Error Please try again","error"=>$conn->error); 
        }
        return $response;
    }
    
    function chechPasswordisCorrect($conn,$user_id,$old_password)
    {
        $query  = "select * from employee where id = '$user_id' and bh_user_password = '$old_password'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function changepassword($conn,$user_id,$old_password,$new_password)
    {
        $check = $this->chechPasswordisCorrect($conn,$user_id,$old_password);
        if($check)
        {
            $query  = "update employee set bh_user_password = '$new_password' where id = '$user_id'";
            $result = mysqli_query($conn, $query);
            
            if($result)
            {
                $response = array("status"=>"Success","message"=>"Password Changed Successfully");  
            }
            else 
            {
                $response = array("status"=>"Failure","message"=>"Error Please try again","error"=>$conn->error);  
            }
        }
        else
        {
            $response = array("status"=>"Failure","message"=>"Incorrect Old Password");  
        }
        return $response;
    }
}
