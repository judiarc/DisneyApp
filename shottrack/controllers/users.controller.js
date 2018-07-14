

(function () {
    'use strict';

   var userapp= angular
        .module('app')
        .controller('UsersController', UsersController);
	<!-- creating own service to export as excel -->	
	userapp.factory('Excel',function($window){
        	var uri='data:application/vnd.ms-excel;base64,',
            template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>			<x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
            format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
        	return {
            tableToExcel:function(tableId,worksheetName){
                	var table=$(tableId),
                    ctx={worksheet:worksheetName,table:table.html()},
                    href=uri+base64(format(template,ctx));
                return href;
            }
        };
    })
    
    UsersController.$inject = ['$cookieStore','$rootScope','$scope','UserService','FlashService','Excel','$timeout','$http'];
    function UsersController($cookieStore,$rootScope,$scope,UserService,FlashService,Excel,$timeout,$http) {
		var vm = this;
		vm.user = null;
		var userid = $cookieStore.get('userid');
		 var username = $cookieStore.get('username');
		     $scope.username = username;	
		     vm.deleteUser = deleteUser;
			 $scope.activeMenu = "user";
		 	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100); // trigger download
        }
		
		$scope.sort = function(keyname){
		$scope.sortKey = keyname;   //set the sortKey to the param passed
		$scope.reverse = !$scope.reverse; //if true make it false and vice versa
	}
		
       initController();
	   
        function initController() {
			getUserDetails();          
			retrieveUsers();
			getAccessDetails();
			//loadAllUsers();
        }
     
		function getUserDetails()
		{
			// getting details of logged user.. eg., testing
			 UserService.getUserDetails(userid).then(function (user) {
				 	vm.user = user;
			 });
		}
		/*function loadAllUsers()
		{
			UserService.GetAll().then(function (data) {
				 console.log("getting user profile");
					$scope.users = data.data;
					console.log($scope.users);
               });
		}*/
		
		 function retrieveUsers() {
								 
				 $.ajax({
					    type        : 'POST',
					    url         : 'services/user.php?function=retrieveUser',
					    data        : $(this).serialize(),
						dataType    : 'json',  
					    success     : function(data) {	
					    	console.log("getting All Users");
					    	$scope.users = data;		
					    	console.log($scope.users);
					      }
					  });			
			
		 }
		 
		 function deleteUser() {			
			 	var checkedValue = ''; 
			 	var inputElements = document.getElementsByClassName('checkbox');
			 	for(var i=0; inputElements[i]; ++i){
			 	      if(inputElements[i].checked){
			 	           checkedValue += inputElements[i].value + ",";			 	           
			 	      }
			 	}
			 	
			 	checkedValue = checkedValue.substr(0, checkedValue.length-1);			 	
			 	if(confirm("Are you sure to delete user")==true)
			 		$.ajax({
					    type        : 'POST',
					    url         : 'services/user.php?function=removeUser&userId='+checkedValue,
					    data        : $(this).serialize(),
						dataType    : 'json',  
					    success     : function(data) {	
					    	if(data == 'success'){
						    	alert("User Id " + checkedValue + " Deleted Successfully");
						    	location.reload(true);
					    	}else {
					    		alert("User Id " + checkedValue + " was not deleted, due to technical issue");
					    	}
					      }
					  });
     }
		 function getAccessDetails()
		{
			 UserService.getAccessDetails(userid).then(function (data) {				
					$scope.useraccess = data.data;
			 });
		} 
		

 
<!-- this part is for multiple checkbox -->
		 
 
 
 
 $("#user").submit(function (e) {
    e.preventDefault();
    var formId = "submit";
 $(formId)
 {
	   
 var ids = [];
    $(".checkbox").each(function () {
        if ($(this).is(":checked")) {
            ids.push($(this).val());
        }
    });
    if (ids.length) {
		 if(confirm("Are you sure to delete user")==true)
        $.ajax({
            type: 'POST',
            url: "includes/delete.php",
            data: {
                id: ids
            },
            success: function (data) {               
				 setTimeout("location.reload(true);",1000);
                           }
        });
    } else {
        alert("Please select items.");
    		}
 		}
 	})
 }

})();