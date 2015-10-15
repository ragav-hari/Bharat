(function(){

    bharat.factory('ordersService',['$q','$http',ordersService]);
    
    function ordersService($q,$http)
    {
        return{
            getAllOrders                :   getAllOrders,
            getSingleOrderDetail        :   getSingleOrderDetail,   
            checkorAssignOrderForUser   :   checkorAssignOrderForUser,
            sendPushNotification        :   sendPushNotification,
            getOrdersForEmployee        :   getOrdersForEmployee,
            allocOrderstoEmployee       :   allocOrderstoEmployee,
            getOrderByOrderType         :   getOrderByOrderType
        }
        
        function getAllOrders(data)
        {
            return $http({method: 'POST',data:data,url:HOST+GET_ALL_ORDERS}).then(function(response){return response.data;});
        }
        
        function getSingleOrderDetail(data)
        {
            return $http({method: 'POST',data:data,url:HOST+GET_SINGLE_ORDER_DETAIL}).then(function(response){return response.data;});
        }
        
        function checkorAssignOrderForUser(data)
        {
            return $http({method: 'POST',data:data,url:HOST+CHECK_OR_ASSIGN_ORDER}).then(function(response){return response.data;});
        }
        
        function sendPushNotification(data)
        {
            return $http({method: 'POST',data:data,url:HOST+SEND_PUSH_NOTIFICATION}).then(function(response){return response.data;});
        }
        
        function getOrdersForEmployee(data)
        {
            return $http({method: 'POST',data:data,url:HOST+GET_ORDERS_FOR_EMPLOYEE}).then(function(response){return response.data;});
        }
        
        function allocOrderstoEmployee(data)
        {
            return $http({method: 'POST',data:data,url:HOST+ALLOC_ORDER_To_EMPLOYEE}).then(function(response){return response.data;});
        }
        
        function getOrderByOrderType(data)
        {
            return $http({method: 'POST',data:data,url:HOST+GET_ORDER_BY_TYPE}).then(function(response){return response.data;});
        }
    }
}())