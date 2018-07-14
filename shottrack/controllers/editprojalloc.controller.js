(function () {
    'use strict';
    angular
           .module('app')
           .controller('EditProjallocController', EditProjallocController);
		    EditProjallocController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','projallocdet'];
		    function EditProjallocController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,projallocdet) {
				
			var projallocID = ($routeParams.projalloc_id) ? parseInt($routeParams.projalloc_id) : 0;//edit userid
		
    	    $rootScope.title = (projallocID > 0) ? 'Edit ProjectAllocation' : 'Add ProjectAllocation';
    	    $scope.buttonText = (projallocID > 0) ? 'Update ProjectAllocation' : 'Add New ProjectAllocation';	
			
			var userid = $cookieStore.get('userid');
			 var username = $cookieStore.get('username');
		     $scope.username = username;			
			$scope.activeMenu = "projectallocation";
			var original = projallocdet.data;
			if(original)
			{			
			$scope.entity= angular.copy(original);
			}
			else
			{
				$scope.entity= {};
			}
			
		    $scope.editprojallocid = projallocID;	
			$scope.projectallocationstatusid = [{projectallocationstatusid:'1',projectallocationstatusname: 'assigned'}, {projectallocationstatusid:'2',projectallocationstatusname: 'inprogress'},{projectallocationstatusid: '3',projectallocationstatusname: 'completed'}]
			$scope.projectallocationresourcestatusid=[{projectallocationresourcestatusid:'1',projectallocationresourcestatusname:'active'},{projectallocationresourcestatusid:'2',projectallocationresourcestatusname:'inactive'}]	
			
			initController();
				   
            function initController() {			
			getfields();
			getclientdetails();
			getprojectdetails();
			getshotdetails();
			getartistseldetails();	
			getAccessDetails();   								
			}		
				
			function getfields()
		    {
		    var projalloc="projectallocation";
            UserService.getFields(projalloc).then(function (data) {				
		    $scope.fields = data.data;			
            	});
         	} 		
		   function getclientdetails()
		    {
				var clientid=5;
				var idname="clientid";
				var name="clientname";
				var entityname="projectallocation";
				var urlid=projallocID;				
		    UserService.getClientDetails(clientid,idname,name,entityname,urlid).then(function (data) {				
		    $scope.projectallocationclientid = data.data;			
            });
         }
		 function getprojectdetails()
		    {
				var projectid=6;
				var idname="projectdetailsid";
				var name="projectdetailsname";
				var entityname="projectallocation";
				var urlid=projallocID;	
		    UserService.getProjectDetails(projectid,idname,name,entityname,urlid).then(function (data) {				
		    $scope.projectallocationprojectdetailsid = data.data;			
            });
         }
		  function getshotdetails()
		    {
				var shotid=7;
				var idname="shotdetailsid";
				var name="shotdetailsname";
				var entityname="projectallocation";
				var urlid=projallocID;	
		    UserService.getShotDetails(shotid,idname,name,entityname,urlid).then(function (data) {				
		    $scope.projectallocationshotdetailsid = data.data;			
            });
         }
		 function getartistseldetails()
		    {
				var artistid=3;
				var idname="artistid";
				var name="artistname";
				var entityname="projectallocation";
				var urlid=projallocID;	
		    UserService.getArtistselDetails(artistid,idname,name,entityname,urlid).then(function (data) {				
		    $scope.projectallocationartistid  = data.data;			
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