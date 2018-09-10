<?php
    include 'session.php';	
		
	$tb='';
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'getData' :
				try{	
					$sql = "SELECT jd.`Id`, jd.`GroupId`, jd.`Qty`, jd.`Remark`, jd.`CreateTime`,DATE_FORMAT(jd.QueueTime,'%H:%i') QueueTime, jd.`CreateUserId` 
					, jg.Name as GroupName, cu.userFullname as CreateUserName 
					FROM `jit_data` jd
					INNER JOIN `jit_group` jg ON jg.Code=jd.GroupId 
					INNER JOIN `".$dtPrefix."user` cu ON cu.userId=jd.CreateUserId
					WHERE jd.CheckInTime IS NULL 
					ORDER BY jd.Id 
					LIMIT 10";
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					
					$rowCount=$stmt->rowCount();
					$jsonData = array();
					while($row=$stmt->fetch()){						
							$jsonData[] = $row;
					}

					$sql = "SELECT SUM(qtyMax) as totalQtyMax
					,(SELECT SUM(qty) FROM jit_data) AS totalRegister
					,(SELECT SUM(qty) FROM jit_data WHERE CheckInTime IS NOT NULL) AS totalCheckIn
					FROM `jit_group` WHERE 1";
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					
					$rowCount2=$stmt->rowCount();
					$jsonData2 = array();
					$jsonData2 =$stmt->fetch();	
					
					header('Content-Type: application/json');				
					echo json_encode( array('success' => true, 'rowCount' => $rowCount, 'data' => json_encode($jsonData), 'rowCount2' => $rowCount2, 'data2' => json_encode($jsonData2) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => false, 'message' => $errors));
				} 
				break;			
			default : 
				header('Content-Type: application/json');
				echo json_encode(array('success' => false, 'message' => 'Unknow action.'));				
		}
	}