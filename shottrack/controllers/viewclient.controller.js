(function () {
    'use strict';

    angular
        .module('app')
        .controller('ViewClientController', ViewClientController);

    ViewClientController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','client'];
    function ViewClientController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,client) {
		
		
		var userid = $cookieStore.get('userid');	//loggedin userid	
		 var username = $cookieStore.get('username');
		     $scope.username = username;	
		var clientID = ($routeParams.client_id) ? parseInt($routeParams.client_id) : 0;//edit userid
		
    	$rootScope.title = (clientID > 0) ? 'View Client' : 'View Client';
    	
		
		  	
				//getting particular customer value
			var original = client.data;			
			$scope.clients= original;
			$scope.activeMenu = "client";
			initController();				   
            function initController() {			
			
			getAccessDetails();
			getentityid();   							
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
	}

})();