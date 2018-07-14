(function () {
    'use strict';
    angular
           .module('app')
           .controller('EditProjectpaymentController', EditProjectpaymentController);
		    EditProjectpaymentController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','projectpayment'];
		    function EditProjectpaymentController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,projectpayment) {
				
			var projectpaymentID = ($routeParams.entityid) ? parseInt($routeParams.entityid) : 0;//edit userid
		
    	    $rootScope.title = (projectpaymentID > 0) ? 'Edit Project' : 'Add Project';
    	    $scope.buttonText = (projectpaymentID > 0) ? 'Update Project' : 'Add New Project';	
			var userid = $cookieStore.get('userid');
			 var username = $cookieStore.get('username');
		$scope.username = username;			
			$scope.activeMenu = "projectdetails";
			
			var original = projectpayment.data;
			if(original)
			{			
			$scope.entity= angular.copy(original);
			}
			else
			{
				$scope.entity= {};
			}
			
		    $scope.editprojectdetid = projectpaymentID;	
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
			getfields();
			getfieldspayment();
			getclientdetails();	
			getAccessDetails(); 
			getentityid();    							
			}		
				
			function getfields()
		    {
		    var projectdet="projectdetails";
            UserService.getFields(projectdet).then(function (data) {				
		    $scope.fields = data.data;			
            	});
         	} 		
			function getfieldspayment()
		    {
		    var payment="payment";
            UserService.getFields(payment).then(function (data) {				
		    $scope.fieldspayment = data.data;			
            	});
         	} 		
		   function getclientdetails()
		    {
				var clientidd=5;
				var idname="clientid";
				var name="clientname";
				var entityname="projectdetails";
				var urlid=projectpaymentID;	
		    UserService.getClientDetails(clientidd,idname,name,entityname,urlid).then(function (data) {				
		    $scope.projectdetailsclientid = data.data;			
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