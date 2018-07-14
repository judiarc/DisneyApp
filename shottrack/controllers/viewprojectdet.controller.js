(function () {
    'use strict';

    angular
        .module('app')
        .controller('ViewProjectdetController', ViewProjectdetController);

    ViewProjectdetController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','projectdet'];
    function ViewProjectdetController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,projectdet) {
		
		
		var userid = $cookieStore.get('userid');	//loggedin userid	
		 var username = $cookieStore.get('username');
		     $scope.username = username;	
		var projectdetID = ($routeParams.projectdet_id) ? parseInt($routeParams.projectdet_id) : 0;//edit userid				
    	$rootScope.title = (projectdetID > 0) ? 'View Project Details' : 'View Project Details';
    	
			//getting particular customer value
			var original = projectdet.data;			
			$scope.projectdetails= original;
			$scope.activeMenu = "projectdetails";
			
			initController();				   
            function initController() {										
			getAccessDetails(); 
			getentityid();
			getuserrole();
			getnoshots();			     							
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
		function getuserrole()
		{
			 UserService.getuserrole().then(function (data) {				
					$scope.userroleid = data.data;
			 });
		}
		function getnoshots()
		{
			var projectidd=projectdetID;
			
			 UserService.getshotscount(projectidd).then(function (data) {				
					$scope.shotscount = data.data;
			 });
		} 					
	}

})();