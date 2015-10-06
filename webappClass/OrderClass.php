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
                $response[] = array("status"=>"Success","order_id"=>$row["order_id"],"order_date"=>$row["order_date"],"order_status"=>$row["order_status"],"order_type"=>$this->orderType($row["amount_range"]),"status_description"=>$row["code_description"]);
            }
        }
        else 
        {
            $response[] = array("status"=>"Failure","message"=>"No orders found");
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
        $query  = "select o.order_date,od.*,u.user_mobileno,type.usertype_name,scode.code_description from orders o join order_details od on o.order_id = od.order_id join users u on o.user_id = u.user_id join usertype type on type.usertype_id = od.account_type join statuscode scode on scode.code_id = o.order_status where o.order_id = '$order_id'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $response = array("status"=>"Success","order_date"=>$row["order_date"],"order_status"=>$row["code_description"],"first_name"=>$row["first_name"],
                                  "email_id"=>$row["email_id"],"address"=>$row["address"],"pincode"=>$row["pincode"],
                                  "landmark"=>$row["landmark"],"account_type"=>$row["account_type"],"usertype_name"=>$row["usertype_name"],"dealer_code"=>$row["dealer_code"],
                                  "comments"=>$row["comments"],"amount_range"=>$row["amount_range"],"gift"=>$row["gift"],
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
                $response[] = array("status"=>"Success","file_name"=>$row["file_name"],"file_url"=>$row["file_url"]);
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
            if($order_type == "Placed")
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
}
