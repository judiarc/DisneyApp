

(function () {
    'use strict';
   var empapp= angular
        .module('app')
        .controller('ShotallocController', ShotallocController);
	<!-- creating own service to export as excel -->	
	empapp.factory('Excel',function($window){
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
    
    ShotallocController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','Excel','$timeout','$http'];
    function ShotallocController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,Excel,$timeout,$http) {
		
		var shotallocID = ($routeParams.shotalloc_id) ? parseInt($routeParams.shotalloc_id) : 0;
		
		var vm = this;
		vm.user = null;
		var userid = $cookieStore.get('userid');
		 var username = $cookieStore.get('username');
		     $scope.username = username;			
		vm.deleteShot = deleteShot;
		$scope.activeMenu = "shotallocation";
		 	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100); // trigger download
        }
		
		$scope.sort = function(keyname){
		$scope.sortKey = keyname;   //set the sortKey to the param passed
		$scope.reverse = !$scope.reverse; //if true make it false and vice versa
	}
	
		$scope.shotallocationdepartmentid=[{shotallocationdepartmentid:'1',name :'Roto'},{shotallocationdepartmentid:'2',name :'Paint'},{shotallocationdepartmentid:'3',name :'Match move'},{shotallocationdepartmentid:'4',name :'Compositing'},{shotallocationdepartmentid:'5',name :'3D'}]	
		
		$scope.shotallocid=shotallocID;	
		
		
				 $scope.selectAction = function () {						
					if(($("#departmentid option:selected").val())!='')
					 {
					var deptIdTemp = parseInt($("#departmentid option:selected").val());
					var deptId = deptIdTemp + 1;
					}
					else
						{
						var deptId='';
						}
					if(($("#projectid option:selected").val())!='')
					{
						var projectIdTemp =  parseInt($("#projectid option:selected").val());
					var projectId=projectIdTemp+1;
					}
					else
						{
						var projectId='';
						}
					getAllShot(deptId, projectId);
				 }
				 
				  
		
		
       initController();
	   
        function initController() {			
			getUserDetails();          
			loadAllEmpshot();
			getAccessDetails();			
			getuserrole(); 		
			getProjectDetails();
			}	
        
        	function getProjectDetails() {
		        UserService.getProjectDetails().then(function (data) {	
		        //	alert(data.data);
						 $scope.shotallocationprojectdetailsid = data.data;	
				 });
        	}
			function getuserrole()
			{
			 UserService.getuserrole().then(function (data) {				
					$scope.userroleid = data.data;
			 });
			}	
			function assignstatusroto()
			{
			UserService.assignstatusroto().then(function (data) {				
		    $scope.assignstatusr= data.data;
				 });
			}
			function assignstatuspaint()
			{
			UserService.assignstatuspaint().then(function (data) {				
		    $scope.assignstatusp= data.data;
				 });
			}
			function assignstatusmatchmove()
			{
			UserService.assignstatusmatchmove().then(function (data) {				
		    $scope.assignstatusm= data.data;
				 });
			}
			function assignstatuscomp()
			{
			UserService.assignstatuscomp().then(function (data) {				
		    $scope.assignstatusc= data.data;
				 });
			}function assignstatus3d()
			{
			UserService.assignstatus3d().then(function (data) {				
		    $scope.assignstatusdS= data.data;
				 });
			}
		function getUserDetails()
		{
			 UserService.getUserDetails(userid).then(function (user) {				
					vm.user = user.data;
			 });
		}
		 function loadAllEmpshot() {
			 getAllShot("", "");
		 }
		 
		 function getAllShot(deptId, projectId) {
			 UserService.getAllempshot(shotallocID,deptId, projectId).then(function (data) {
					$scope.users = data.data;
                });
		 }
		 function deleteShot() {	
			 	var checkedValue = ''; 
			 	var inputElements = document.getElementsByClassName('checkbox');
			 	for(var i=0; inputElements[i]; ++i){
			 	      if(inputElements[i].checked){
			 	           checkedValue += inputElements[i].value + ",";
			 	      }
			 	}
			 	
			 	checkedValue = checkedValue.substr(0, checkedValue.length-1);	
			 	//alert("checkedValue" + checkedValue);
			 	if(confirm("Are you sure to delete Shot")==true)
			 		$.ajax({
					    type        : 'POST',
					    url         : 'services/shot.php?function=removeShot&shotId='+checkedValue,
					    data        : $(this).serialize(),
						dataType    : 'json',  
					    success     : function(data) {		
					    	if(data == 'success'){
						    	alert("Shot Id " + checkedValue + " Deleted Successfully");
						    	location.reload(true);
					    	}else {
					    		alert("Client Id " + checkedValue + " was not deleted, due to technical issue");
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
		 

	 $("#delete").click(function (e) {
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
		 if(confirm("Are you sure to delete Shot")==true)
        $.ajax({
            type: 'POST',
            url: "includes/deleteshot.php",
            data: {
                id: ids
            },
            success: function (data) {
                alert(data);
				 setTimeout("location.reload(true);",1000);
                           }
        });
    } else {
        alert("Please select items.");
    		}
 		}
 	})
	
	
	
	$("#approveit").click(function (e) {
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
		 if(confirm("Are you sure to approve Shot")==true)
        $.ajax({
            type: 'POST',
            url: "includes/approveshot.php",
            data: {
                id: ids
            },
            success: function (data) {
                alert(data);
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