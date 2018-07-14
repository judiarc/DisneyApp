(function () {
    'use strict';

    angular
        .module('app')
        .controller('PurchaseController', PurchaseController);

    PurchaseController.$inject = ['$cookieStore','$routeParams','$rootScope','$route','$scope','UserService','FlashService','$location'];
    function PurchaseController($cookieStore,$routeParams,$rootScope,$route,$scope,UserService,FlashService,$location) {
    	$scope.usersDetails = {};
		var userid = $cookieStore.get('userid');	//loggedin userid	
		var userID = ($routeParams.user_id) ? parseInt($routeParams.user_id) : 0;//edit userid
		var loggedusername = $cookieStore.get('username');
		$scope.raiseOrder=raiseOrder;
    	$rootScope.title = (userID > 0) ? 'Edit User' : 'Add User';
    	$scope.buttonText = (userID > 0) ? 'Update User' : 'Add New User';
    	
    	
		   $scope.edituserid = userID;
		   var userid = $cookieStore.get('userid');	
		    var username = $cookieStore.get('username');
		    var userRoleId = $cookieStore.get('userRole');
		    $scope.userRole = userRoleId;
		     $scope.username = username;
			$scope.activeMenu = "users";
			//var original = customer.data;
		
			 $scope.balancePaymentForPO=function(){
				 console.log("payment function");
				 var identifyId = document.getElementById("identifyId").value;
				// console.log("passed value "+x);
				 var total=document.getElementById("totalAmount").value;

				 var paid=document.getElementById("paid").value;
			      
				 $.ajax({
		           		
					    type        : 'POST',
					    url         : 'services/purchase.order.php?function=balancePayament&identifyId='+identifyId+'&paid='+paid+'&totalAmount='+total,
					    data        : $(this).serialize(),
						dataType    : 'json',  
						success     : function(data) {
							if (data.success) {
							console.log("paymentId "+data.paymantId);
							document
									.getElementById("responseMessage").innerHTML = "<span style=color:'green'>"
									+ data.message + "</span>";
							document
									.getElementById("responseMessage").style.color = '#008000'; // red or #ffffff
							document
									.getElementById("responseMessage").style.fontWeight = 'bold';
							window.scrollTo(500, 0);
							retrieveInvoiceDetails();
						} else {
							//alert('failure'+data);
							document
									.getElementById("responseMessage").innerHTML = "<span style=color:'red'>"
									+ data.message + "</span>";
							document
									.getElementById("responseMessage").style.color = '#ffffff'; // red or #ffffff
							document
									.getElementById("responseMessage").style.fontWeight = 'bold';
							window.scrollTo(500, 0);
						}
							
							//$scope.purchaseOrderNo = data[0];
					      }
					  });
				 
				 
				 
			 }
			
			 
			 $scope.payment_history=function()
			 {
/*		    	 	var checkedValue = ''; 

				 var inputElements = document.getElementsByClassName('checkbox');
				 
				 
				 
				 for(var i=0; inputElements[i]; ++i){
			 	      if(inputElements[i].checked){
			 	    	  
			 	           checkedValue += inputElements[i].value + ",";			 	           
			 	      }
			 	}
				 	checkedValue = checkedValue.substr(0, checkedValue.length-1);

				 alert("checked value "+checkedValue);
				 	var i=1;
				 	var j=1;
				 for (var x=1;x<=j;x++)
					 {
					 i++;
					 }
				 
				 	if(i==1)
				 		{
				 		alert("goes up "+i);
				 		
				 		}
				 	else 
				 		{
				 		alert("goes down"+i);
				 		}
		*/	 
				 var checkboxes = document.getElementsByName('checked_id[]');
				 var vals = "";
				 for (var i=0, n=checkboxes.length;i<n;i++) 
				 {
				     if (checkboxes[i].checked) 
				     {
				         vals += ","+checkboxes[i].value;
				     }
				 }
				 if (vals) vals = vals.substring(1);
				 //alert("passed value "+vals.length);
	
				    if (vals.length) {
				    	$("#History").attr("data-target","#myHistory");

				        $.ajax({
				            type: 'POST',
				            url: 'services/purchase.order.php?function=getPaymentHistory&invoiceId='+vals+'&entity_type=1',
				            data        : $(this).serialize(),
							dataType    : 'json',  
						    success     : function(data) {	
						    	$scope.paymentDetails=data;
						    	console.log("raisedOrderPayment "+$scope.paymentDetails);
						      }
				        });
				    } else {
				    	$("#History").attr("data-target","");
				        alert("Please select one item.");

				        
				    }
				 		
			 }
			 
			 
			 
			$scope.userentity={};
			$scope.getItems = function(endpoint) {
      		var data = UserService.getItems(endpoint);
     		 return data;
			}
			
		<!--	 this part is for multiple checkbox edit -->
		
			 $scope.isChecked = function(id){
      			var match = false;
     			 for(var i=0 ; i < $scope.userentity.length; i++) {
        		 if($scope.userentity[i].entity_id == id){
         		 match = true;
       			 }
     		 }
     			 return match;
  			};
  
			$scope.usergenderid = [{usergenderid:'1',name: 'male'}, {usergenderid:'2',name: 'female'}]	
			
			
			<!-- this part is for multiple checkbox -->
		 
 
		    initController();	   
            function initController() {	
            	pendingPurchaseOrder();
            	retriveRaisedOrder();
            	retrivePurchaseOrderDetails();
            	getStatus();
            }
		   


            
		   function raiseOrder(){
			   alert("connected");

			    	 	var checkedValue = ''; 
			 	var checkedName='';
			 	var checkedManday='';
			 	var checkedStatus='';
			 	var checkedDept='';
			 	var checkedAmount='';
			 	var checkedShotWork='';
			 	var inputElements = document.getElementsByClassName('checkbox');
			 	var verdorNames=document.getElementsByClassName('vendor_id');
			 	var mandays=document.getElementsByClassName('shotcode_manday');
			 	var status=document.getElementsByClassName('shotcode_status');
			 	var amount=document.getElementsByClassName('shotcode_total');
			 	var shotwork=document.getElementsByClassName('shotcode_shotwork');
			 	for(var i=0; inputElements[i]; ++i){
			 	      if(inputElements[i].checked){
			 	    	  
			 	           checkedValue += inputElements[i].value + ",";			 	           
			 	      }
			 	}
			 	for(var i=0; verdorNames[i],inputElements[i]; ++i){
			 		if(inputElements[i].checked){
			 	           checkedName += verdorNames[i].value + ",";
			 		}
			 	      
			 	}
			 	for(var i=0; mandays[i],inputElements[i]; ++i){
			 		if(inputElements[i].checked){

			 	           checkedManday += mandays[i].value + ",";			 	           
			 		}			 	      
			 	}
			 	for(var i=0; status[i],inputElements[i]; ++i){
			 		if(inputElements[i].checked){
 
			 		checkedStatus += status[i].value + ",";			 	           
			 		}			 	      
			 	}
			 	
			 	for(var i=0; amount[i],inputElements[i]; ++i){
			 		if(inputElements[i].checked){
 
			 		checkedAmount += amount[i].value + ",";			 	           
			 		}			 	      
			 	}
			 	
				for(var i=0; shotwork[i],inputElements[i]; ++i)
				{
			 		if(inputElements[i].checked){
 
			 		checkedShotWork += shotwork[i].value + ",";	
			 		}			 	      
			 	}
			 	
			 	checkedValue = checkedValue.substr(0, checkedValue.length-1);
			 	
			 	checkedName = checkedName.substr(0, checkedName.length-1);
			 	checkedManday = checkedManday.substr(0, checkedManday.length-1);

			 	checkedStatus = checkedStatus.substr(0, checkedStatus.length-1);
			 	checkedAmount = checkedAmount.substr(0, checkedAmount.length-1);
			 	checkedShotWork=checkedShotWork.substr(0, checkedShotWork.length-1);
			 	//alert("shot work "+checkedShotWork);
			 	var date = new Date();
			 	console.log(checkedShotWork);
			 	
			 	
			 	//alert("IdentifyId "+checkedValue+" vendorId "+checkedName+" manday "+checkedManday+" status "+checkedStatus+" amount "+checkedAmount+"shot work"+checkedShotWork);
			if(confirm("Do you want to raise PO for Shot")==true)
			 		 $.ajax({
						    type        : 'POST',
						    url         : 'services/purchase.order.php?function=raiseOrder&identifyId='+checkedValue+'&vendorId='+checkedName+'&status='+checkedStatus+'&totalAmount='+checkedAmount+'&manday='+checkedManday+'&shotWork='+checkedShotWork,
						    data        : $(this).serialize(),
							dataType    : 'json',  
						    success     : function(data) {	
						    	if(data.success){
						    		
							    	alert("Shotcode " + checkedValue + " PO raised successfully");
						    		$route.reload();
						    	}else {
						    		//alert("Shotcode " + checkedValue + " was not able to raise, due to technical issue");
						    	}						    	
						      }
						  });
			 	
			 		
		   }
		   

	          function retriveRaisedOrder()
	            {
	            	
	              //alert("get raised PO");
	            	 $.ajax({
						    type        : 'POST',
						    url         : 'services/purchase.order.php?function=retriveRaisedOrder',
						    data        : $(this).serialize(),
							dataType    : 'json',  
						    success     : function(data) {	
						    	$scope.raisedorders=data;
						    	console.log("raisedOrder "+$scope.raisedOrders);
						      }
						  });
	            	
	            }
		  
		   function pendingPurchaseOrder(){
			   //alert("get details");

			  	 $.ajax({
					    type        : 'POST',
					    url         : 'services/purchase.order.php?function=retrivePendingPurchaseOrder',
					    data        : $(this).serialize(),
						dataType    : 'json',  
					    success     : function(data) {	
					    	$scope.pendingorders=data;
					    	}
					  });

		   }
		   function retrivePurchaseOrderDetails()
		   {
			   
	           	var url = window.location.href;           	
	           	var purchaseIdArray = url.split(":"); 
	           	////alert(userIdArray.length);
	           	var purchaseId = '';
	           	if(purchaseIdArray.length > 2){
	           		purchaseId = purchaseIdArray[2];          	
	           	$.ajax({
	           		
					    type        : 'POST',
					    url         : 'services/purchase.order.php?function=orderDetails&purchaseId='+purchaseId,
					    data        : $(this).serialize(),
						dataType    : 'json',  
						success     : function(data) {
							$scope.purchaseDetails = data;
							console.log("purchase details "+$scope.purchaseDetails);
					      }
					  });
	           	}
		   }
		   
		   
		   function getStatus(){
			 	$.ajax({
	           		
				    type        : 'POST',
				    url         : 'services/purchase.order.php?function=getStatus',
				    data        : $(this).serialize(),
					dataType    : 'json',  
					success     : function(data) {
						$scope.statusValue = data;
						console.log($scope.statusValue);
				      }
				  });
		   
		   }
		   
		   
		 
 
		 
		 
 }


})();