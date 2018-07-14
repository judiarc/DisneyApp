<?php
		include('database.php');	  
        $idArr = $_POST['id'];		
        foreach($idArr as $id){			
           
			mysqli_query($conn,"UPDATE shot_details SET approved='1' WHERE entity_id = $id");
		}
			echo "approved successfully";
        
        	
?>