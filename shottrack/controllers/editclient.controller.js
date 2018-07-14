(function () {
    'use strict';
    angular
           .module('app')
           .controller('EditClientController', EditClientController);
		    EditClientController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','client'];
		    function EditClientController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,client) {
				
			var clientID = ($routeParams.client_id) ? parseInt($routeParams.client_id) : 0;//edit userid
		
    	    $rootScope.title = (clientID > 0) ? 'Edit Client' : 'Add Client';
    	    $scope.buttonText = (clientID > 0) ? 'Update Client' : 'Add New Client';
			var userid = $cookieStore.get('userid');	
			 var username = $cookieStore.get('username');
			 $scope.username = username;		
			$scope.activeMenu = "client";
			var original = client.data;
			$scope.clientFields = {};
			$scope.isSelected = function(listOfItems, item){			
	    		var resArr = listOfItems.split(",");
	    		if (resArr.indexOf(item.toString()) > -1) {	    			
	    		    return true;
	    		  } else {	    			 
	    		    return false;
	    		  }
	    	};
	   /* 	$scope.addClient = function(){
	    		var i = 0;
	    		$('#client')
				.submit(
						function(event) {
							alert("function calls");
							i += 1;
							if(i == 1) {
								alert("value "+1);
							$
									.ajax({
										type : 'POST',
										url : 'services/client.php?function=addOrUpdateClient',
										data : $(this).serialize(),
										dataType : 'json',
										success : function(data) {
											if (data.success) {		
												//alert("saved");
												document.getElementById('clientId').value = data.clientId;
												$scope.clientId = data.clientId;
												document
														.getElementById("responseMessage").innerHTML = "<span style=color:'green'>"
														+ data.message + "</span>";
												document
														.getElementById("responseMessage").style.color = '#008000'; // red or #ffffff
												document
														.getElementById("responseMessage").style.fontWeight = 'bold';
												window.scrollTo(500, 0);
												    
												retrieveEntityDeptAssoc();
											} else {
												document
														.getElementById("responseMessage").innerHTML = "<span style=color:'green'>"
														+ data.message + "</span>";
												document
														.getElementById("responseMessage").style.color = '#ffffff'; // red or #ffffff
												document
														.getElementById("responseMessage").style.fontWeight = 'bold';
												window.scrollTo(500, 0);
											}
										}
									});
							}
							// stop the form from submitting and refreshing
							event.preventDefault();
						});
	    	};
	    */	$scope.departmentSave = function($dept){
	    		alert($dept);
	    		var i = 0;
	    		$('#form'+$dept).submit(function(event) {
							i += 1;							
							if(i == 1) {
							$
									.ajax({
										type : 'POST',
										url : 'services/client.php?function=saveEntityDeptAssoc&department_id='+$dept,
										data : $(this).serialize(),
										dataType : 'json',
										success : function(data) {											
											if (data.success) {	
												document
														.getElementById("responseMessage"+$dept).innerHTML = "<span style=color:'green'>"
														+ data.message + "</span>";
												document
														.getElementById("responseMessage"+$dept).style.color = '#008000'; // red or #ffffff
												document
														.getElementById("responseMessage"+$dept).style.fontWeight = 'bold';
																								    
												
											} else {
												document
														.getElementById("responseMessage"+$dept).innerHTML = "<span style=color:'green'>"
														+ data.message + "</span>";
												document
														.getElementById("responseMessage"+$dept).style.color = '#ffffff'; // red or #ffffff
												document
														.getElementById("responseMessage"+$dept).style.fontWeight = 'bold';
												
											}
										}
									});
							}
							// stop the form from submitting and refreshing
							event.preventDefault();
						});
	    	};
				
			if(original)
			{			
			$scope.entity= angular.copy(original);
			}
			else
			{
				$scope.entity= {};
			}
		
			 $scope.selectAction = function () {
				
                   // call jQuery functiona using angular.element
              var b= angular.element(document.getElementById("typeid")).val();
					if(b == 2)
					{
						 var b= angular.element(document.getElementById("regionsid"));
						 b.removeAttr('disabled');
					}
					else if(b == 1)
					{
						$("#regionsid").prop('selectedIndex',0).attr('disabled','disabled');
						$("#countryid").prop('selectedIndex',0).attr('disabled','disabled')
						$("#currencyid").prop('selectedIndex',0).attr('disabled','disabled')				 	
						
					}
			var c= angular.element(document.getElementById("regionsid")).val();
			
					 if(c)
					 {
						 var d= angular.element(document.getElementById("countryid"));
						 d.removeAttr('disabled');
						 UserService.getCountryDetails(c).then(function (data) {				
						$scope.clientcountryid = data.data;
			 			});
					 }
					 
			 var e= angular.element(document.getElementById("countryid")).val();
			
					 if(e)
					 {
						 
						 var f= angular.element(document.getElementById("currencyid"));
						 f.removeAttr('disabled'); 
						  UserService.getCurrencyDetails(e).then(function (data) {				
							$scope.clientcurrencyid = data.data;
			 			});
					 }
					 else 
					 {
						 var k=angular.element(document.getElementById("typeid")).val();
						 if(k == 2)
						 {
							 
						 var g=angular.element(document.getElementById("countryidselect")).val();
						
						 UserService.getCurrencyDetails(g).then(function (data) {				
							$scope.clientcurrencyid = data.data;
			 			});
						 }
						 
					 }
                };

		    $scope.editclientid = clientID;	
			$scope.clientaccessid = [{clientaccessid:'1',name: 'ftp'}, {clientaccessid: '2',name: 'aspera'}]	
			$scope.clientstatusid = [{clientstatusid:'1',name: 'active'}, {clientstatusid: '2',name: 'dormant'}, {clientstatusid: '3',name: 'inactive'}]
			$scope.clientregionsid = [{clientregionsid:'1',name: 'Asia'}, {clientregionsid: '2',name: 'Asia-Pacific'},{clientregionsid:'3',name: 'Europe'}, 
			{clientregionsid: '4',name: 'North America'}]	
			$scope.clienttypeid = [{clienttypeid:'1',name: 'domestic'}, {clienttypeid: '2',name: 'international'}]	
			
			
			
			 $scope.selectaccess = function () {
			
                   // call jQuery functiona using angular.element
              var b= angular.element(document.getElementById("accessid")).val();
			 
					if(b == 2)
					{
						 var b= angular.element(document.getElementById("url"));
						 b.removeAttr('disabled');
						 
						 var e= angular.element(document.getElementById("hostname"))
						e.attr('disabled','disabled');
					}
					else if(b == 1)
					{
						var d= angular.element(document.getElementById("url"))
						d.attr('disabled','disabled');	
						
					 	var c= angular.element(document.getElementById("hostname"))
						c.removeAttr('disabled');
						
					}
					
			 }
			
				
			 
			 $scope.addChoice = function () 
			 {
				$("#email1").removeAttr('style');
			 }
			  $scope.addChoice1 = function () 
			 {
				$("#email2").removeAttr('style');
			 }
			  $scope.addChoice2 = function () 
			 {
				$("#email3").removeAttr('style');
			 }
			  $scope.addChoice3 = function () 
			 {
				$("#email4").removeAttr('style');
			 }
			  $scope.addmobile = function () 
			 {
				$("#mobile1").removeAttr('style');
			 }
			  $scope.addmobile1 = function () 
			 {
				$("#mobile2").removeAttr('style');
			 }
			  $scope.addmobile2 = function () 
			 {
				$("#mobile3").removeAttr('style');
			 }
			  $scope.addmobile3= function () 
			 {
				$("#mobile4").removeAttr('style');
			 } 
			
			initController();
				   
            function initController() {
				retrieveEntityDeptAssoc();

            getUserRolefields();
            retrieveClient();
			getClientType();
			getEntityStatus();
			getCurrencyList();
			getdepartments();
//			retrieveEntityDeptAssoc();
			}	  
            
            
           
            function retrieveEntityDeptAssoc(){
            	$scope.selected=2;
            	var url = window.location.href;
            	var clientId = url.split(":")[2];
            	
            	if(clientId == null || client == '') {
            		clientId = $scope.clientId;
            	}
            	$.ajax({           
			    type        : 'POST',
			    url         : 'services/client.php?function=retrieveEntityDeptAssoc&clientId='+clientId,
			    data        : $(this).serialize(),
				dataType    : 'json',  
			    success     : function(dataModel) {			    	  
				    	$scope.entityDeptAssoc = dataModel;
				    	    		
				    		
				    		
				    	
			    	
			      }
			  }); 
		    }
            
            function retrieveClient(){
            	var url = window.location.href;
            	var clientId = url.split(":")[2];
            	$.ajax({
				    type        : 'POST',
				    url         : 'services/client.php?function=retrieveClient&clientId='+clientId,
				    data        : $(this).serialize(),
					dataType    : 'json',  
				    success     : function(data) {		
				    	$scope.entity = data[0];
				      }
				  });            	
            }
            
            
            function getClientType(){            	
            	$.ajax({
				    type        : 'POST',
				    url         : 'services/client.php?function=getClientType',
				    data        : $(this).serialize(),
					dataType    : 'json',  
				    success     : function(data) {		
				    	$scope.types = data;	
				    	//alert("$scope.types" + $scope.types);
				      }
				  });            	
            }
            function getEntityStatus(){   
            		var url = 'services/getEntityStatus';
  			   		UserService.getApiCall(url).then(function (data) {				
  				    $scope.entitystatus = data.data;  				    
  		            });           
  		   }
            
            function getdepartments()
            {
            	UserService.getdepartments().then(function (data){
            		$scope.departments=data.data;
            		//alert($scope.departments);
            	});
            }
            function getCurrencyList(){   
        		var url = 'services/getCurrencyList';
			   		UserService.getApiCall(url).then(function (data) {				
				    $scope.currencyList = data.data;  				    
		            });           
		   }
            
            function getUserRolefields(){   	
  			   UserService.getUserRolefields(5).then(function (data) {				
  				    $scope.clientFields = data.data;
  				   // alert("current field" + $scope.clientFields);
  		            });           
  		   }
            
			function getfields()
		    {
		    var client="client";
            UserService.getFields(client).then(function (data) {				
		    $scope.fields = data.data;			
           	 });
        	}
			function getfieldsbank()
		    {
		    var bankdetails="bankdetails";
            UserService.getFields(bankdetails).then(function (data) {				
		    $scope.fieldsbank = data.data;			
            });
         	} 
			function getfieldsftp()
		    {
		    var ftp="ftp";
            UserService.getFields(ftp).then(function (data) {				
		    $scope.fieldsftp = data.data;			
            });
         	} 	
		  function getAccessDetails()
			{
			  
			 UserService.getAccessDetails(userid).then(function (data) {				
					$scope.useraccess = data.data;
			 });
		}  		
		function getentityid()
		{
			var name="client";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 		 
	}; 
})();