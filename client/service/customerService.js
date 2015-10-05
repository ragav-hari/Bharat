(function(){

    bharatApp.factory('customerService',['$q','$http',customerService]);
    
    function customerService($q,$http)
    {
        return{
            getAllCustomerDetails : getAllCustomerDetails
        }
        
        function getAllCustomerDetails()
        {
             return $http({method: 'POST',url:HOST+GETALLCUSTOMERS}).then(function(response){return response;});
        }
    }
   
}());