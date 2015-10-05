(function(){

    bharatApp.factory('fullOrderService',['$q','$http',fullOrderService]);
    
    function fullOrderService($q,$http)
    {
        return{
            getfullOrders :  getfullOrders
        }
        
        function  getfullOrders(data)
        { 
            
            alert("IN serivce call"+HOSTNEW+GETFULLORDER);
            alert(data);
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+GETFULLORDER
              })
              .then(function(response){
                   return response.data;
               })
              .catch(function(error){
                   alert(JSON.stringify(error));
               });
            
        }
    }
    
    
    
    
    
    
    
  
}());

