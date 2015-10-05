(function(){

    bharatApp.factory('ordersService',['$q','$http',ordersService]);
    
    function ordersService($q,$http)
    {
        return{
            viewOrders :  viewOrders,           
            sendOrderID : sendOrderID,
             getfullOrders :  getfullOrders,
            insertEmpID : insertEmpID,
            getOrderByDate :  getOrderByDate,
            changeOrderStatus : changeOrderStatus,
            sendPush : sendPush,

        }
        
        function  viewOrders()
        {
             return $http({method: 'POST',url:HOSTNEW+GETALLORDERS}).then(function(response){
                   return response.data;
               })
              .catch(function(error){
                   return error;
               });
        }
        
        function sendOrderID(data)
        {
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+FILEUPLOAD1
              })
              .then(function(response){
                   return response.data;
               })
              .catch(function(error){
                    return error;
               });
            
        }
        function  getfullOrders(data)
        {  
            
              console.log("GET FULL SER CALLEd");
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+GETFULLORDER
              })
              .then(function(response){
                   console.log("SERVICE DATA" +JSON.stringify(response));
                   return response.data;
               })
              .catch(function(error){
                   console.log("SERVICE ERROR" +JSON.stringify(error));
                 return error;
               });
            
        }
        function insertEmpID(data)
        {
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+EMPID
              })
              .then(function(response){
                   return response.data;
               })
              .catch(function(error){
                   return error;
               });
            
        }
        function  getOrderByDate(data)
        {
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+ORDERDATE
              })
              .then(function(response){
                   return response.data;
               })
              .catch(function(error){
                    return error;
               });
            
        }
         function  changeOrderStatus(data)
        {  
            
              console.log("GET FULL SER CALLEd");
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+ORDERSTATUS
              })
              .then(function(response){
                   console.log("SERVICE DATA" +JSON.stringify(response));
                   return response.data;
               })
              .catch(function(error){
                   console.log("SERVICE ERROR" +JSON.stringify(error));
                 return error;
               });
            
        }
        function   sendPush(data)
        {  
            
              console.log("GET FULL SER CALLEd");
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+PUSHDATA
              })
              .then(function(response){
                   console.log("SERVICE DATA" +JSON.stringify(response));
                   return response.data;
               })
              .catch(function(error){
                   console.log("SERVICE ERROR" +JSON.stringify(error));
                 return error;
               });
            
        }
  
        
    }
  
}());

