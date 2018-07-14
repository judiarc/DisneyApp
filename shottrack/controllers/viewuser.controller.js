(function () {
    'use strict';

    angular
        .module('app')
        .controller('ViewUserController', ViewUserController);

    ViewUserController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','customer'];
    function ViewUserController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,customer) {
		
		
		var userid = $cookieStore.get('userid');	//loggedin userid	
		 var username = $cookieStore.get('username');
		     $scope.username = username;	
		var userID = ($routeParams.user_id) ? parseInt($routeParams.user_id) : 0;//edit userid
		var loggedusername = $cookieStore.get('username');

    	$rootScope.title = (userID > 0) ? 'View User' : 'View User';
    	$scope.buttonText = (userID > 0) ? 'Update User' : 'Add New User';
		
		  	
			//getting particular customer value
			var original = customer.data;			
			$scope.users= original;
			$scope.activeMenu = "users";
			initController();	
			
            function initController() {			
			console.log("viewuser.controller");
			getfields();
			getuserentity();
			getAccessDetails(); 
			getentityid();   	  							
			}
            
            
            function getfields()
		    {
		    var user="users";
            UserService.getFields(user).then(function (data) {				
		    $scope.fields = data.data;			
            });
         }
            function getuserentity()
		    {
           UserService.userentity(userID).then(function (data) {				
		   $scope.userentity = data.data;			
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
			var name="users";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 		
		 
//		 function starts here to update existing user details
		 
		 $scope.edituser=function(editid,newname,newpassword,newemail,roleid)
		 {

			 console.log("got user role id "+roleid);
				console.log("fetched values "+editid,newname,newpassword,newemail);
				UserService.updateuser(loggedusername,editid,newname,newpassword,newemail).success(function (response) {
					if (response.success == 'true') {
						 alert('updated successfully');
						 $location.path("/users");
		                }
					 else {
	                     FlashService.Error(response.message);
	                 }
				});

		 }
		 
//		 function ends here for update user
		 
		 $scope.deleteUser=function(id) {
			    if(confirm("Are you sure to delete user")==true)
        		 UserService.Delete(id).success(function (response) {
          			if (response.success == 'true') {
				 alert('deleted successfully');
				 $location.path("/users");
             } else {
                 FlashService.Error(response.message);
                 vm.dataLoading = false;
             }
			 });
     }
		 
	}

})();