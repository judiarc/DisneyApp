

(function () {
    'use strict';

   var empapp= angular
        .module('app')
        .controller('ClientController', ClientController);
		
	<!-- creating own service to export as excel -->	
	empapp.factory('Excel',function($window){
        	var uri='data:application/vnd.ms-excel;base64,',
            template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
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
    
    ClientController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','Excel','$timeout','$http','DTOptionsBuilder', 'DTColumnBuilder'];
    function ClientController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,Excel,$timeout,$http,DTOptionsBuilder, DTColumnBuilder) {
		
		var clientID = ($routeParams.client_id) ? parseInt($routeParams.client_id) : 0;
		var vm = this;
		vm.user = null;
		var userid = $cookieStore.get('userid');	
		var username = $cookieStore.get('username');
		$scope.username = username;		
		vm.deleteEmp = deleteEmp;
		
			$scope.activeMenu = "client";
		 	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100); // trigger download
        }
	
		$scope.clientid=clientID;
        initController();
	   
        function initController() {
			getUserDetails();          
			retrieveClient();			
        }
		function getUserDetails()
		{
			 UserService.getUserDetails(userid).then(function (user) {				
					vm.user = user;
			 });
		}
		 function retrieveClient() {			 
			 $.ajax({
				    type        : 'POST',
				    url         : 'services/client.php?function=retrieveClient',
				    data        : $(this).serialize(),
					dataType    : 'json',  
				    success     : function(data) {		
				    	$scope.clients = data;				    	
				      }
				  });			
		 }
		
		 function deleteEmp() {			
			 	var checkedValue = ''; 
			 	var inputElements = document.getElementsByClassName('checkbox');
			 	for(var i=0; inputElements[i]; ++i){
			 	      if(inputElements[i].checked){
			 	           checkedValue += inputElements[i].value + ",";
			 	      }
			 	}
			 	
			 	checkedValue = checkedValue.substr(0, checkedValue.length-1);			 	
			 	if(confirm("Are you sure to delete Client")==true)
			 		$.ajax({
					    type        : 'POST',
					    url         : 'services/client.php?function=removeClient&clientId='+checkedValue,
					    data        : $(this).serialize(),
						dataType    : 'json',  
					    success     : function(data) {		
					    	if(data == 'success'){
						    	alert("Client Id " + checkedValue + " Deleted Successfully");
						    	location.reload(true);
					    	}else {
					    		alert("Client Id " + checkedValue + " was not deleted, due to technical issue");
					    	}			    	
					      }
					  });
        }
		/*function getuserrole()
		{
			 UserService.getuserrole().then(function (data) {				
					$scope.userroleid = data.data;
			 });
		}*/
 
	<!-- this part is for multiple checkbox -->
		 


	 $("#empform").submit(function (e) {
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
            url: "includes/deleteemp.php",
            data: {
                id: ids
            },
            success: function (data) {
                //alert(data);
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