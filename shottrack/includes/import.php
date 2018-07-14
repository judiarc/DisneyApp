<?php
include('database.php');
if(isset($_POST["Import"])){
		

		echo $filename=$_FILES["file"]["tmp_name"];
		

		 if($_FILES["file"]["size"] > 0)
		 {

		  	$file = fopen($filename, "r"); //read file
			fgets($file);  //sip firstline
	         while (($projData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
	    			$username=$_COOKIE["username"];	
			        $date=date("Y-m-d H:i:s"); 
	          //It wiil insert a row to our shot table from our csv file`
			  
				$entitydet = "INSERT INTO entity_det_table(name,entity_id,is_deleted,created_by)VALUES('$projData[3]','12','0','$username')";
				$r =mysqli_query($conn,$entitydet);				
				$entity_det_id = mysqli_insert_id($conn);
				//It wiil insert a row to our shot table from our csv file`of shot details
	          $querydet ="INSERT INTO shot_details(entity_id,row_id,shotallocationprojectname,shotallocationprojectdetailsid,sequence,task,comments,path,shotcode,receiveddate,shotallocationdepartmentid,version,created_by)
VALUES ('$entity_det_id','0','$projData[0]','$projData[1]','$projData[2]','$projData[6]','$projData[7]','$projData[8]','$projData[3]','$projData[4]','$projData[5]','1','$username') ";
	          $result = mysqli_query( $conn, $querydet );
			  //It wiil insert a row to our shot table from our csv file of dept details
			  $deptdetails = "INSERT INTO shot_details(entity_id,row_id,shotallocationdepartmentid,targetdate,internaltargetdate, framesgiven,complexityid,workrange,clientmandays,internalmandays,outsourcemandays,priceclient,priceoutsource,shotallocationrotoartistid,shotallocationrotovendorid,shotallocationrotofreelancerid,shotallocationshotstatusid,version,created_by)
VALUES ('$entity_det_id','$projData[11]','$projData[11]','$projData[12]','$projData[13]','$projData[14]','$projData[15]','$projData[16]','$projData[17]','$projData[18]','$projData[19]','$projData[20]','$projData[21]','$projData[22]','$projData[23]','$projData[24]','$projData[25]','1','$username'); ";
 $resultdet = mysqli_query( $conn, $deptdetails );
 
 //It wiil insert a row to our shot table from our csv file of dept details
			  $deptdetailstime = "INSERT INTO shot_details(entity_id,multiid, 	shotallocationdepartmentid,shotallocationrotoartistid,workstartdateroto,targettimeroto,completedtimeroto,version,is_deleted,created_by)VALUES('$entity_det_id','1','1','$projData[22]','$projData[26]','$projData[27]','$projData[28]','1','0','$username')";
 $resultdettime = mysqli_query( $conn, $deptdetailstime );
 
 
   $deptdetailstimev = "INSERT INTO shot_details(entity_id,multiid, 	shotallocationdepartmentid,shotallocationrotovendorid,workstartdateroto,targettimeroto,completedtimeroto,version,is_deleted,created_by)VALUES('$entity_det_id','1','1','$projData[22]','$projData[29]','$projData[30]','$projData[31]','1','0','$username')";
 $resultdettimev = mysqli_query( $conn, $deptdetailstimev );
 
 
   $deptdetailstimef = "INSERT INTO shot_details(entity_id,multiid, 	shotallocationdepartmentid,shotallocationrotofreelancerid,workstartdateroto,targettimeroto,completedtimeroto,version,is_deleted,created_by)VALUES('$entity_det_id','1','1','$projData[22]','$projData[32]','$projData[33]','$projData[34]','1','0','$username')";
 $resultdettimef = mysqli_query( $conn, $deptdetailstimef );
				if(! $resultdettimef )
				{
					echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
							window.location = '#/shotallocation/12';
						</script>";
				
				}

	         }
	         fclose($file);
	         //throws a message if data successfully imported to mysql database from excel file
	         echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location = '#/shotallocation/12';
					</script>";
	        
			 

			
		 	
			
		 }
	}	 
?>		 