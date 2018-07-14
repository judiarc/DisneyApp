(function () {
    'use strict';
    angular
           .module('app')
           .controller('EditArtistController', EditArtistController);
		    EditArtistController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','artist'];
		    function EditArtistController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,artist) {

		    	var artistID = ($routeParams.artist_id) ? parseInt($routeParams.artist_id) : 0;// edit
		    	var vm = this;																				// userid
		    	vm.user = null;
    	    $rootScope.title = (artistID > 0) ? 'Edit Artist' : 'Add Artist';
    	    $scope.buttonText = (artistID > 0) ? 'Update Artist' : 'Add New Artist';	
			var userid = $cookieStore.get('userid');
			 var username = $cookieStore.get('username');
		    $scope.username = username;			
			$scope.activeMenu = "artist";
			var loggeduser = $cookieStore.get('username');
			var artistid = $cookieStore.get('artist_id');
			//console.log("artist id "+artistid);
			var original = artist.data;
			$scope.artists= original;
			if(original)
			{			
			$scope.entity= angular.copy(original);
			}
			else
			{
				$scope.entity= {};
			}
			
			$scope.redirect = function(){
 			window.location = "#/artist.html";
			}
			$scope.artistDetails = {};
		    $scope.editartistid = artistID;	
			$scope.artiststatusid = [{artiststatusid: '1',name: 'active'}, {artiststatusid:'0',name: 'inactive'}]	
			$scope.artistgenderid = [{artistgenderid:'1',name: 'Male'}, {artistgenderid:'2',name: 'Female'}]	
			$scope.artistlevelid =  [{artistlevelid:'1',name: 'Senior'}, {artistlevelid:'2',name: 'Mid-level'},{artistlevelid:'3',name:'Junior'},{artistlevelid:'4',name:'Team Lead'},{artistlevelid:'5',name:'Supervisor'}]
			$scope.artistdepartmentid=[{artistdepartmentid:'1',name:'Roto'},{artistdepartmentid:'2',name:'Paint'},{artistdepartmentid:'3',name:'Match move'},{artistdepartmentid:'4',name:'Compositing'},{artistdepartmentid:'5',name:'3D'},{artistdepartmentid:'6',name:'VFX'}]
				
			$scope.isSelected = function(listOfItems, item){
				var resArr = '';
				if(listOfItems != null && listOfItems != ''){
				if(listOfItems.indexOf(",") !== -1) {
					resArr = listOfItems.split(",");
				} else {
					resArr = listOfItems;
				}
			}
	    		if (resArr.indexOf(item.toString()) > -1) {
	    		    return true;
	    		  } else {
	    		    return false;
	    		  }
	    	};
				
			 $scope.addChoice = function () 
			 {
				$("#email1").removeAttr('style');
			 }
			  $scope.addChoice1 = function () 
			 {
				$("#email2").removeAttr('style');
			 }
			  $scope.addChoice2 = function () 
			 {
				$("#email3").removeAttr('style');
			 }
			  $scope.addChoice3 = function () 
			 {
				$("#email4").removeAttr('style');
			 }
			  $scope.addmobile = function () 
			 {
				$("#mobile1").removeAttr('style');
			 }
			  $scope.addmobile1 = function () 
			 {
				$("#mobile2").removeAttr('style');
			 }
			  $scope.addmobile2 = function () 
			 {
				$("#mobile3").removeAttr('style');
			 }
			  $scope.addmobile3= function () 
			 {
				$("#mobile4").removeAttr('style');
			 }
			 
			initController();				   
            function initController() {	
            retrieveArtist();
			//getfields();
			getAccessDetails(); 
			getentityid();  
			getLeads();
        	getroles();
        	getUserRolefields();
			//getArtistDetails();
        	getcompany();
        	getlevels();
        	getstatus();
        	getProjectHead();
        	getSupervisors();
        	getdepartments();
			}	
            
            function retrieveArtist(){
            	var url = window.location.href; 
            	var artistIdArray = url.split(":");
               	var artistId = '';
               	if(artistIdArray.length > 2){
               		artistId = artistIdArray[2];
            	$.ajax({
				    type        : 'POST',
				    url         : 'services/artist.php?function=retrieveArtist&artistId='+artistId,
				    data        : $(this).serialize(),
					dataType    : 'json',  
				    success     : function(data) {		
				    	$scope.artistDetails = data[0];
				
				    	}
            	
				  });            	
            }
            }
            
            
            function getdepartments()
            {
            	UserService.getdepartments().then(function (data){
            		$scope.departments=data.data;
            		//alert($scope.departments);
            	});
            }
            
            function getUserRolefields(){   	
 			   UserService.getUserRolefields(3).then(function (data) {				
 				    $scope.userFields = data.data;			
 		            });           
 		   }
            
            function getroles()
    		{
            	UserService.getroles().then(function(data){
 				   $scope.roles=data.data;
 				 
 			   });
    		}
            
            function getcompany()
            {
            	UserService.getCompanies().then(function (data){
            		$scope.companies=data.data;
            	})
            }
            
            function getlevels()
            {
            	UserService.getlevels().then(function(data){
            		$scope.levels=data.data;
            	});
            }
            
            function getstatus()
            {
            	UserService.getstatus().then(function (data){
            		$scope.conditions=data.data; 
            	});
            }
            
            function getArtistDetails()
            {
            	UserService.getArtistDetails(artistid).then(function (artist){
            		vm.artist=artist;
            		
            	})
            }
        
            function getLeads()
    		{
    			 UserService.getLeads().then(function (data) {				
    				 $scope.leads = data.data;
    				// alert($scope.leads);
    			 });
    		}
            function getProjectHead()
            {
            	UserService.getProjectHead().then(function (data) {				
   				 $scope.project_heads = data.data;
   				
   			 });
            }
            
            
            function getSupervisors()
    		{
    			 UserService.getSupervisors().then(function (data) {				
    				 $scope.supervisors = data.data;
    				//alert($scope.supervisors);
    			 });
    		}
			/*function getfields()
		    {
		    var artist="artist";
            UserService.getFields(artist).then(function (data) {				
		    $scope.fields = data.data;			
            	});
        	 } */	
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
			}
})();