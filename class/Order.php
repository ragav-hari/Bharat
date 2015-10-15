<?php
define("SERVERHOST","http://www.cloudservices.ashinetech.com/Bharat/uploads/");
 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Order
 *
 * @author USER
 */
class Order
{

    function  viewOrder($con)
    {
        $current_date= date("Y/m/d"); 
        $query="select o.order_id, o.order_date,u.user_first_name,st.code_description from orders o
 join users u on u.user_id=o.user_id
  join statuscode st on st.code_id = o.order_status
where o.order_date='$current_date'";
       $result = mysqli_query($con,$query);
       while($row = mysqli_fetch_array($result))
       {
           $response[] = array("order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"user_first_name"=>$row["user_first_name"],"order_status"=>$row["code_description"],"emp_name"=>$this->getEmpofOrder($con,$row["order_id"])); 
       }
       return $response;
     
    }
    function  getEmpofOrder($con,$orderid)
    {
        
        $query="select e.bh_user_name from processby p
join orders o on o.order_id = p.order_id
join employee e on e.id = p.emp_id
where o.order_id='$orderid'";
       $result = mysqli_query($con,$query);
       $count=  mysqli_num_rows($result);
       if($count>0)
       {
        while($row = mysqli_fetch_array($result))
       {
           $response = $row["bh_user_name"]; 
       }  
           
       }
       else
       {
            $response ="Employee Not Assigned"; 
       }
      
       return $response;
     
    }
            
      function  getFullOrder($con,$orderid)
    {  
         
       $query= "select od.amount_range,od.gift from order_details od
join orders o on o.order_id = od.order_id where o.order_id='$orderid'"; 
     $result=  mysqli_query($con, $query);
     while($row=  mysqli_fetch_array($result))
     {
         
       $amount=$row["amount_range"];
       $gift=$row["gift"];
       
       if(($amount!=0)&&($gift!=0))
       {
           
           $query="select o.order_id , o.order_date,st.code_description, oi.item_type,oi.item_name,oi.item_url,u.user_id,u.user_first_name,u.user_email,u.user_mobileno, ut.usertype_name,ar.amount_from,ar.amount_to,g.gift_name from orders o 
       join orderitem oi
       on oi.order_id=o.order_id
       join order_details od on od.order_id = o.order_id
       join gift g on g.id= od.gift
       join amount_range ar on ar.id =  od.amount_range
       join users u
       on u.user_id=o.user_id
       join usertype ut 
       on ut.usertype_id=u.user_type
       join statuscode st on st.code_id = o.order_status
       where o.order_id='$orderid'";
       
        $result=  mysqli_query($con, $query);
       while($row=  mysqli_fetch_array($result))
     {
   
        $response[]=  array("order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"order_status"=>$row["code_description"],"item_type"=>$row["item_type"],"item_name"=>$row["item_name"],"item_url"=>$row["item_url"],"user_first_name"=>$row["user_first_name"],"user_email"=>$row["user_email"],"user_mobileno"=>$row["user_mobileno"],"usertype_name"=>$row["usertype_name"],"user_id"=>$row["user_id"],"amount_from"=>$row["amount_from"],"amount_to"=>$row["amount_to"],"gift_name"=>$row["gift_name"]);
     
     }
           
       }
            

//  $response[]=  array("order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"order_status"=>$row["code_description"],"item_type"=>$row["item_type"],"item_url"=>$row["item_url"],"user_first_name"=>$row["user_first_name"],"user_email"=>$row["user_email"],"user_mobileno"=>$row["user_mobileno"],"usertype_name"=>$row["usertype_name"],"user_id"=>$row["user_id"]);
     
     }
         return $response;
     
        
    }
   
     function  generateInvoice($con,$orderid,$filename,$target_path)
    {
           
     
           $query = "insert into invoice(order_id,file_name,file_url) values('$orderid','$filename','$target_path')";
           $result = mysqli_query($con, $query);
           if($result)
           {
             
               $response = array("status"=>"Success","message"=>"File Upload Success","targetpath"=>$target_path);
               
           }
           else
           {
               $response = array("status"=>$con->error,"message"=>"");
           }
           return $response; 
       
    }
    function  checkorderExists($con,$orderid,$empid)
    {
        $query="select emp_id from processby where order_id='$orderid'";
        $result=  mysqli_query($con, $query);
        $count = mysqli_num_rows($result);
       if($count > 0)
       {
         while($row =  mysqli_fetch_array($result))
         {
             $emp_id=$row["emp_id"];
             if($emp_id==$empid)
             {
                    $response =array("status"=>"allow","message"=>"");
              
             }
             else
             {
                  $response =array("status"=>"deny","message"=>"");
                 
             }
             
         }
       }
       else
       {
            $query = "insert into processby(order_id,emp_id) values('$orderid','$empid')";
             $result = mysqli_query($con, $query);
           if($result)
           {
             
              $response =array("status"=>"allow","message"=>"");
           }
           else
           {
               $response = array("status"=>"LOL","message"=>"");
           }
           
       }
       return $response; 
    }
            function  insertEmp($con,$orderid,$empid)
    {
           
          $query="select *from processby where order_id='$orderid' and emp_id='$empid'";
          $result=  mysqli_query($con,$query);
          if(result)
          {
               $query = "insert into processby(order_id,emp_id) values('$orderid','$empid')";
              $result = mysqli_query($con, $query);
           if($result)
           {
             
               $response = array("status"=>"Success","message"=>"");
           }
           else
           {
               $response = array("status"=>$con->error,"message"=>"");
           }
              
          }
          else
          {
              $response =array("status"=>"Cannot Insert","message"=>"");
              
              
          }
        
        
          
           return $response;
        
    }
    
    function  getOrderByDate($con,$date)
    {
          $query="select o.order_id, o.order_date,u.user_first_name,st.code_description from orders o
 join users u on u.user_id=o.user_id
 join statuscode st on st.code_id = o.order_status
where o.order_date='$date'";
       $result = mysqli_query($con,$query);
       $count=  mysqli_num_rows($result);
       if($count>0)
       {
        while($row = mysqli_fetch_array($result))
       {
           
             $response[] = array("order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"user_first_name"=>$row["user_first_name"],"order_status"=>$row["code_description"],"emp_name"=>$this->getEmpofOrder($con,$row["order_id"])); 
       
            
       }
        }
       else
       {
          //  $response = array("status"=>"Failure","message"=>"No Orders Present At this Date");
           $response[] = array("order_id"=>"NO Orders Found At this Date","order_date"=>"NULL","user_first_name"=>"NULL","order_status"=>"NULL","emp_name"=>"NULL"); 
       
       }
       
       return $response;
       
    }
    
    function  changeOrderStatus($con,$orderid)
    {
        
        $query="update orders set order_status='106' where order_id='$orderid'";
        $result = mysqli_query($con, $query);
           if($result)
           {
               
               $response = array("status"=>"Success","message"=>"Order Status Updated");
           }
           else
           {
                $response = array("status"=>"Failure","message"=>"Failed");
           }
           return $response;
    }
    function  insertPushMessage($con,$userid,$mobile,$orderid,$title,$message)
    {  
        $current_date= gmdate('Y-m-d h:i:s \G\M\T');
       $query = "insert into push_message(cust_id,order_id,title,message,send_date_time,customer_mobile) values('$userid','$orderid','$title','$message','$current_date','$mobile')";
           $result = mysqli_query($con, $query);
           if($result)
           {
             $this->sendPushFromServer($con,$mobile,$title,$message);
               $response[] = array("status"=>"Success","message"=>"INSERT Success");
               
           }
           else
           {
               $response[] = array("status"=>$con->error,"message"=>"");
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
    
    function getAllOrderList($conn,$mobileno)
    {
        $userid = $this->getUserID($conn, $mobileno);
        $query = "select * from orders o join statuscode s on s.code_id = o.order_status join order_details od on od.order_id = o.order_id where o.user_id = $userid and s.code_id != '104'";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"statuscode"=>$row["order_status"],"status"=>$row["code_description"],"amount"=>$row["amount_range"]);
            }
        }
        else
        {
            $response[] = array("status"=>"Failure","message"=>$conn->error);
        }

        return $response;
    }
    
    function getallordersformobile($conn,$mobileno)
    {
        $userid = $this->getUserID($conn, $mobileno);
        $query = "select * from orders o join statuscode s on s.code_id = o.order_status join order_details od on od.order_id = o.order_id where o.user_id = $userid";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"statuscode"=>$row["order_status"],"status"=>$row["code_description"],"amount"=>$row["amount_range"]);
            }
        }
        else
        {
            $response[] = array("status"=>"Failure","message"=>$conn->error);
        }

        return $response;

    }
            
    function viewSingleOrderDetail($conn,$order_id)
    {
        $hostname = "";
        $query = "select * from invoice i join orders o on o.order_id = i.order_id where i.order_id = '$order_id'";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        if($result > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
            $response[] = array("Status"=>"Success","order_id"=>$row["order_id"],"file_name"=>$row["file_name"],"file_url"=>SERVERHOST.$row["file_name"],"order_status"=>$row["order_status"]);
            }
        }
        else
        {
            $response[] = array("Status"=>"Failure","Invoice Not Generated");
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
   
   function  getALLDatas($con,$orderid)
   {
     $query = "select * from orderitem where order_id = '$orderid'";
       $result = mysqli_query($con, $query);
       $count = mysqli_num_rows($result);
       if($count > 0)
       {
           while($row = mysqli_fetch_array($result))
           {
               $response[] = array("status"=>"success","item_type"=>$row["item_type"],"item_name"=>$row["item_name"],"item_url"=>$row["item_url"]);
           }
       }
       else
       {
           $response[] =  array("status"=>"failure","error"=>$con->error,"order_id"=>$orderid);
       }
       return $response;  
   }
   
   function selectItemForDelete($con,$name,$orderid)
   {
       $query = "select * from orderitem where order_id = $orderid";
       $result = mysqli_query($con, $query);
       $count = mysqli_num_rows($result);
       if($count > 0)
       {
          while($row = mysqli_fetch_array($result))
          {
             if($row["item_name"] == rawurlencode($name))
             {
                 $id = $row["item_id"];
             }
             else
             {
                 $id = "null";
             }
          }
       }
       else
       {
           $id = "null";
       }
      
       return $id;
   }
   
   function  deleteImage($con,$name,$orderid)
   {
       $id = $this->selectItemForDelete($con, $name, $orderid);
       
       if($id == "null")
       {
           $response[] = array("status"=>"Failure","message"=>"No ID Associated","error"=>$con->error,"file"=>$name,"ORDERID"=>$orderid);
       }
       else
       {
        $query = "delete from orderitem where item_id = '$id'"; 
        $result = mysqli_query($con,$query);
           if($result)
           {
               $response[] = array("status"=>"Success","message"=>"Deleted Successfully");
           }
           else
           {
                $response[] = array("status"=>"Failure","message"=>"Failed","error"=>$con->error,"file"=>$name,"ID"=>$id,"ORDERID"=>$orderid);
           }
       }
       return $response;
       
   }
   
   function changeOrderStatusofOrder($conn,$order_id,$status)
   {
       $query = "update orders set order_status = '$status' where order_id = '$order_id'";
       $result = mysqli_query($conn, $query);
       
       if($result)
       {
           $response[] = array("status"=>"Success","message"=>"Order Updated Successfully");
       }
       else
       {
           $response[] = array("status"=>"Failure","message"=>"Order Updation Failure");
       }
       return $response;
   }
   
   function sendPushFromServer($conn,$mobile,$title,$message)
    {
        $apiKey = "AIzaSyCWa_0lJ3Nne8K8Nv8Tj9JwMc-a57L0Idk";

        $registrationIDs = array($this->getToken($conn, $mobile));

        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $registrationIDs,
            'data' => array("title"=>$title,"message" => $message ),
        );
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

    // Open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            echo false;
        }

        curl_close($ch);   
        echo true;

    }
}
