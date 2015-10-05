<?php

class OrderClass
{
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
}
