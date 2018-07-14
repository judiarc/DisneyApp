(function () {
    'use strict';

    angular
        .module('app')
        .controller('EditUserController', EditUserController);

    EditUserController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','customer'];
    function EditUserController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,customer) {
    	$scope.usersDetails = {};
		var userid = $cookieStore.get('userid');	//loggedin userid	
		var userID = ($routeParams.user_id) ? parseInt($routeParams.user_id) : 0;//edit userid
		var loggedusername = $cookieStore.get('username');

    	$rootScope.title = (userID > 0) ? 'Edit User' : 'Add User';
    	$scope.buttonText = (userID > 0) ? 'Update User' : 'Add New User';
    	
    	
		   $scope.edituserid = userID;
		   var userid = $cookieStore.get('userid');	
		    var username = $cookieStore.get('username');
		    var userRoleId = $cookieStore.get('userRole');
		    $scope.userRole = userRoleId;
		     $scope.username = username;
			$scope.activeMenu = "users";
			var original = customer.data;
		
			
			//alert(JSON.stringify(original));
			if(original)
			{			
			$scope.entityu= angular.copy(original);
			}
			else
			{
				$scope.entityu= {};
			}
			
				$scope.userentity={};
			$scope.getItems = function(endpoint) {
      		var data = UserService.getItems(endpoint);
     		 return data;
			}
			
		<!--	 this part is for multiple checkbox edit -->
		
			 $scope.isChecked = function(id){
      			var match = false;
     			 for(var i=0 ; i < $scope.userentity.length; i++) {
        		 if($scope.userentity[i].entity_id == id){
         		 match = true;
       			 }
     		 }
     			 return match;
  			};
  
			$scope.usergenderid = [{usergenderid:'1',name: 'male'}, {usergenderid:'2',name: 'female'}]	
			
			
			<!-- this part is for multiple checkbox -->
		 
 
	$(document).ready(function(){
	
    $('#select_all').on('click',function(){
		
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        	}
    	});
	});
 
 
		    initController();	   
            function initController() {			
			access();	
			entity();		
			getUserRolefields();
			retrieveUser();
			getroles();
			getCompanies();
			getEntityStatus();
		   }
            
            function getEntityStatus(){   
        		var url = 'services/getEntityStatus';
			   		UserService.getApiCall(url).then(function (data) {				
				    $scope.entitystatus = data.data;  				    
		            });           
		   }
		   
		   function access()
		    {
           UserService.access().then(function (data) {				
		   $scope.userroleid = data.data;			
            });
          } 
		   function getCompanies()
		   {
			   UserService.getCompanies().then(function (data){
				   $scope.companies=data.data;
			   });
		   }
		   function getroles()
		   {
			   UserService.getroles().then(function (data){
				   $scope.roles=data.data;
			   });
		   }
		   
		   function retrieveUser(){
           	var url = window.location.href;           	
           	var userIdArray = url.split(":"); 
           	//alert(userIdArray.length);
           	var userId = '';
           	if(userIdArray.length > 2){
           		userId = userIdArray[2];          
           		//$( "#artiststable" ).DataTable();
           	$.ajax({
           		
				    type        : 'POST',
				    url         : 'services/user.php?function=retrieveUser&userId='+userId,
				    data        : $(this).serialize(),
					dataType    : 'json',  
					success     : function(data) {
						$scope.usersDetails = data[0];
					
						
				      }
           
				  });
           	}
           	
		   }
		   
		   function getUserRolefields(){   	
			   UserService.getUserRolefields(2).then(function (data) {				
				    $scope.userFields = data.data;			
		            });           
		   }
		   
			 function entity()
		    {
           UserService.entity().then(function (data) {				
		   $scope.entity = data.data;			
            });
          }  
			 
		   function getuserentity()
		    {
           UserService.userentity(userID).then(function (data) {				
		   $scope.userentity = data.data;			
            });
          }  
		  function getfields()
		    {
		    var user="users";
            UserService.getFields(user).then(function (data) {				
		    $scope.fields = data.data;			
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
		 
 }


})();