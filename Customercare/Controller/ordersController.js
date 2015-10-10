(function(){
    
    bharat.controller('ordersController',['$scope','$location','ordersService','$filter','Upload','$timeout','$state','usSpinnerService',ordersController]);
    
    function ordersController($scope,$location,ordersService,$filter,Upload,$timeout,$state,usSpinnerService)
    {
        $scope.startSpin = function()
        {
            usSpinnerService.spin('spinner-1');
        }
        $scope.stopSpin = function()
        {
            usSpinnerService.stop('spinner-1');
        }
        
        $scope.noorders = false;
        $scope.invoiceDetails  =  [];
        $scope.allocmessagediv = []; 
        $scope.order_type_select = 0;
         // Function to get all orders
        $scope.getAllOrders = function()
        {
            $scope.startSpin();
            $scope.orderList = [];
            $scope.date = $filter('date')($scope.date,'yyyy-MM-dd'); 
            $scope.order_type_select = 0;
            console.log("SEL"+$scope.order_type_select);
            ordersService.getAllOrders({"date":$scope.date}).then(function(response){
                console.log("ALL ORDER"+JSON.stringify(response));    
                if(response[0].status === "Success")
                {
                   $scope.noorders = false; 
                   $scope.orderList = response;
                }
                else
                {
                    $scope.noorders = true;
                }
                $scope.stopSpin();
            })
        }
        
        $scope.selectOrderByType = function()
        {
            $scope.startSpin();
            if($scope.order_type_select === 0) // all orders
            {
               $scope.getAllOrders();
            }
            else
            {
                var data = {"order_type":$scope.order_type_select,"date":$scope.date};
                ordersService.getOrderByOrderType(data).then(function(response){
                    if(response[0].status === "Success")
                    {
                         $scope.noorders = false; 
                         $scope.orderList = response;
                    }
                    else
                    {
                         $scope.noorders = true;
                    }
                    $scope.stopSpin();
                });
                
            }
        }
        
        $scope.checkOrderStatus = function(order_id)
        {
            $scope.user_id  = sessionStorage.userid;
            $scope.order_id = order_id;
            $scope.checkorAssignOrderForUser($scope.order_id,$scope.user_id);
        }
        
        // :todo allow admin to view report //error pending
        $scope.checkResponsiblity = function(responsibility)
        {
            $scope.responsibilityList = [];
            $scope.dashboardItems     = JSON.parse(window.localStorage.getItem("dashboardItems"));
            $scope.status = true;
            $scope.responsibilityList = $scope.dashboardItems.preloaddata.responsibilities;
            console.log("RESP LIST"+responsibility);
            
            for(var i = 0 ; i < $scope.responsibilityList.length ; i++)
            {
                if($scope.responsibilityList[i].id !== 5)
                {
                    $scope.status = false;
                }
                else
                {
                    $scope.status = true;
                }
            }

            return $scope.status;
            
        }
        
        // assign/check employee handling the order
        $scope.checkorAssignOrderForUserfromManageOrder = function(orderid)
        {   
                $scope.startSpin();
                $user_id = sessionStorage.userid;       
                ordersService.checkorAssignOrderForUser({"user_id":$user_id,"order_id":orderid}).then(function(response){
                console.log("CHECK"+JSON.stringify(response));        
                if(response.status === "Success")
                {
                   $state.go("vieworderdetail",{order_id:orderid}); 
                }
                else
                {
                   alert("You are not allowed to process the order");
                }
                $scope.stopSpin();
            });
        }
        
        $scope.checkorAssignOrderForUserfromQueryString = function($order_id,$user_id)
        {
            if($order_id === null){$state.go("manageorders")}
            ordersService.checkorAssignOrderForUser({"user_id":$user_id,"order_id":$order_id}).then(function(response){
                
                if(response.status === "Success")
                {
                   
                }
                else
                {
                   $state.go("manageorders");
                }
            });
        }
        
        // Function to get complete details of an order
        $scope.getSingleOrderDetail = function()
        {
           $scope.startSpin();
           $scope.order_id = $state.params["order_id"];
           $scope.user_id  = sessionStorage.userid;
           $scope.checkorAssignOrderForUserfromQueryString($scope.order_id,$scope.user_id);
           ordersService.getSingleOrderDetail({"order_id":$scope.order_id}).then(function(response){
               console.log(JSON.stringify(response));
               if(response.status === "Success")
               {
                        $scope.noOrderDetail       =   false;
                        $scope.order_date          =   response.order_date;
                        $scope.order_status        =   response.order_status; 
                        $scope.assignCustomerDetail(response);
                        
                        // check if order item exists
                        if(response.order_items[0].status === "Success")
                        {
                            $scope.hasOrderItem = true;
                            $scope.assignOrderItems(response.order_items);    
                        }
                        else
                        {
                            $scope.hasOrderItem = false;
                        }
                        
                        // check if amount detals exists
                        if(response.amountDetails.status === "Success")
                        {
                            $scope.hasAmountDetails = true;
                            $scope.assignAmountDetails(response.amountDetails);
                            $scope.ordertype        = "Placed";
                        }
                        else
                        {
                            $scope.ordertype        = "Quote";
                            $scope.hasAmountDetails = false;
                        }
                        
                        // check if gift details exists
                        if(response.giftDetails.status === "Success")
                        {
                            $scope.hasGiftDetails = true;
                            $scope.assignGiftDetails(response.giftDetails);
                        }
                        else
                        {
                            $scope.hasGiftDetails = false;
                        }
                        
                        // check if invoice exists
                        if(response.invoiceDetails[0].status === "Success")
                        {
                            console.log("SUCC"+response.invoiceDetails);
                            $scope.hasInvoiceDetails = true;
                            $scope.assigninvoiceDetails(response.invoiceDetails);
                        }
                        else
                        {
                            console.log("FAIL"+response.invoiceDetails);
                            $scope.hasInviceDetails = false;
                        }
               }
               else
               {
                   $scope.noOrderDetail = true;
               }
               $scope.stopSpin();
           }); 
        }
        
        
        $scope.assignCustomerDetail     =    function(response)
        {
            $scope.user_id              =    response.user_id;
            $scope.first_name           =    response.first_name;
            $scope.email_id             =    response.email_id;
            $scope.address              =    response.address;
            $scope.pincode              =    response.pincode;
            $scope.landmark             =    response.landmark;
            $scope.usertype_name        =    response.usertype_name;
            $scope.dealer_code          =    response.dealer_code;
            $scope.comments             =    response.comments;
            $scope.mobile_no            =    response.user_mobileno;
        }
        
        $scope.assignOrderItems         =    function(response)
        {
            $scope.orderItems           =    [];
            $scope.itemType             =    response[0].item_type;
            $scope.orderItems           =    response;  
        }
        
        $scope.assignAmountDetails      =    function(response)
        {
            $scope.amount_from          =    response.amount_from;
            $scope.amount_to            =    response.amount_to;
        }
        
        $scope.assignGiftDetails        =    function(response)
        {
            $scope.gift_name            =    response.gift_name;
        }
        
        $scope.assigninvoiceDetails     =    function(response)
        {
            $scope.invoiceDetails       =    response;
            console.log("IN"+$scope.invoiceDetails);
        }
        
        
        $scope.sendPushNotification = function()
        {
            $scope.startSpin();
            var data = {"title":$scope.push.title,"message":$scope.push.message,"order_id":$scope.order_id,"user_id":$scope.user_id,"mobile_no":$scope.mobile_no};
            
            ordersService.sendPushNotification(data).then(function(response){
                if(response[0].status === "Success" && response[0].Push === true) 
                {
                    alert("Push Send Successfuly");
                    $scope.push.title = "";
                    $scope.push.message = "";
                }
                else
                {
                    alert("Push Sending Failure");
                }
                $scope.stopSpin();
            });
        }
        
        $scope.getOrdersForEmployee = function()
        {
            $scope.allocmessagediv.length = 0;
            $scope.allocmessagediv = [];
            $scope.startSpin();
            $scope.date = $filter('date')($scope.allocdate,'yyyy-MM-dd'); 
            var data = {"date":$scope.date,"user_id":$scope.allocuserlists.id};
            console.log(JSON.stringify(data));
            ordersService.getOrdersForEmployee(data).then(function(response){
                 if(response[0].status === "Success")
                 {
                     $scope.ordersAssigned = response;
                     $scope.isorderassigned = true;
                 }
                 else
                 {
                     $scope.ordersAssigned  = [];
                     $scope.isorderassigned = false;
                 }
                 $scope.stopSpin();
            });
        }
        
        $scope.allocOrderstoEmployee = function(order_id,user_id,$index)
        {
            var data = {"order_id":order_id,"assign_to":user_id.id};
            ordersService.allocOrderstoEmployee(data).then(function(response){
                if(response.status === "Success")
                { 
                    $scope.allocmessagediv[$index] = true;
                    $scope.allocicon = "glyphicon glyphicon-ok";
                }
                else
                {
                    $scope.allocmessagediv[$index] = true;
                    $scope.allocicon = "glyphicon glyphicon-remove";
                }
            })
        }
        
      
        
       /* Date Related Coding Starts*/
        $scope.today = function() {
         $scope.date = new Date();
        };
        
        $scope.today();

        $scope.clear = function () {
          $scope.date = null;
        };

        $scope.open = function($event) {
          $scope.status.opened = true;
        };

        $scope.dateOptions = {
          formatYear: 'yy',
          startingDay: 1
        };

        $scope.status = {
          opened: false
        };
        
        $scope.formats = ['yyyy-MM-dd', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        $scope.format = $scope.formats[0];
        /* Date Related Coding Ends*/
        
        
        
        /* File Upload Functionality Starts */
        
        $scope.$watch('files', function () {
            $scope.upload($scope.files);
        });
        
        $scope.$watch('file', function () {
             if ($scope.file != null) {
                $scope.upload([$scope.file]);
             }
        });
        
        $scope.log = '';
        
        $scope.upload = function (files) 
        {
            
            var orders_id   = $scope.order_id;
            var userid      = sessionStorage.userid;
            var order_type  = $scope.ordertype; 
            console.log("ORDER TYPE"+$scope.ordertype);
            if (files && files.length) 
            {
                for (var i = 0; i < files.length; i++) 
                {
                  var file = files[i];
                  if (!file.$error) 
                  {
                       $scope.startSpin();
                        Upload.upload({url: HOST+FILEUPLOAD, method: 'POST',file: file,
                            fields: {
                            'order_id'   : orders_id,
                            'user_id'    : userid,
                            'order_type' : order_type
                            },
                        sendFieldsAs: 'form',
                        }).progress(function (evt) 
                        { 
                            $scope.progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                            if($scope.progressPercentage === 100)
                            {
                                $scope.showProgressbar = false;
                            }
                            else
                            {
                                $scope.showProgressbar = true;
                            }
                        })
                        .success(function (data, status, headers, config) 
                        {
                            console.log("FILE"+JSON.stringify(data));    
                            if(data[0].status === "Success")
                            {
                                $scope.fileurl = data[0].file_path;
                                $scope.invoiceDetails.push(data[0]);
                                $scope.hasInvoiceDetails = true;
                            }

                        $timeout(function() {
                             // $scope.log='file: ' + config.file.name;
                            // $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                         });
                         $scope.stopSpin();
                     });
                   }
                 }
                 
              }
              
        };
        
        /* File Upload Functionality Ends */

        
    }
    
   
    
}())
    