(function () {
    'use strict';

    angular
        .module('app')
        .controller('ViewFreelancersController', ViewFreelancersController);

    ViewFreelancersController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','freelancers'];
    function ViewFreelancersController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,freelancers) {
		
		
		var userid = $cookieStore.get('userid');	//loggedin userid
		 var username = $cookieStore.get('username');
		     $scope.username = username;		
		var freelancersID = ($routeParams.freelancers_id) ? parseInt($routeParams.freelancers_id) : 0;//edit userid
		
    	$rootScope.title = (freelancersID > 0) ? 'View Freelancers' : 'View Freelancers';
    	$scope.buttonText = (freelancersID > 0) ? 'Update Freelancers' : 'Add New Freelancers';
		
		  	
				//getting particular customer value
			var original = freelancers.data;			
			$scope.freelancers= original;
			$scope.activeMenu = "freelancers";
			initController();				   
            function initController() {			
			
			getAccessDetails();
			getuserrole();
			getentityid();     							
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
			var name="freelancers";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 		 			
	}

})();