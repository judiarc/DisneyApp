(function () {
    'use strict';
    angular
    .module('app').directive('datetimepicker', function(){
        return {
            restrict: 'A',
            link: function(scope, element, attrs){
                // binding timepicker from here would be ensure that
                // element would be available only here.getShotAlloc()
                element.datetimepicker({
                	dateFormat: "dd/mm/yy hh:mm"}); // your code would be here.
            }
        }
    });
    angular
           .module('app')
           .controller('EditShotallocController', EditShotallocController);
    
		    EditShotallocController.$inject = ['$cookieStore','$routeParams','$route','$rootScope','$scope','UserService','FlashService','$location','shotallocdet','shotallocdetshotstatusid'];
		    function EditShotallocController($cookieStore,$routeParams,$route,$rootScope,$scope,UserService,FlashService,$location,shotallocdet,shotallocdetshotstatusid) {
		    	$scope.getShotDet = function(){		    		
		    		$scope.selected=5;		    		
		    		getShotDet();	
		    		retreiveComments();
		    	};
		    	$scope.datetime="0000-00-00 00:00:00.000000";
		    	$scope.isSelected = function(listOfItems, item){		    		
		    		var resArr = listOfItems.split(",");
		    		if (resArr.indexOf(item.toString()) > -1) {
		    		    return true;
		    		  } else {
		    		    return false;
		    		  }
		    	};
		    	
		    	$scope.back = function() {
		    		window.location = "/shottrack/#/shotdetails";
		    	}
		    	
		    	$scope.getShotFinal = function(){
		    		$scope.selected=6;
		    		
		    		 UserService.getUserRolefields(13).then(function (data) {				
						   	$scope.uploadFields = data.data;
						   	alert($scope.uploadFields);
				            });           
		    		
		    		retreiveComments();
		    	}
		    	
		    	$scope.startShotWork = function(shotworkId,id,startTime, pauseTime, status){		
					startShotWork(shotworkId);		
					buttonEnableDisable1(shotworkId,id,startTime, pauseTime, status);
				};
				$scope.pauseShotWork = function(shotworkId,id,startTime, pauseTime, status){		
					pauseShotWork(shotworkId);
					buttonEnableDisable2(shotworkId,id,startTime, pauseTime, status);
				};
				$scope.completeShotWork = function(shotworkId,id,startTime, pauseTime, status){		
					completeShotWork(shotworkId);
					buttonEnableDisable3(shotworkId,id,startTime, pauseTime, status);
				};
		    	$scope.getShotAlloc = function(){
		    		$scope.selected=2;
		    		getShotAllocWorkDetail();
		    		retreiveComments();
		    	};
		    	
		    	$scope.addOrUpdateComment = function($entityId, $feedback, $feedbackpath){
		    		addOrUpdateComment($entityId, $feedback, $feedbackpath);
		    	}
		    	
				
				var shotallocID = ($routeParams.shotalloc_id) ? parseInt($routeParams.shotalloc_id) : 0;// edit
																										// userid
				// alert("shotallocID--" + shotallocID);
				var shotstatusid = ($routeParams.shotstatusid) ? parseInt($routeParams.shotstatusid) : 0;// shotstatusid
				var version = ($routeParams.version) ? parseInt($routeParams.version) : 0;// shotstatusid
		    	$scope.addShot = function() {		    		
		    		var i = 0;
		    		$('#formaddshot').submit(function(event) {
		    			
		    			i += 1;
		    			// alert(i);
		    			if(i == 1) {		    				
		    			 // process the form
		    			  $.ajax({
		    			    type        : 'POST',
		    			    url         : 'services/shot.php?function=addshot',
		    			    data        : $(this).serialize(),
		    				dataType    : 'json',  
		    			    success     : function(data) {
		    			    	
		    			      // log data to the console so we can see
		    			    // console.log(data);

		    			        // if validation is good add success message
		    					if (data.success) {	
		    						shotallocID	= data.shot_det_id;
									// alert('data'+data.shot_det_id);
										document.getElementById('shot_det_id').value = data.shot_det_id;
										document
												.getElementById("responseMessage").innerHTML = "<span style=color:'green'>"
												+ data.message + "</span>";
										document
												.getElementById("responseMessage").style.color = '#008000'; // red
																											// or
																											// #ffffff
										document
												.getElementById("responseMessage").style.fontWeight = 'bold';
										window.scrollTo(500, 0);
		    			       			// alert(data.message);
		    			       			shotallocID = data.entity;
		    			       			$scope.shot_det_id = data.shot_det_id;
		    			       			version = data.version;	
		    			       			$routeParams.shotalloc_id = data.entity;
		    			       			$routeParams.version  = data.version;		    			      			
		    							// window.location =
										// '#/shotallocation/12';
		    						// window.location.reload(true);
		    					}else {

									document
									.getElementById("responseMessage").innerHTML = "<span style=color:'red'>"
									+ data.message + "</span>";
							document
									.getElementById("responseMessage").style.color = '#ffffff'; // red																										// or
																										// #ffffff
							document
									.getElementById("responseMessage").style.fontWeight = 'bold';
								
		    					}
		    			      }
		    			  });
		    			}

		    			  // stop the form from submitting and refreshing
		    			  event.preventDefault();
		    			});
		    	}
		    	$scope.submitShotDetails=function($dept, $shotDet){	
		    		
		    		var i = 0;
				$('#formaddshotdept'+$dept).submit(function(event) {	
					
					 // process the form
					i += 1;
					if(i == 1) {						
					  $.ajax({
					    type        : 'POST',
					    url         : 'services/shot.php?function=addShotDept',
					    data        : $(this).serialize(),
						dataType    : 'json',  
					    success     : function(data) {
					    	// if validation is good add success message
							if ( data.success) {
									$shotDet.version = data.version;
									document
									.getElementById($dept+"responseMessage").innerHTML = "<span style=color:'green'>"
									+ data.message + "</span>";
							document
									.getElementById($dept+"responseMessage").style.color = '#008000'; // red
																										// or
																										// #ffffff
							document
									.getElementById($dept+"responseMessage").style.fontWeight = 'bold';
							// window.scrollTo(500, 0);
							}else {

								document
								.getElementById($dept+"responseMessage").innerHTML = "<span style=color:'red'>"
								+ data.message + "</span>";
						document
								.getElementById($dept+"responseMessage").style.color = '#ffffff'; // red
																									// or
																									// #ffffff
						document
								.getElementById($dept+"responseMessage").style.fontWeight = 'bold';
							
							}
					      }
					  });
				}
					  // stop the form from submitting and refreshing
					  event.preventDefault();
					  
				});
			};
			
			$scope.submitShotAllocDetails=function($dept){		
				// alert("inside submitShotAllocDetails");
				var i = 0;
				$('#formaddshotallocdept'+$dept).submit(function(event) {
					// alert("inside submitShotAllocDetails called" + $dept);
					// process the form
					i += 1;
					if(i == 1) {
					  $.ajax({
					    type        : 'POST',
					    url         : 'services/shot.php?function=addShotAlloc',
					    data        : $(this).serialize(),
						 dataType    : 'json',  
					    success     : function(data) {
			     
					      
					        // if validation is good add success message
							if ( data.success) {									
					       			// alert(data.message);
								document
								.getElementById($dept+"responseAllocMessage").innerHTML = "<span style=color:'green'>"
								+ data.message + "</span>";
						document
								.getElementById($dept+"responseAllocMessage").style.color = '#008000'; // red
																									// or
																									// #ffffff
						document
								.getElementById($dept+"responseAllocMessage").style.fontWeight = 'bold';
					       			getShotAllocWorkDetail();								
							}else {
								document
								.getElementById($dept+"responseAllocMessage").innerHTML = "<span style=color:'red'>"
								+ data.message + "</span>";
						document
								.getElementById($dept+"responseAllocMessage").style.color = '#ffffff'; // red
																									// or
																									// #ffffff
						document
								.getElementById($dept+"responseAllocMessage").style.fontWeight = 'bold';
							}
					      }
					  });
					}
					  // stop the form from submitting and refreshing
					  event.preventDefault();
				});			
			};
			
			$scope.submitWorkDetails = function($dept, $worker_name, $shotAllocDetail) {
				// alert("inside submitShotAllocDetails called");
				var i = 0;
				$('#formshotalloc'+$dept+$worker_name+"workdetails").submit(function(event) {
					// alert("inside submitShotAllocDetails called" + $dept);
					// process the form
					i += 1;
					if(i == 1) {
					  $.ajax({
					    type        : 'POST',
					    url         : 'services/shot.php?function=addShotAllocWorkDetail',
					    data        : $(this).serialize(),
						 dataType    : 'json',  
					    success     : function(data) {

					      // log data to the console so we can see
					    //  console.log(data);

					        // if validation is good add success message
							if ( data.success) {
					       			$shotAllocDetail.id = data.shot_work_detail_id;
					       			
					       			document
									.getElementById($dept+$worker_name+"responseMessage").innerHTML = "<span style=color:'green'>"
									+ data.message + "</span>";
							document
									.getElementById($dept+$worker_name+"responseMessage").style.color = '#008000'; // red
																										// or
																										// #ffffff
							document
									.getElementById($dept+$worker_name+"responseMessage").style.fontWeight = 'bold';
									// window.location =
									// '#/shotallocation/'+entity+'&version='+version;
									// window.location.reload(true);
							}else {
								document
								.getElementById($dept+$worker_name+"responseMessage").innerHTML = "<span style=color:'red'>"
								+ data.message + "</span>";
						document
								.getElementById($dept+$worker_name+"responseMessage").style.color = '#ffffff'; // red
																									// or
																									// #ffffff
						document
								.getElementById($dept+$worker_name+"responseMessage").style.fontWeight = 'bold';
							}
					      }
					  });
					}
					  // stop the form from submitting and refreshing
					  event.preventDefault();
				});	
	    	};
		    	
		    	$scope.reloadRoute = function () {
		    		$route.reload();
			};	
		
    	   
		
			
			var userid = $cookieStore.get('userid');
			 var username = $cookieStore.get('username');
		     $scope.username = username;	
			$scope.activeMenu = "shotallocation";
			$scope.shotstatusidd=shotstatusid;
			
			var original = shotallocdet.data;
			var shotstatusoriginal = shotallocdetshotstatusid.data;
			
			
			if(original || shotstatusoriginal )
			{			
			$scope.entitys= angular.copy(original);
			$scope.entityshotstatus= angular.copy(shotstatusoriginal);			
			$scope.entity = (shotstatusid == '2')? $scope.entityshotstatus : $scope.entitys;			 
			}
			else
			{
				$scope.entity= {};
			}			
		$scope.editshotallocid = shotallocID;		
	
		$scope.shotallocationstatusid = [{shotallocationstatusid:'1',shotallocationstatusname: 'completed'}, {shotallocationstatusid:'2',shotallocationstatusname: 'Redo'}]
		
		$scope.shotallocationresourcestatusid=[{shotallocationresourcestatusid:'1',name:'assigned'},{shotallocationresourcestatusid:'2',name:'free'}]	
		
		$scope.rototeamtypeid=[{rototeamtypeid:'1',name:'In House'},{rototeamtypeid:'2',name:'Vendor'},{rototeamtypeid:'3',name:'Freelancers'}]	
		$scope.paintteamtypeid=[{paintteamtypeid:'1',name:'In House'},{paintteamtypeid:'2',name:'Vendor'},{paintteamtypeid:'3',name:'Freelancers'}]	
		$scope.matchmoveteamtypeid=[{matchmoveteamtypeid:'1',name:'In House'},{matchmoveteamtypeid:'2',name:'Vendor'},{matchmoveteamtypeid:'3',name:'Freelancers'}]	
		$scope.compteamtypeid=[{compteamtypeid:'1',name:'In House'},{compteamtypeid:'2',name:'Vendor'},{compteamtypeid:'3',name:'Freelancers'}]	
		$scope.dteamtypeid=[{dteamtypeid:'1',name:'In House'},{dteamtypeid:'2',name:'Vendor'},{dteamtypeid:'3',name:'Freelancers'}]	
	
		$scope.shotallocationdepartmentid=[{shotallocationdepartmentid:'1',name :'Roto'},{shotallocationdepartmentid:'2',name :'Paint'},{shotallocationdepartmentid:'3',name :'Match move'},{shotallocationdepartmentid:'4',name :'Compositing'},{shotallocationdepartmentid:'5',name :'3D'}]	
		
		$scope.complexityid =[{complexityid :'1',name :'easy'},{complexityid :'2',name :'average'},{complexityid :'3',name :'complex'}]
		
		$scope.shotallocationshotstatusid=[{shotallocationshotstatusid:'1',name:'assigned'},{shotallocationshotstatusid:'2',name:'inprogress'},{shotallocationshotstatusid:'3',name:'completed'},{shotallocationshotstatusid:'4',name:'Redo'},{shotallocationshotstatusid:'5',name:'needs fixing(or)feedback received'},{shotallocationshotstatusid:'6',name:'feedback fix & uploaded'},{shotallocationshotstatusid:'7',name:'on hold(Post delivery)'},{shotallocationshotstatusid:'8',name:'on hold(Pre delivery)'},{shotallocationshotstatusid:'9',name:'omitted'},{shotallocationshotstatusid:'10',name:'awaiting updated input'},{shotallocationshotstatusid:'11',name:'allocated and yet to start'},{shotallocationshotstatusid:'12',name:'Allocated and work in progress'},{shotallocationshotstatusid:'13',name:'uploaded and waiting for feedback'},{shotallocationshotstatusid:'14',name:'approved'}]			
			
			
				 			 
			initController();
            function initController() {
            	getUserRolefields(7);
            	getdepartments();
            getShotStatus();
			getshotstatusid();			
			// getfieldsadd();
			getfieldsdept();
			getfieldsallocate();
			getfieldsstatus();
			getfieldsfinal();			
			getAccessDetails();  
			getprojectdetails();		
			
			getentityid();   
			// gettimedetails();
			getuserrole();				
			getShotDet();
			getdept();	
			getLeads();
			}  
          
           
            
            function getLeads()
    		{
    			 UserService.getLeads().then(function (data) {				
    				 $scope.leads = data.data;
    				// alert($scope.leads);
    			 });
    		}
            
           /*
			 * function buttonEnableDisable(id, startTime, pauseTime, status){
			 * if(status == 11) { document.getElementById(id).disabled = true;
			 * id.disabled = true; }else if(status == 11){
			 * document.getElementById(id).disabled = true; id.disabled = true;
			 * }else{ alert("Else Case"); var startId = id+"start"; var pauseId =
			 * id+"pause"; var completeId = id+"complete";
			 * 
			 * document.getElementById(startId).disabled = true;
			 * document.getElementById(pauseId).disabled = false;
			 * document.getElementById(completeId).disabled = false;
			 * //id.disabled = true; } }
			 */
            function buttonEnableDisable1(shotworkId,id, startTime, pauseTime, status){            	
            		alert("Else Case");
            		var startId = id+"start"+shotworkId;
            		var pauseId = id+"pause"+shotworkId;
            		var completeId = id+"complete"+shotworkId;
            		
            		document.getElementById(startId).disabled = true;
            		document.getElementById(pauseId).disabled = false;
            		document.getElementById(completeId).disabled = false;
                	// id.disabled = true;
            	}
            function buttonEnableDisable2(shotworkId,id, startTime, pauseTime, status){            	
        		alert("Else Case");
        		var startId = id+"start"+shotworkId;
        		var pauseId = id+"pause"+shotworkId;
        		var completeId = id+"complete"+shotworkId;
        		
        		document.getElementById(startId).disabled = false;
        		document.getElementById(pauseId).disabled = true;
        		document.getElementById(completeId).disabled = true;
            	// id.disabled = true;
        	}
            function buttonEnableDisable3(shotworkId,id, startTime, pauseTime, status){            	
        		alert("Else Case");
        		var startId = id+"start"+shotworkId;
        		var pauseId = id+"pause"+shotworkId;
        		var completeId = id+"complete"+shotworkId;
        		
        		document.getElementById(startId).disabled = true;
        		document.getElementById(pauseId).disabled = true;
        		document.getElementById(completeId).disabled = true;
            	// id.disabled = true;
        	}
            
            function getShotAllocWorkDetail() {  
            	
            	UserService.getUserRolefields(9).then(function (data) {				
					   	$scope.shotDeptAllocDetails = data.data;		
			            }); 
            	
            	var pathSplit = $location.path().split("/");
                var shotallocID = pathSplit[2];
                if(shotallocID == 0){
                	shotallocID  = document.getElementById('shot_det_id').value;
                }
            	
            	 UserService.getShotAllocWorkDetail(shotallocID,version).then(function (data) {
                     if(data.data) {                     	 
                     	$scope.shotAllocDetails = data.data;                      	
                     	
                     	UserService.getUserRolefields(10).then(function (data) {				
    					   	$scope.shotWorkAllocDetails = data.data;		
    			            });    
                     }
 				});  
            }
            
            function retreiveComments() { 
            	var pathSplit = $location.path().split("/");
                var entityId = pathSplit[2];
                // alert(entityId);
            	 $.ajax({
					    type        : 'POST',
					    url         : 'services/comments.php?function=retreiveComments&entityType=10&entityId=' + entityId, 
					    data        : $(this).serialize(),
						 dataType    : 'json',  
					    success     : function(data) {
				        // Comments/Feedback details will be set in scope for
						// Comments Pop Up
					       			$scope.comments = data;
					       			//alert($scope.comments);							
					      }
					  });
            }
            
            
            function addOrUpdateComment($entityId)
		    {	            
            	var entityId = $entityId;
            	var feedback = document.getElementById('feedback').value;
            	var feedbackpath = document.getElementById('feedbackpath').value;
				$.ajax({
				    type        : 'POST',
				    url         : 'services/comments.php?function=addOrUpdateComment&entityType=10&entityId=' + entityId 
				    				+ '&feedback='+ feedback +'&feedbackpath=' + feedbackpath, 
				    data        : $(this).serialize(),
					 dataType    : 'json',  
				    success     : function(data) {
				    	// Comment updated successfully
				    	if ( data.success) {
			       			alert("Comments updated Successfully");
			      }
				    }
						
				  });
		    } 
            function getShotDet() {            	
         		 /*
					 * var shotallocationid2 = shotallocID; var shstatus2 =
					 * shotstatusid; var version2 = version;
					 */ 
            	var pathSplit = $location.path().split("/");
                var shotallocID = pathSplit[2];
                if(shotallocID == 0){
                	shotallocID  = document.getElementById('shot_det_id').value;
                }
                 UserService.getShotDet(shotallocID,version).then(function (data) {
                     if(data.data) {
                    	 
                     	$scope.shotDetails = data.data; 
                     	
                     	 UserService.getUserRolefields(8).then(function (data) {				
     					   	$scope.shotDeptFields = data.data;		
     			            });    
                     	
                     // alert($scope.shotDetails);
                     	// shotAllocDropDownSelection($scope.shotDetails);
                     }
 				});  
            }
            
           
			function getshotstatusid()
			{
					var shotallocationid=shotallocID;
				UserService.getmaxshotstatusid(shotallocationid).then(function (data) {				
					$scope.shotstatusid = data.data;
					var maxshotstatusid=$scope.shotstatusid
						$scope.versionedit = (maxshotstatusid == '2' && version =='1') ? '0' : '1';	
			
			$scope.versioneditalert=$scope.versionedit;
			 });
				
			}
			function getuserrole()
			{
			 UserService.getuserrole().then(function (data) {				
					$scope.userroleid = data.data[0];
				//	alert("user role"+$scope.userroleid);
			 });
			}	
			
			function getUserRolefields(roleId){   	
				   UserService.getUserRolefields(roleId).then(function (data) {				
					   	$scope.fieldsadd = data.data;
					   
			            });           
			   }
			function getdepartments()
            {
            	UserService.getdepartments().then(function (data){
            		$scope.departments=data.data;
            	});
            }
			/*
			 * function getfieldsadd() { var shotdetails="shotdetails";
			 * UserService.getFields(shotdetails).then(function (data) {
			 * 
			 * }); }
			 */
			function getfieldsdept()
		    {
		    	var deptdetails="deptdetails";
            UserService.getFields(deptdetails).then(function (data) {
		    $scope.fieldsdept = data.data;			
            	});
         	}
         	function getdept()
                {
                    var pathSplit = $location.path().split("/");
                    var entityId = pathSplit[2];
                    UserService.getDept(entityId).then(function (data) {
                        
                        $scope.depts = data.data;
                    });
                }
         	
         	
			function getfieldsallocate()
		    {
		    var shotallc="projectallocation";
            UserService.getFields(shotallc).then(function (data) {				
		    $scope.fieldsallocate = data.data;			
            	});
         	}
			function getprojectdetails()
		    {				
		    UserService.getProjectDetails().then(function (data) {				
		    $scope.projectDetails = data.data;	
		    // alert($scope.projectDetails);
            });
         } 	
			
			
			function startShotWork(shotworkId){	
				var shot_dept_artist_details_id = document.getElementById("shot_work_detail_id").value;	
				
				$.ajax({
				    type        : 'POST',
				    url         : 'services/shot.php?function=startShotWork&shot_dept_artist_details_id=' + shotworkId, 
				    data        : $(this).serialize(),
					 dataType    : 'json',  
				    success     : function(data) {
				    	// Comment updated successfully
				    	if ( data.success) {
				    		$scope.shotDashboardDetail.actual_start_date = data.data.actual_start_date;
			       			alert(" Task Progress Time Started");
			      }
				    }
						
				  });
				/*
				 * UserService.startShotWork(shot_dept_artist_details_id).then(function
				 * (data) { //alert(data.data.message);
				 * $scope.shotDashboardDetail.actual_start_date =
				 * data.data.actual_start_date; });
				 */
			}
			function pauseShotWork(shotworkId){
				var shot_dept_artist_details_id = document.getElementById("shot_work_detail_id").value;
				
				$.ajax({
				    type        : 'POST',
				    url         : 'services/shot.php?function=pauseorCompleteShotWork&shot_dept_artist_details_id=' + shotworkId
				    + '&operationFlag=pause', 
				    data        : $(this).serialize(),
					 dataType    : 'json',  
				    success     : function(data) {
				    	// Comment updated successfully
				    	if ( data.success) {				    		
			       			alert(" Task Progress Paused");
			      }
				    }
						
				  });
				
				/*
				 * // alert("scope.shotDashboardDetail.worked_hours" + //
				 * $scope.shotDashboardDetail.worked_hours);
				 * UserService.pauseShotWork(shot_dept_artist_details_id,
				 * $scope.shotDashboardDetail.worked_hours).then(function (data) { //
				 * alert(data.data.message); });
				 */	
			}
			function completeShotWork(shotworkId){
				var shot_dept_artist_details_id = document.getElementById("shot_work_detail_id").value;
				
				$.ajax({
				    type        : 'POST',
				    url         : 'services/shot.php?function=pauseorCompleteShotWork&shot_dept_artist_details_id=' + shotworkId
				    + '&operationFlag=complete', 
				    data        : $(this).serialize(),
					 dataType    : 'json',  
				    success     : function(data) {
				    	// Comment updated successfully
				    	if ( data.success) {		
				    		$scope.shotAllocDetail.actualenddate = data.data.actual_completed_date;
			       			alert(" Task Completed");
			      }
				    }
						
				  });
				// alert("scope.shotDashboardDetail.worked_hours" +
				// $scope.shotDashboardDetail.worked_hours);
				/*
				 * UserService.completeShotWork(shot_dept_artist_details_id,
				 * $scope.shotDashboardDetail.worked_hours).then(function (data) {
				 * //alert(data.data.message);
				 * $scope.shotDashboardDetail.actual_start_date =
				 * data.data.actual_completed_date; });
				 */			
			}
			
			
			
			function getfieldsstatus()
		    {
		    var shotstatus="shotstatus";
            UserService.getFields(shotstatus).then(function (data) {				
		    $scope.fieldstatus = data.data;			
            	});
         	} 
			function getfieldsfinal()
		    {
		    var shotfinal="shotfinal";
            UserService.getFields(shotfinal).then(function (data) {				
		    $scope.final = data.data;			
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
			var name="shotallocation";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 	
		
                
       
		
		function getShotStatus()
		{
		 UserService.getShotStatus().then(function (data) {				
				$scope.statusList = data.data;				
		 });
		}
		
		
	}; 
})();