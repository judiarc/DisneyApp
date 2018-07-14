(function () {
    'use strict';

    angular
        .module('app')
        .controller('ViewVendorController', ViewVendorController);

    ViewVendorController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','vendor'];
    function ViewVendorController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,vendor) {
		
		
		var userid = $cookieStore.get('userid');	//loggedin userid	
		 var username = $cookieStore.get('username');
		     $scope.username = username;	
		var vendorID = ($routeParams.vendor_id) ? parseInt($routeParams.vendor_id) : 0;//edit userid
		
    	$rootScope.title = (vendorID > 0) ? 'View Vendor' : 'View Vendor';
    	$scope.buttonText = (vendorID > 0) ? 'Update User' : 'Add New User';
		
		  	
				//getting particular customer value
			var original = vendor.data;			
			$scope.vendors= original;
			$scope.activeMenu = "vendor";
			initController();				   
            function initController() {			
			getuserrole();
			getAccessDetails();
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
			var name="vendor";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 		 			
	}

})();