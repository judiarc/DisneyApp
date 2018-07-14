(function () {
    'use strict';
    angular
           .module('app')
           .controller('EditProjectdetailsController', EditProjectdetailsController);
		    EditProjectdetailsController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location'];
		    function EditProjectdetailsController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location) {
				
			var projectdetID = ($routeParams.projectdet_id) ? parseInt($routeParams.projectdet_id) : 0;// edit
																										// userid
		
    	    $rootScope.title = (projectdetID > 0) ? 'Edit Project' : 'Add Project';
    	    $scope.buttonText = (projectdetID > 0) ? 'Update Project' : 'Add New Project';	
			var userid = $cookieStore.get('userid');
			 var username = $cookieStore.get('username');
		$scope.username = username;			
			$scope.activeMenu = "projectdetails";
			$scope.entity= {};
			 
			  
			  $scope.selectAction = function () {
				  
				var selectedoption= $("#clientid option:selected").text();
				
				 $('#selectedclientid').val(selectedoption);
			  }
			  
		    /*	$scope.addProject = function(){
		    		var i = 0;
		    		$('#project')
					.submit(
							function(event) {
								$
										.ajax({
											type : 'POST',
											url : 'services/project.php?function=addOrUpdateProject',
											data : $(this).serialize(),
											dataType : 'json',
											success : function(data) {
												if (data.success) {
													//alert("added");
													document.getElementById('projectId').value = data.projectId;
														$scope.projectId = data.projecttId;
													document
															.getElementById("responseMessage").innerHTML = "<span style=color:'green'>"
															+ data.message + "</span>";
													document
															.getElementById("responseMessage").style.color = '#008000'; // red or #ffffff
													document
															.getElementById("responseMessage").style.fontWeight = 'bold';
													window.scrollTo(500, 0);
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
								// stop the form from submitting and refreshing
								event.preventDefault();
							});
		    	};
			  */
			  
			  
			  
			  
			  
			  
			  
			  
			  
			
			
		    $scope.editprojectdetid = projectdetID;	
			$scope.projectdetailsstatusid = [{projectdetailsstatusid:'0',projectdetailsstatusname: 'active'}, {projectdetailsstatusid: '1',projectdetailsstatusname: 'inactive'}]	
			$scope.projectdetailsprojectstatusid = [{projectdetailsprojectstatusid:'1',projectdetailsprojectstatusname: 'assigned'}, {projectdetailsprojectstatusid:'2',projectdetailsprojectstatusname: 'inprogress'},{projectdetailsprojectstatusid:'3',projectdetailsprojectstatusname: 'completed'}]
			$scope.projectdetailsdepartmentid=[{projectdetailsdepartmentid:'1',name:'Roto'},{projectdetailsdepartmentid:'2',name:'Paint'},{projectdetailsdepartmentid:'3',name:'Match move'},{projectdetailsdepartmentid:'4',name:'Compositing'},{projectdetailsdepartmentid:'5',name:'3D'}]
			$scope.transfermodeid=[{transfermodeid:'1',name:'cash'},{transfermodeid:'2',name:'cheque'},{transfermodeid:'3',name:'NEFT'}]
			
			 
			
			 $scope.selectpay = function () {
				$("#receivedamount1").removeAttr('style');
				$("#pendingamount1").removeAttr('style');
				$("#amtreceiveddate1").removeAttr('style');
				$("#followdate1").removeAttr('style');			
			 }
			
			 $scope.selectpay1 = function () {
				$("#receivedamount2").removeAttr('style');
				$("#pendingamount2").removeAttr('style');
				$("#amtreceiveddate2").removeAttr('style');
				$("#followdate2").removeAttr('style');			
			 }
			 
			 $scope.selectpay2 = function () {
				$("#receivedamount3").removeAttr('style');
				$("#pendingamount3").removeAttr('style');
				$("#amtreceiveddate3").removeAttr('style');
				$("#followdate3").removeAttr('style');			
			 }
			
					
			initController();
				   
            function initController() {			
            getUserRolefields();
            retrieveProject();
			getfieldspayment();
			getClientNameList();
			getEntityStatus();
			getAccessDetails(); 
			getentityid();    							
			}	
            
            
            function retrieveProject(){
            	var url = window.location.href;
            	var projectId = url.split(":")[2];
            	$.ajax({
				    type        : 'POST',
				    url         : 'services/project.php?function=retrieveProject&projectId='+projectId,
				    data        : $(this).serialize(),
					dataType    : 'json',  
				    success     : function(data) {		
				    	$scope.entity = data[0];
				      }
				  });            	
            }
            
            function getEntityStatus(){   
        		var url = 'services/getEntityStatus';
			   		UserService.getApiCall(url).then(function (data) {				
				    $scope.entitystatus = data.data;  				    
		            });           
		   }
				
            function getUserRolefields(){   	
   			   UserService.getUserRolefields(6).then(function (data) {				
   				    $scope.projectFields = data.data;
   				   // alert("current field" + $scope.clientFields);
   		            });           
   		   }	
			function getfieldspayment()
		    {
		    var payment="payment";
            UserService.getFields(payment).then(function (data) {				
		    $scope.fieldspayment = data.data;			
            	});
         	} 		
		   function getClientNameList()
		    {					
			   $.ajax({
				    type        : 'POST',
				    url         : 'services/client.php?function=getClientNameList',
				    data        : $(this).serialize(),
					dataType    : 'json',  
				    success     : function(data) {		
				    	$scope.clientList = data;				    	
				      }
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
			var name="projectdetails";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 	
	}; 
})();