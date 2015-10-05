(function(){

    bharat.factory('ordersService',['$q','$http',ordersService]);
    
    function ordersService($q,$http)
    {
        return{
            getAllOrders : getAllOrders,
        }
        
        function getAllOrders(data)
        {
            return $http({method: 'POST',data:data,url:HOST+GET_ALL_ORDERS}).then(function(response){return response.data;});
        }
    }
}())