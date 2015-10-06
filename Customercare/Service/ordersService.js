(function(){

    bharat.factory('ordersService',['$q','$http',ordersService]);
    
    function ordersService($q,$http)
    {
        return{
            getAllOrders         : getAllOrders,
            getSingleOrderDetail : getSingleOrderDetail   
        }
        
        function getAllOrders(data)
        {
            return $http({method: 'POST',data:data,url:HOST+GET_ALL_ORDERS}).then(function(response){return response.data;});
        }
        
        function getSingleOrderDetail(data)
        {
            return $http({method: 'POST',data:data,url:HOST+GET_SINGLE_ORDER_DETAIL}).then(function(response){return response.data;});
        }
    }
}())