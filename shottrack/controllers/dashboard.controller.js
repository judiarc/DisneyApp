(function () {
    'use strict';

    angular
        .module('app')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = ['$window','$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','Excel','$timeout','$http','$location'];
    function DashboardController($window,$cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,Excel,$timeout,$http,$location) {
		var vm = this;
		vm.user = null;
		var userid = $cookieStore.get('userid');
		 var username = $cookieStore.get('username');
		$scope.username = username;		
		$scope.loggedId=userid;
		//alert("dashboard controller "+$scope.loggedId);
		$scope.activeMenu = "dashboard";
			
        initController();
		
		
$scope.logout = function ()
    {
      		 $cookieStore.remove("userid");
			 $cookieStore.remove("username");
			 $window.location.href='#/login';
		
    };
		$scope.saveDashBoard = function(){
			// alert("saveDashBoard");
			var dashboardRetrievalQuery = document.getElementById("dashboardQuery").value;
			var dashboardName = document.getElementById("dashboardName").value;
			var retrieveLevel = document.getElementById("level").value;	
			//alert("dashboardRetrievalQuery" + dashboardRetrievalQuery);
			if(dashboardRetrievalQuery != "" && dashboardName != "" && retrieveLevel != "" ){	
				saveOrSearchDashBoard(dashboardName, dashboardRetrievalQuery, retrieveLevel, "save");
			} else{
				alert("Dashboard Name, Dashboard Query and Level is Mandatory");
			}
			// saveDashBoard(dashboardName, dashboardRetrievalQuery,
			// retrieveLevel);
			// alert("dashboardSave");
		};
		
		$scope.searchDashBoard = function(){
			// alert("saveDashBoard");
			var dashboardRetrievalQuery = document.getElementById("dashboardQuery").value;
			var dashboardName = document.getElementById("dashboardName").value;
			var retrieveLevel = document.getElementById("level").value;	
			//alert("dashboardRetrievalQuery" + dashboardRetrievalQuery);
			if(dashboardRetrievalQuery != "" && retrieveLevel != "" ){
				saveOrSearchDashBoard(dashboardName, dashboardRetrievalQuery, retrieveLevel, "search");
			} else{
				alert("Dashboard Name, Dashboard Query and Level is Mandatory");
			}
			// saveDashBoard(dashboardName, dashboardRetrievalQuery,
			// retrieveLevel);
			// alert("dashboardSave");
		};
		
		
		$scope.viewDetails = function(shotDetail){		
			$scope.shotdetailFlag = true;
			$scope.shotDashboardDetail = shotDetail;
			buttonEnableDisable();
			// alert("shotdetails value" + $scope.shotDashboardDetail.id);
		};
		$scope.startShotWork = function(){		
			startShotWork();		
			buttonEnableDisable();
		};
		$scope.pauseShotWork = function(){		
			pauseShotWork();
			buttonEnableDisable();
		};
		$scope.completeShotWork = function(){		
			completeShotWork();
			buttonEnableDisable();
		};
		
		/*
		 * $scope.viewDetails = function(){ alert("viewDetails value"); };
		 */
		$scope.getDashboardDetails  = function(){
			alert("getDashboardDetails");
			var dashboardName = document.getElementById("dashboardName").value;
			// alert("getDashboardDetails" + dashboardRetrievalQuery);
			getDashBoardDetails(dashboardName);			
		};
		
		$scope.submitWorkDetails  = function(formName){
			var i = 0;
			$('#'+formName).submit(function(event) {
				i += 1;
				// process the form
				if(i == 1) {
				  $.ajax({
				    type        : 'POST',
				    url         : 'services/process.php?function=addShotAllocWorkDetail',
				    data        : $(this).serialize(),
					 dataType    : 'json',  
				    success     : function(data) {

				      // log data to the console so we can see
				      console.log(data);

				        // if validation is good add success message
						if ( data.success) {		
				       			alert(data.message);
								// window.location =
								// '#/shotallocation/'+entity+'&version='+version;
								// window.location.reload(true);
						}
				      }
				  });
				}
				  // stop the form from submitting and refreshing
				  event.preventDefault();
			});				
		};
		
		$scope.isSelected = function(listOfItems, item){		    		
    		var resArr = listOfItems.split(",");
    		if (resArr.indexOf(item.toString()) > -1) {
    		    return true;
    		  } else {
    		    return false;
    		  }
    	};
		
	<!-- Intialising all functions -->   
        function initController() {
        	//alert("dashboard controller");
		/*	getUserDetails();
			getDashBoardDetails(null);
			getDashBoardList();
			getAccessDetails();
			getLeads();
			getShotStatus();*/
			getShotList();
			}	
        
        function getShotList()
        {
        	var userid = $cookieStore.get('userid');
        	$scope.loggedId=userid;
        	
        	$.ajax({
			    type        : 'POST',
			    url         : 'services/dashboard.php?function=getShotList&userId='+$scope.loggedId,
			  //  url         : 'services/dashboard.php?function=getShotList&userId=47',
			    data        : $(this).serialize(),
				dataType    : 'json',  
			    success     : function(data) {
			    	$scope.shotdetails=data;
			      }
			  });
        }
        
        
  /*      
        
        function viewDetails(id){				
			alert("Anchor ----" + id);			
		}
        
        function saveOrSearchDashBoard(dashboardName, dashboardRetrievalQuery, retrieveLevel, operation){
        	$.ajax({
			    type        : 'POST',
			    url         : 'services/process.php?function=saveOrSearchDashBoard&dashboardName='+dashboardName+
        		'&dashboardRetrievalQuery='+dashboardRetrievalQuery+'&retrieveLevel='+retrieveLevel+'&dashBoardOperation='+ operation,
			    data        : $(this).serialize(),
				dataType    : 'json',  
			    success     : function(data) {
			    	
			      // log data to the console so we can see
			      console.log(data);
			    //  alert(data);
			      if(data.status == false) {
			    	 // alert(12585);
			    	//  alert(data.message);
			      }else {
			    	  dashboardGridDisplay(data);
			      }
			        // if validation is good add success message
					
			      }
			  });
        }
        
        function buttonEnableDisable(){
        	var shotDashboardDetail = $scope.shotDashboardDetail;
        	if(shotDashboardDetail) {
        		
        	}
        }
        
        function getDashBoardList(){
        	UserService.getDashBoardList().then(function (response) {
        		$scope.dashBoardList = response.data;
        	});
        }
		function getUserDetails()
		{
			
			document.getElementById('home').className = 'active';
			document.getElementById('artist').className = '';			
			document.getElementById('users').className = '';
			document.getElementById('client').className = '';
			document.getElementById('vendor').className = '';
			document.getElementById('project').className = '';
			document.getElementById('shot').className = '';
			 UserService.getUsername(userid).success(function (response) {				
					if (response != "unauthorized")
							 {
										$cookieStore.put('username',response);												
										response = { success: true };
								}
					else 
								{
                           			 response = { success: false, message: 'Username or password is incorrect' };
                       					 }
			 });
		}
		
		
		function getDashBoardDetails(dashboardName)		
		{
			var userid = $cookieStore.get('userid');
			 //alert("userid" + userid);
			 UserService.getDashBoardDetails(dashboardName, userid).then(function (data) {				
					dashboardGridDisplay(data);
			 });
		
		}
		function dashboardGridDisplay(data) {
			$scope.dashboarddetails = data.data;
			var output = $scope.dashboarddetails;
			var col = [];
			var filterCol = ['id','shotcode', 'worker_name', 'dept', 'framesgiven', 'complexityid', 'workrange', 'internalmandays', 'version', 'status', 'allocated_hours', 'worked_hours', 'qcperson'];
	        for (var i = 0; i < output.length; i++) {
	            for (var key in output[i]) {
	                if (col.indexOf(key) === -1 && filterCol.indexOf(key) != -1) {
	                    col.push(key);
	                }
	            }
	        }
	        $scope.headers = col;
		}
		function saveDashBoard(dashboardName, dashboardRetrievalQuery, retrieveLevel)		
		{
			UserService.saveDashBoard(dashboardName, dashboardRetrievalQuery, retrieveLevel).then(function (data) {	
				 
			 });
		}
		function getAccessDetails()
		{
			 UserService.getAccessDetails(userid).then(function (data) {				
					$scope.useraccess = data.data;
			 });
		} 
		
		function startShotWork(){	
			var shot_dept_artist_details_id = document.getElementById("shot_work_detail_id").value;			
			UserService.startShotWork(shot_dept_artist_details_id).then(function (data) {				
				//alert(data.data.message);
				$scope.shotDashboardDetail.actual_start_date = data.data.actual_start_date;
		 });		
		}
		function pauseShotWork(){
			var shot_dept_artist_details_id = document.getElementById("shot_work_detail_id").value;
			// alert("scope.shotDashboardDetail.worked_hours" +
			// $scope.shotDashboardDetail.worked_hours);
			UserService.pauseShotWork(shot_dept_artist_details_id, $scope.shotDashboardDetail.worked_hours).then(function (data) {				
			//	alert(data.data.message);
		 });		
		}
		function completeShotWork(){
			var shot_dept_artist_details_id = document.getElementById("shot_work_detail_id").value;
			// alert("scope.shotDashboardDetail.worked_hours" +
			// $scope.shotDashboardDetail.worked_hours);
			UserService.completeShotWork(shot_dept_artist_details_id, $scope.shotDashboardDetail.worked_hours).then(function (data) {				
				//alert(data.data.message);
				$scope.shotDashboardDetail.actual_start_date = data.data.actual_completed_date;
		 });				
		}
			
		function getShotStatus()
		{
		 UserService.getShotStatus().then(function (data) {				
				$scope.statusList = data.data;				
		 });
		}
		
		function getLeads()
		{
			 UserService.getLeads().then(function (data) {				
				 $scope.leads = data.data;
				// alert($scope.leads);
			 });
		}*/
		 
}

})();