(function(){
    
    bharatApp.controller('ordersController',['$q','$scope','$filter','$rootScope','$location', 'Upload', '$timeout','ordersService',ordersController]);
    
    function ordersController($q,$scope, $filter,$rootScope,$location,Upload,$timeout,ordersService)
    {
       
      /* $scope.gridOptions = {
                    enablePaginationControls: true,    
                    paginationPageSizes: [2,10, 20, 30],
                    paginationPageSize: 2,
                    columnDefs: [
                      { name: 'order_id' },
                      { name: 'order_date' },
                      { name: 'user_first_name' },
                      { name: 'order_status'}
                    ]
            };
            */
    
        
        /*ordersService.viewOrders().then(function(response){
           console.log("SCOPE",response.data);
           $scope.gridOptions.data = response.data;
           
         
       });
       */
        
       
         
      
      
       var userid=sessionStorage.getItem("userid");
       console.log("user id is"+userid);
       
       if(userid)
       {
          
      $scope.gridOptions = {
          
        
    enableRowSelection: true,
    enableSelectAll: true,
     multiSelect:true,
    selectionRowHeaderWidth: 35,
    rowHeight: 35,
    showGridFooter:true
  };

   $scope.gridOptions.columnDefs = [
         { name: 'order_id' ,enableCellEdit: false},
         {  name: 'order_date'},
         { name: 'user_first_name'},
         { name: 'order_status' },
          {name : 'emp_name'}
       ];
       
         $scope.gridOptions.multiSelect = false;
        $scope.gridOptions.enablePaginationControls=true;
          ordersService.viewOrders().then(function(response){
          // console.log("SCOPE",response);
           console.log("JSON STRING in GRID" +JSON.stringify(response));
          
           $scope.gridOptions.data = response;
            $rootScope.da=response;
       });
       
       
       
          $scope.gridOptions.onRegisterApi = function(gridApi){
      //set gridApi on scope
      $scope.gridApi = gridApi;
      gridApi.selection.on.rowSelectionChanged($scope,function(row){
        var msg = 'row selected ' + row.isSelected;
         
          var data= $scope.gridApi.selection.getSelectedRows();
      //   console.log("Id"+JSON.stringify(data[0].order_id));
            localStorage.setItem("orderid1",data[0].order_id)
            var data1=localStorage.getItem("orderid1");
            var emp_id=sessionStorage.getItem("userid");
            var data2={"order_id":data1,"empid":emp_id}
          $scope.fullOrder(data2);
         //  $location.path('/fullorder');
      //  $log.log(msg);
      });

      gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
        var msg = 'rows changed ' + rows.length;
            
        //$log.log(msg);
      });
    };
       
       $scope.fullOrder=function(data)
       {
           ordersService.insertEmpID(data).then(insertEmpIDResponse);
           function insertEmpIDResponse(response)
           {
               
               console.log("TTT"+JSON.stringify(response));
              
               var role= window.localStorage.getItem("role");
               console.log("ROLE"+role);
               if(response.status=="allow")
               {
                getFULL();
               }
               else if(role==1)
               {
                   getFULL(); 
               }
               else
               {
               alert("The Order is Taken by Another Employee")   
                   
               }
               function  getFULL()
               {
                 console.log("yes"); 
                  ordersService.getfullOrders(data).then(orderResponse);
           function orderResponse(response)
           {   
               $scope.imgdata = [];
               console.log("THE RESP"+JSON.stringify(response));
               $scope.fullData= response;
               for(var i=0;i<response.length;i++)
               {
                  $scope.imgdata.push(response[i].item_url);
                   
               }
                   window.localStorage.setItem("response",JSON.stringify($scope.fullData)); window.localStorage.setItem("img",JSON.stringify($scope.imgdata));
               
             $location.path('/fullorder');
           }  
               }
           }
           
        
       }
       $scope.loadDatas=function()
       {
      
        $scope.fulldatas =JSON.parse(window.localStorage.getItem("response"));
        console.log("$$$$$$"+JSON.stringify($scope.fulldatas));
          
        $scope.order_id=$scope.fulldatas[0].order_id;
        window.localStorage.setItem("orderid",$scope.order_id);
   
        $scope.order_date=$scope.fulldatas[0].order_date;
        $scope.order_status=$scope.fulldatas[0].order_status;
        $scope.user_first_name=$scope.fulldatas[0].user_first_name;
        $scope.user_email=$scope.fulldatas[0].user_email;
        $scope.user_mobileno=$scope.fulldatas[0].user_mobileno;
        $scope.usertype_name=$scope.fulldatas[0].usertype_name;
        
        $scope.images =JSON.parse(window.localStorage.getItem("img"));
        console.log("IMG LOG"+JSON.stringify($scope.images));
        
        
       }
       
       
       
                       $scope.$watch('files', function () {
        $scope.upload($scope.files);
    });
    $scope.$watch('file', function () {
        if ($scope.file != null) {
            $scope.upload([$scope.file]);
        }
    });
    $scope.log = '';
    $scope.upload = function (files) {
        
        var orders_id=window.localStorage.getItem("orderid1");
        var userid=window.sessionStorage.getItem("userid");
     //   var ooid={"orderid":orders_id}
        if (files && files.length) {
            for (var i = 0; i < files.length; i++) {
              var file = files[i];
              if (!file.$error) {
               Upload.upload({
    url: HOSTNEW+FILEUPLOAD, 
    method: 'POST',
    file: file,
     fields: {
                        'ooid': orders_id,
                        'userid' : userid
                    },
    sendFieldsAs: 'form',
}).progress(function (evt) {
                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + '% ' +
                                evt.config.file.name + '\n' + $scope.log;
                }).success(function (data, status, headers, config) {
                   window.localStorage.setItem("fileurl",data.targetpath);
                  // var fileurl= window.localStorage.getItem("fileurl");
                  // $scope.link1=DOMAIN+fileurl;
                   $scope.message =data.message;

                   location.reload();
                   if(data.status=="Success")
                   {
                      var orderid =window.localStorage.getItem("orderid1");
                      $scope.changeStatus(orderid);
                   }
                   
                    $timeout(function() {
                         // $scope.log='file: ' + config.file.name;
                        // $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                    });
                });
              }
            }
        }
    };
  
        
           
           
       }
       else
       {
        $location.path('/validateEmployee');
       }  
          
      //   document.getElementById('datePicker').value = moment().format('YYYY-MM-DD');
          $scope.getDate=function()
          {
             
              $scope.results = $filter('date')($scope.orderdate.value,"yyyy/MM/dd");
              console.log("res"+$scope.results);
              var data={"order_date":$scope.results}
              ordersService.getOrderByDate(data).then(getOrderResponse);
              
              function getOrderResponse(response)
              {
                  
                  console.log("THE DATA"+JSON.stringify(response));
                   $scope.gridOptions.data=response;
                
              }
          }
          
          $scope.changeStatus=function(orderid)
          {
             alert("the function is"+orderid);
             var data={"order_id":orderid}
             ordersService.changeOrderStatus(data).then(changeStatusResponse);
             function changeStatusResponse(response)
             {
                  console.log("response"+JSON.stringify(response));
                  
             }
          }
              var fileurl=window.localStorage.getItem("fileurl");
               $scope.link1=DOMAIN+fileurl;
                 $scope.location="FILE LOCATION";
            
         $scope.sendPush=function()
         {
     $scope.fulldatas =JSON.parse(window.localStorage.getItem("response"));
        console.log("$$$$$$"+JSON.stringify($scope.fulldatas));
        
          $scope.user_id=$scope.fulldatas[0].user_id;
          $scope.user_mobileno=$scope.fulldatas[0].user_mobileno;
          $scope.order_id=$scope.fulldatas[0].order_id;
          $scope.title =$scope.push_title;
          $scope.message =$scope.push_message;
            
           var data={"user_id":$scope.user_id,"user_mobile":$scope.user_mobileno,"order_id": $scope.order_id,"title":$scope.title,"message":$scope.message}
             
           
            
             
             ordersService.sendPush(data).then(pushResponse);
             function pushResponse(response)
             {
                 console.log("JSON"+JSON.stringify(response));
             }
             
         }
        
        
        
    
    }
    


}());