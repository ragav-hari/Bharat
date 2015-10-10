<?php

class OrderClass
{
    // get all orders according to date
    function getAllOrders($conn,$date)
    {
        $query  = "select o.*,od.amount_range,s.code_description from orders o join order_details od on o.order_id = od.order_id join statuscode s on s.code_id = o.order_status where o.order_status != '104' and o.order_date = '$date'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"order_status"=>$row["order_status"],"order_type"=>$this->orderType($row["amount_range"]),"status_description"=>$row["code_description"],"handled_by"=>$this->getEmployeesHandlingOrders($conn,$row["order_id"]));
            }
        }
        else 
        {
            $response[] = array("status"=>"Failure","message"=>"No orders found","error"=>$conn->error);
        }
        return $response;
    }
    
    function getOrderByType($conn,$order_type,$date)
    {
        if($order_type == 2)
        {
            $response = $this->getPlacedOrder($conn, $date);
        }
        else
        {
            $response = $this->getQuoteOrder($conn, $date);
        }
        return $response;
    }
    
    function getPlacedOrder($conn,$date)
    {
        $query  = "select o.*,od.amount_range,s.code_description from orders o join order_details od on o.order_id = od.order_id join statuscode s on s.code_id = o.order_status where o.order_status != '104' and o.order_date = '$date' and od.amount_range > 0 ";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"order_status"=>$row["order_status"],"order_type"=>$this->orderType($row["amount_range"]),"status_description"=>$row["code_description"],"handled_by"=>$this->getEmployeesHandlingOrders($conn,$row["order_id"]));
            }
        }
        else 
        {
            $response[] = array("status"=>"Failure","message"=>"No orders found","error"=>$conn->error);
        }
        return $response;
    }
    
    function getQuoteOrder($conn,$date)
    {
        $query  = "select o.*,od.amount_range,s.code_description from orders o join order_details od on o.order_id = od.order_id join statuscode s on s.code_id = o.order_status where o.order_status != '104' and o.order_date = '$date' and od.amount_range = 0 ";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"order_status"=>$row["order_status"],"order_type"=>$this->orderType($row["amount_range"]),"status_description"=>$row["code_description"],"handled_by"=>$this->getEmployeesHandlingOrders($conn,$row["order_id"]));
            }
        }
        else 
        {
            $response[] = array("status"=>"Failure","message"=>"No orders found","error"=>$conn->error);
        }
        return $response;
    }
    
    function getEmployeesHandlingOrders($conn,$order_id)
    {
        $query = "select p.emp_id,e.bh_name from processby p join employee e on e.id = p.emp_id where order_id = '$order_id' order by p.id desc";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response = array("status"=>"Success","emp_id"=>$row["emp_id"],"emp_name"=>$row["bh_name"]);
            }
        }
        else
        {
            $response = array("status"=>"Failure","error"=>$conn->error);
        }
        return $response;
    }
    // set order type values 
    function orderType($amount_id)
    {
        if($amount_id == 0)
        {
            $response = "Quote";
        }
        else
        {
            $response = "Placed";
        }
        return $response;
    }
    
    // get single order details for an order id
    function getSingleOrderDetail($conn,$order_id)
    {
        $query  = "select o.order_date,od.*,u.user_mobileno,u.user_id,type.usertype_name,scode.code_description from orders o join order_details od on o.order_id = od.order_id join users u on o.user_id = u.user_id join usertype type on type.usertype_id = od.account_type join statuscode scode on scode.code_id = o.order_status where o.order_id = '$order_id'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $response = array("status"=>"Success","order_date"=>$row["order_date"],"order_status"=>$row["code_description"],"first_name"=>$row["first_name"],
                                  "email_id"=>$row["email_id"],"address"=>$row["address"],"pincode"=>$row["pincode"],
                                  "landmark"=>$row["landmark"],"account_type"=>$row["account_type"],"usertype_name"=>$row["usertype_name"],"dealer_code"=>$row["dealer_code"],
                                  "comments"=>$row["comments"],"amount_range"=>$row["amount_range"],"gift"=>$row["gift"],"user_id"=>$row["user_id"],
                                  "user_mobileno"=>$row["user_mobileno"],"order_items"=>$this->getOrderItems($conn, $order_id),
                                  "amountDetails"=>$this->getAmountRange($conn, $row["amount_range"]),"giftDetails"=>$this->getGift($conn, $row["gift"]),
                                  "invoiceDetails"=>$this->getInvoiceofOrder($conn,$order_id));
            }
        }
        else 
        {
            $response[] = array("status"=>"Failure","message"=>"No orders found");
        }
        return $response;
    }
    
    // get order images and audio
    function getOrderItems($conn,$order_id)
    {
        $query  = "select * from orderitem where order_id = '$order_id'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                 $response[] = array("status"=>"Success","item_type"=>$row["item_type"],"item_name"=>$row["item_name"],'item_url'=>$row["item_url"]);        
            }  
        }
        else
        {
            $response[] = array("status"=>"Failure","message"=>"No Order Items Found");
        }
        return $response;
    }
    
    
    function getAmountRange($conn,$amount_range)
    {
        if($amount_range != 0)
        {
           $response = $this->getAmountDetails($conn, $amount_range);
        }
        else
        {
            $response = array("status"=>"Failure");
        }
        return $response;
    }
    
    function getGift($conn,$gift)
    {
        if($gift != 0)
        {
            $response = $this->getGiftDetails($conn, $gift);
        }
        else
        {
            $response = array("status"=>"Failure");
        }
        return $response;
    }
    
    function getAmountDetails($conn,$amountid)
    {
         $query  = "select * from amount_range where id = '$amountid'";
         $result = mysqli_query($conn, $query);
         $count  = mysqli_num_rows($result);
         
         if($count > 0)
         {
             while($row = mysqli_fetch_array($result))
             {
                 $response = array("status"=>"Success","amount_from"=>$row["amount_from"],"amount_to"=>$row["amount_to"]);
             }
         }
         else
         {
             $response = array("status"=>"Failure");
         }
         return $response;
    }
    
    function getGiftDetails($conn,$giftid)
    {
         $query  = "select * from gift where id = '$giftid'";
         $result = mysqli_query($conn, $query);
         $count  = mysqli_num_rows($result);
         
         if($count > 0)
         {
             while($row = mysqli_fetch_array($result))
             {
                 $response = array("status"=>"Success","gift_name"=>$row["gift_name"]);
             }
         }
         else
         {
             $response = array("status"=>"Failure");
         }
         return $response;
    }
    
    function getInvoiceofOrder($conn,$order_id)
    {
        $query  = "select * from invoice where order_id = '$order_id'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","file_name"=>$row["file_name"],"file_path"=>$row["file_url"]);
            }
        }
        else
        {
             $response[] = array("status"=>"Failure");
        }
        return $response;
    }

    function generateInvoiceandUpdateOrder($con,$order_id,$filename,$target_path,$user_id,$order_type)
    {
        $query  = "insert into invoice(order_id,file_name,file_url,user_id) values('$order_id','$filename','$target_path','$user_id')";
        $result = mysqli_query($con, $query);
        if($result)
        {
            if($order_type == "Quote")
            {
                $response[] = array("status"=>"Success","file_path"=>$target_path,"order_status"=>"","OrderDetail"=>$this->updateOrderStatus($con, $order_id, "106"));
            }
            else
            {
                 $response[] = array("status"=>"Success","file_path"=>$target_path,"order_status"=>"","OrderDetail"=>$this->updateOrderStatus($con, $order_id, "107"));
            }
        }
        else
        {
            $response[] = array("status"=>"Failure","message"=>"Failed to upload");
        }
        return $response;
    }
    
    function updateOrderStatus($con,$order_id,$status)
    {
        $query  = "update orders set order_status = '$status' where order_id = '$order_id'";
        $result = mysqli_query($con, $query);
        if($result)
        {
            $response = array("status"=>"Success","message"=>"Order updated Successfully");
        }
        else
        {
            $response = array("status"=>"Failure","message"=>$con->error);
        }
        return $response;
    }
    
    function checkorAssignOrder($conn,$user_id,$order_id)
    {
        $checkadmin = $this->checkUserisAdmin($conn,$user_id);
        if($checkadmin)
        {
            $response = array("status"=>"Success");
        }
        else
        {
                $status = $this->checkiforderisassigned($conn, $user_id, $order_id);
        
                if($status == 1)
                {
                    $response = array("status"=>"Success","no"=>$status);
                }
                else if($status == 2) 
                {
                    $response = array("status"=>"Failure","no"=>$status);
                }
                else if($status == 3)
                {
                    $response = array("status"=>"Success","no"=>$status);
                }
                else
                {
                    $response = array("status"=>"Failure","no"=>$status);
                }
        }
       
        return $response;
    }
    
    function checkUserisAdmin($conn,$user_id)
    {
        $query = "select user_role from employee where id = '$user_id'";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($result))
        {
            $role = $row["user_role"];
            if($role == 1)
            {
                $response = true;
            }
            else
            {
                $response = false;
            }
        }
        return $response;
    }
    
    function checkiforderisassigned($conn,$user_id,$order_id)
    {
        $query  = "select * from processby where order_id = '$order_id'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                if($row["emp_id"] == $user_id)
                {
                    $response = 1; //already assigned to me
                }
                else
                {
                    $response = 2; //already assigned to others
                }
            }
        }
        else
        {
            $response = $this->assignOrdertoEmployee($conn, $user_id, $order_id); 
        }
        return $response;
    }
    
    function assignOrdertoEmployee($conn,$user_id,$order_id)
    {
        $query  = "insert into processby(order_id,emp_id) values($order_id,$user_id)";
        $result = mysqli_query($conn, $query);
        
        if($result)
        {
            $response = 3; // assigned to me
        }
        else
        {
            $response = 4; // error
        }
        return $response;
    }
    
    function getOrdersForEmployee($conn, $date,$user_id)
    {
        $query  = "select o.*,od.amount_range,s.code_description from orders o join order_details od on o.order_id = od.order_id join statuscode s on s.code_id = o.order_status join processby pb on pb.order_id = o.order_id where o.order_status != '104' and o.order_date = '$date' and pb.emp_id = '$user_id'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"order_status"=>$row["order_status"],"order_type"=>$this->orderType($row["amount_range"]),"status_description"=>$row["code_description"]);
            }
        }
        else 
        {
            $response[] = array("status"=>"Failure","message"=>"No orders found","error"=>$conn->error);
        }
        return $response;
    }
    
    function allocOrderToEmployee($conn,$order_id,$assign_to)
    {
        $query = "update processby set emp_id = '$assign_to' where order_id = '$order_id'";
        $result = mysqli_query($conn, $query);
        
        if($result)
        {
            $response = array("status"=>"Success");
        }
        else
        {
            $response = array("status"=>"Failure","error"=>$conn->error);
        }
        return $response;
    }
    
    function insertPushMessage($con,$userid,$mobile,$orderid,$title,$message)
    {  
        $current_date= gmdate('Y-m-d h:i:s ');
        $query = "insert into push_message(cust_id,order_id,title,message,send_date_time,customer_mobile) values('$userid','$orderid','$title','$message','$current_date','$mobile')";
        $result = mysqli_query($con, $query);
        if($result)
        {
          $res = $this->sendPushFromServer($con,$mobile,$title,$message);
          $response[] = array("status"=>"Success","message"=>"INSERT Success","Push"=>$res);
        }
        else
        {
          $response[] = array("status"=>$con->error,"message"=>"");
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
            return false;
        }

        curl_close($ch);   
        return true;

    }
}
