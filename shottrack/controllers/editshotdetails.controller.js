(function () {
    'use strict';
    angular
           .module('app')
           .controller('EditShotdetailsController', EditShotdetailsController);
		    EditShotdetailsController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','shotdet'];
		    function EditShotdetailsController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,shotdet) {
				
			var shotdetID = ($routeParams.shotdet_id) ? parseInt($routeParams.shotdet_id) : 0;//edit userid
		
    	    $rootScope.title = (shotdetID > 0) ? 'Edit Shot' : 'Add Shot';
    	    $scope.buttonText = (shotdetID > 0) ? 'Update Shot' : 'Add New Shot';	
			
			var userid = $cookieStore.get('userid');
			 var username = $cookieStore.get('username');
		     $scope.username = username;	
			$scope.activeMenu = "shotdetails";
			var original = shotdet.data;
			if(original)
			{			
			$scope.entity= angular.copy(original);
			}
			else
			{
				$scope.entity= {};
			}
			
		    $scope.editshotdetid = shotdetID;	
			$scope.shotdetailsshotstatusid = [{shotdetailsshotstatusid: '1',shotdetailsshotstatusname: 'assigned'}, {shotdetailsshotstatusid: '2',shotdetailsshotstatusname: 'inprogress'},{shotdetailsshotstatusid: '3',shotdetailsshotstatusname: 'completed'}];
			$scope.shotdetailsstatusid = [{shotdetailsstatusid:'1',shotdetailsstatusname: 'active'}, {shotdetailsstatusid:'0',shotdetailsstatusname: 'inactive'}]
				
			
			initController();
				   
            function initController() {			
			getfields();
			getclientdetails();	
			getprojectdetails();
			getAccessDetails();   								
			}				
			function getfields()
		    {
		    var shotdet="shotdetails";
			var entityname="shotdetails";
            UserService.getFields(shotdet).then(function (data) {				
		    $scope.fields = data.data;			
            	});
         	} 		
		   function getclientdetails()
		    {
				var clientidd=5;
				var idname="clientid";
				var name="clientname";
				var entityname="shotdetails";
				var urlid=shotdetID;	
		    UserService.getClientDetails(clientidd,idname,name,entityname,urlid).then(function (data) {				
		    $scope.shotdetailsclientid = data.data;			
            });
         }
		   function getprojectdetails()
		    {
				var projectid=6;
				var idname="projectdetailsid";
				var name="projectdetailsname";
				var entityname="shotdetails";
				var urlid=shotdetID;	
		    UserService.getProjectDetails(projectid,idname,name,entityname,urlid).then(function (data) {				
		    $scope.shotdetailsprojectdetailsid = data.data;			
            });
         }
		  function getAccessDetails()
		{
			 UserService.getAccessDetails(userid).then(function (data) {				
					$scope.useraccess = data.data;
			 });
		} 
	}; 
})();