(function () {
    'use strict';

    angular
        .module('app')
        .controller('InvoiceController', InvoiceController);

    InvoiceController.$inject = ['$cookieStore','$routeParams','$route','$rootScope','$scope','UserService','FlashService','$location'];
    function InvoiceController($cookieStore,$routeParams,$rootScope,$route,$scope,UserService,FlashService,$location) {
    	$scope.usersDetails = {};
		var userid = $cookieStore.get('userid');	//loggedin userid	
		var userID = ($routeParams.user_id) ? parseInt($routeParams.user_id) : 0;//edit userid
		var loggedusername = $cookieStore.get('username');    	
		   $scope.edituserid = userID;	  
		   var userRoleId = $cookieStore.get('userRole');
		   $scope.userRole = userRoleId;
		   $scope.username = loggedusername;
					$scope.userentity={};
			 $scope.raiseInvoice=function(){
				 
				 alert("raiseInvoice function call");
				  var checkedValue = ''; 
				 	var checkedName='';
				 	var checkedManday='';
				 	var checkedStatus='';
				 	var checkedDept='';
				 	var checkedAmount='';
				 	var checkedshotDeptId='';
				 	var inputElements = document.getElementsByClassName('checkbox');
				 	var clientNames=document.getElementsByClassName('invoice_client');
				 	var mandays=document.getElementsByClassName('invoice_manday');
				 	var status=document.getElementsByClassName('invoice_status');
				 	var amount=document.getElementsByClassName('invoice_total');
				 	var shotdeptId=document.getElementsByClassName('invoice_shot_dept_ID');
				 	
				 	for(var i=0; inputElements[i]; ++i){
				 	      if(inputElements[i].checked){
				 	    	  
				 	           checkedValue += inputElements[i].value + ",";			 	           
				 	      }
				 	}
				 	for(var i=0; clientNames[i],inputElements[i]; ++i){
				 		if(inputElements[i].checked){
				 	           checkedName += clientNames[i].value + ",";
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
				 	
				 	for(var i=0; shotdeptId[i],inputElements[i]; ++i){
				 		if(inputElements[i].checked){

				 			checkedshotDeptId += shotdeptId[i].value + ",";			 	           
				 		}			 	      
				 	}
				 	
				 	
				 	checkedValue = checkedValue.substr(0, checkedValue.length-1);
				 	
				 	checkedName = checkedName.substr(0, checkedName.length-1);
				 	checkedManday = checkedManday.substr(0, checkedManday.length-1);

				 	checkedStatus = checkedStatus.substr(0, checkedStatus.length-1);
				 	checkedAmount = checkedAmount.substr(0, checkedAmount.length-1);
				 	checkedshotDeptId=checkedshotDeptId.substr(0, checkedshotDeptId.length-1);
				 	//alert(checkedValue+" "+checkedName);
				 	
		           	$.ajax({
		           		
						    type        : 'POST',
						    url         : 'services/invoice.php?function=raiseInvoice&identifyId='+checkedValue+'&client='+checkedName+'&status='+checkedStatus+'&amount='+checkedAmount+'&shotDeptIdentifyId='+checkedshotDeptId,
						    data        : $(this).serialize(),
							dataType    : 'json',  
							success     : function(data) {
								if(data.success)
									{
									$route.reload;
									}
								else
									{
									alert("Teshnical Issue");
									}
								//$scope.purchaseOrderNo = data[0];
						      }
						  });
				 
			 }
			 
			 
			 $scope.balancePayment=function(){
				 //console.log("payment function");
				 var identifyId = document.getElementById("identifyId").value;
				// console.log("passed value "+x);
				 var total=document.getElementById("totalAmount").value;
				 var paid=document.getElementById("paid").value;
			      
				 $.ajax({
		           		
					    type        : 'POST',
					    url         : 'services/invoice.php?function=balancePayament&identifyId='+identifyId+'&paid='+paid+'&totalAmount='+total,
					    data        : $(this).serialize(),
						dataType    : 'json',  
						success     : function(data) {
							if (data.success) {
								//alert('data'+data);
								//document.getElementById('paymentId').value = data.paymantId;
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
				 alert("passed value "+vals.length);
				
				    if (vals.length) {
				    	$("#History").attr("data-target","#myHistory");

				        $.ajax({
				            type: 'POST',
				            url: 'services/invoice.php?function=getPaymentHistory&invoiceId='+vals+'&entity_type=2',
				            data        : $(this).serialize(),
							dataType    : 'json',  
						    success     : function(data) {	
						    	$scope.Details=data;
						    	console.log("raisedInvoicePayment "+$scope.Details);
						      }
				        });
				    } else {
				    	$("#History").attr("data-target","");

				        alert("Please select one item.");
				    }
				 		
			 }
			 
			 
 
		    initController();	   
            function initController() 
            {			
            	retrieveInvoiceShot();
            	retriveInvoiceRaisedList();
            	retrieveInvoiceDetails();
            	getStatus();
            	

		   }
		   
		  function retrieveInvoiceShot()
		  {
			  //alert("pending invoice");
           	$.ajax({
           		
				    type        : 'POST',
				    url         : 'services/invoice.php?function=retrieveInvoiceShot',
				    data        : $(this).serialize(),
					dataType    : 'json',  
					success     : function(data) {
						$scope.invoiceShotDetails = data;
						//console.log("pending invoice "+$scope.invoiceShotDetails);
				      }
				  });
           	}	
		
		  function retriveInvoiceRaisedList()
		  {
			  //alert("raised invoice");
	           	$.ajax({	           		
					    type        : 'POST',
					    url         : 'services/invoice.php?function=retriveInvoiceRaisedList',
					    data        : $(this).serialize(),
						dataType    : 'json',  
						success     : function(data) {
							$scope.invoiceList = data;
					      }
					  });
	           	}	
		  function retrieveInvoiceDetails()
		  {  
			  //console.log("getting invoice details");
			   	var url = window.location.href;           	
	           	var invoiceIdArray = url.split(":"); 
	           	//alert(userIdArray.length);
	           	var invoiceId = '';
	           	if(invoiceIdArray.length > 2){
	           		invoiceId = invoiceIdArray[2];  
	           		$.ajax({	           			
					    type        : 'POST',
					    url         : 'services/invoice.php?function=retrieveInvoiceDetails&invoiceNo=' + invoiceId,
					    data        : $(this).serialize(),
						dataType    : 'json',  
						success     : function(data) {
							$scope.invoiceDetails = data[0];
							$scope.invoiceDetails.paid_amount=($scope.invoiceDetails.amount)-($scope.invoiceDetails.paid);
							
							console.log("paid amount controller "+$scope.invoiceDetails.paid_amount);
				//			alert("received "+$scope.invoiceDetails);;
							//console.log("invoice details "+$scope.invoiceDetails);
					      }
					  });
	           	}	
		  	}

		  
		  
		   function getStatus()
		   {
			 	$.ajax({
	           		
				    type        : 'POST',
				    url         : 'services/invoice.php?function=getStatus',
				    data        : $(this).serialize(),
					dataType    : 'json',  
					success     : function(data) {
						$scope.statusValue = data;
					//	console.log($scope.statusValue);
				      }
				  });
		   
		   }
		   
		 
		   
		   
		   
		   
		   
		   
    }
})();