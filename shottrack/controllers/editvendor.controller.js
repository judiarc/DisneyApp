(function () {
    'use strict';
    angular
           .module('app')
           .controller('EditVendorController', EditVendorController);
		    EditVendorController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','vendor'];
		    function EditVendorController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,vendor) {
		    	
	
			var vendorID = ($routeParams.vendor_id) ? parseInt($routeParams.vendor_id) : 0;//edit userid
		
    	    $rootScope.title = (vendorID > 0) ? 'Edit Vendor' : 'Add Vendor';
    	    $scope.buttonText = (vendorID > 0) ? 'Update Vendor' : 'Add New Vendor';
				
		
			var userid = $cookieStore.get('userid');
			 var username = $cookieStore.get('username');
		     $scope.username = username;	
			$scope.activeMenu = "vendor";
			var original = vendor.data;
			if(original)
			{			
			$scope.entity= angular.copy(original);
			}
			else
			{
				$scope.entity= {};
			}
			
			
			
		/*	$scope.addVendor = function(){
	    		//alert("function called");
	    		var i = 0;
	    		$('#vendor')
				.submit(
						function(event) {
							i += 1;
							if(i == 1) {
							$
									.ajax({
										type : 'POST',
										url : 'services/vendor.php?function=addOrUpdateVendor',
										data : $(this).serialize(),
										dataType : 'json',
										success : function(data) {
											//alert("success "+data);
											

												//$scope.vendorId = data;
												//console.log("value "+$scope.vendorId);

											
											if (data.success) {	
												//alert("done");
												document.getElementById('vendorId').value = data.vendorId;
												$scope.vendorId = data.vendorId;
												//console.log("value "+$scope.vendorId);
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
	    	};*/
	    	$scope.departmentSave = function($dept){
	    		//alert($dept);
	    		var i = 0;
	    		$('#form'+$dept).submit(function(event) {
							i += 1;							
							if(i == 1) {
							$
									.ajax({
										type : 'POST',
										url : 'services/vendor.php?function=saveEntityDeptAssoc&department_id='+$dept,
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
	    	
	    	$scope.isSelected = function(listOfItems, item){			
	    		var resArr = listOfItems.split(",");
	    		//alert(resArr);
	    		if (resArr.indexOf(item.toString()) > -1) {	    			
	    		    return true;
	    		  } else {	    			 
	    		    return false;
	    		  }
	    	};
			
		    $scope.editvendorid = vendorID;	
			$scope.vendoraccessid = [{vendoraccessid:'1',name: 'ftp'}, {vendoraccessid: '2',name: 'aspera'}]	
			$scope.vendorstatusid = [{vendorstatusid:'1',name: 'active'}, {vendorstatusid:'2',name: 'inactive'}]	
			$scope.vendorgenderid = [{vendorgenderid:'1',name: 'male'}, {vendorgenderid:'2',name: 'female'}]	
			$scope.vendorlevelid =  [{vendorlevelid:'1',name: 'high'}, {vendorlevelid:'2',name: 'middle'},{vendorlevelid:'3',name:'low'}]
			$scope.vendordepartmentid=[{vendordepartmentid:'1',name:'Roto'},{vendordepartmentid:'2',name:'Paint'},{vendordepartmentid:'3',name:'Match move'},{vendordepartmentid:'4',name:'Compositing'},{vendordepartmentid:'5',name:'3D'}]
			
			
			initController();
				   
            function initController() {						
            getUserRolefields();
			getfieldsbank();
			getfieldsftp();
			getAccessDetails(); 
			getentityid();
			getEntityStatus();
			getdepartments(); 	
			retrieveVendor();
			retrieveEntityDeptAssoc();
			}	
           
            function retrieveEntityDeptAssoc(){
            	//alert("funciton for entityAssoc");
            	var url = window.location.href;
            	var vendorId = url.split(":")[2];
            	if(vendorId == null || vendorId == '') {
            		vendorId = $scope.vendorId;
            	}
            	
            	
            	$.ajax({           
			    type        : 'POST',
			    url         : 'services/vendor.php?function=retrieveEntityDeptAssoc&vendorId='+vendorId,
			    data        : $(this).serialize(),
				dataType    : 'json',  
			    success     : function(dataModel) {
			    	  //console.log(dataModel);
				    	$scope.entityDeptDetails = dataModel;
				    	//alert("retrived Entity");
				    	alert("done "+$scope.entityDeptDetails);
			    	
			      }
			  }); 
		    }

            function retrieveVendor(){
            	var url = window.location.href;
            	var vendorId = url.split(":")[2];
            	$.ajax({
				    type        : 'POST',
				    url         : 'services/vendor.php?function=retrieveVendor&vendorId='+vendorId,
				    data        : $(this).serialize(),
					dataType    : 'json',  
				    success     : function(data) {		
				    	$scope.entity = data[0];
				      }
				  });            	
            }
            function getdepartments()
            {
            	UserService.getdepartments().then(function (data){
            		$scope.departments=data.data;
            	});
            }
            
            function getEntityStatus(){   
        		var url = 'services/getEntityStatus';
			   		UserService.getApiCall(url).then(function (data) {				
				    $scope.entitystatus = data.data;  				    
		            });           
		   }
             /*function gettypes()
             {
            	 UserService.gettypes().then(function(data){
            		 $scope.types=data.data;
            		//alert($scope.types);
            	 });
             }*/
             function getUserRolefields(){   	
    			   UserService.getUserRolefields(4).then(function (data) {				
    				    $scope.vendorFields = data.data;
    				   // alert("current field" + $scope.clientFields);
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
			
			function getuserrole()
		{
			 UserService.getuserrole().then(function (data) {				
					$scope.userroleid = data.data;
			 });
		}
			function getentityid()
		{
			var name="vendor";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 		 	
		}; 
})();