(function () {
    'use strict';

    angular
        .module('app')
        .controller('ViewArtistController', ViewArtistController);

    ViewArtistController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','artist'];
    function ViewArtistController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,artist) {
		
		
		var userid = $cookieStore.get('userid');	//loggedin userid	
		 var username = $cookieStore.get('username');
		     $scope.username = username;	
		var artistID = ($routeParams.artist_id) ? parseInt($routeParams.artist_id) : 0;//edit userid
    	$rootScope.title = (artistID > 0) ? 'View Artist' : 'View Artist';
    	
		var loggeduser = $cookieStore.get('username');

    	
				//getting particular customer value
			var original = artist.data;			
			$scope.artists= original;
			$scope.activeMenu = "artist";
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
			var name="artist";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 	
		 
		 $scope.editartist=function(artistid,addname,addemail,adddob,addmobile,addaddress1,addaddress2,addcity,addstate,addcountry,adddoj,addexperience,addgender,addctc,addrole,addsupervisor,addprojecthead,addlevel,addoutputmanday,adddol,addstatus)
		 {
			 console.log("Logged user name "+loggeduser);
			 
			 UserService.updateArtist(loggeduser,artistid,addname,addemail,adddob,addmobile,addaddress1,addaddress2,addcity,addstate,addcountry,adddoj,addexperience,addgender,addctc,addrole,addsupervisor,addprojecthead,addlevel,addoutputmanday,adddol,addstatus).success(function(response){
				 if (response.success == 'true') {
					 alert('User added successfully');
					 $location.path("/users");
	                }
				 else {
                     FlashService.Error(response.message);
                 } 
				 
			 });
			 
			 
		 }
		 
	}

})();