<?php
    include 'session.php';	
		
	$rootPage = 'topic';
	$tb = 'eval_topic';
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'save' :				
				try{
				$id = $_POST['id'];
				$topicGroupId = $_POST['topicGroupId'];
				$positionGroupId = $_POST['positionGroupId'];
				$positionId = $_POST['positionId'];
				$name = $_POST['name'];
				$nameDesc = $_POST['nameDesc'];

				$positionGroupId=($positionGroupId<>""?$positionGroupId:'0');
				$positionId=($positionId<>""?$positionId:'0');			
				
				if ( $id == "" ){
					//Insert				
					// Check duplication?
					$sql = "SELECT id FROM `".$tb."` WHERE (topicGroupId=:topicGroupId
					 AND positionGroupId=:positionGroupId
					 AND positionId=:positionId
					 AND name=:name) ";
					$stmt = $pdo->prepare($sql);	 
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->bindParam(':positionGroupId', $positionGroupId);
					$stmt->bindParam(':positionId', $positionId);
					$stmt->bindParam(':name', $name);
					$stmt->execute();
					if ($stmt->rowCount() >= 1){
					  header('Content-Type: application/json');
					  $errors = "ผิดพลาด : ข้อมูลซ้ำ";
					  echo json_encode(array('status' => 'danger', 'message' => $errors));  
					  exit;    
					}   
		
					$sql = "INSERT INTO `".$tb."` (`topicGroupId`, `positionGroupId`, `positionId`, `name`, `nameDesc`, `statusId`, `createTime`, `createUserId`)
					 VALUES (:topicGroupId, :positionGroupId, :positionId, :name, :nameDesc,1,NOW(),:createUserId) ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->bindParam(':positionGroupId', $positionGroupId);
					$stmt->bindParam(':positionId', $positionId);
					$stmt->bindParam(':name', $name);
					$stmt->bindParam(':nameDesc', $nameDesc);
					$stmt->bindParam(':createUserId', $s_userId);
					if ($stmt->execute()) {
						header('Content-Type: application/json');
						echo json_encode(array('status' => 'success', 'message' => 'Data Inserted Complete.'));
					} else {
						header('Content-Type: application/json');
						$errors = "ผิดพลาด : ".$pdo->errorInfo();
						echo json_encode(array('status' => 'danger', 'message' => $errors));
					}
				}else{
					//Update
					$id = $_POST['id'];
					$topicGroupId = $_POST['topicGroupId'];
					$positionGroupId = $_POST['positionGroupId'];
					$positionId = $_POST['positionId'];
					$name = $_POST['name'];
					$nameDesc = $_POST['nameDesc'];
					$statusId = $_POST['statusId'];

					$positionGroupId=($positionGroupId<>""?$positionGroupId:'0');
					$positionId=($positionId<>""?$positionId:'0');		
					
					// Check user name duplication?
					$sql = "SELECT id FROM `".$tb."` WHERE (topicGroupId=:topicGroupId
					 AND positionGroupId=:positionGroupId
					 AND positionId=:positionId
					 AND name=:name) 
					 AND id<>:id ";
					$stmt = $pdo->prepare($sql);	 
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->bindParam(':positionGroupId', $positionGroupId);
					$stmt->bindParam(':positionId', $positionId);
					$stmt->bindParam(':name', $name);
					$stmt->bindParam(':id', $id);
					$stmt->execute();
					if ($stmt->rowCount() >= 1){
					  header('Content-Type: application/json');
					  $errors = "Error on Data Insertion. Duplicate data.";
					  echo json_encode(array('status' => 'warning', 'message' => $errors));  
					  exit;    
					} 	   
					
					//Sql
					$sql = "UPDATE `".$tb."` SET `topicGroupId`=:topicGroupId 
					, `positionGroupId`=:positionGroupId
					, `positionId`=:positionId
					, `name`=:name
					, `nameDesc`=:nameDesc
					, `statusId`=:statusId
					, `updateTime`=NOW()
					, `updateUserId`=:updateUserId
					WHERE id=:id 
					";	
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->bindParam(':positionGroupId', $positionGroupId);
					$stmt->bindParam(':positionId', $positionId);
					$stmt->bindParam(':name', $name);
					$stmt->bindParam(':nameDesc', $nameDesc);
					$stmt->bindParam(':statusId', $statusId);
					$stmt->bindParam(':updateUserId', $s_userId);
					$stmt->bindParam(':id', $id);
					if ($stmt->execute()) {
						  header('Content-Type: application/json');
						  echo json_encode(array('status' => 'success', 'message' => 'Data Updated Complete.'));
					   } else {
						  header('Content-Type: application/json');
						  $errors = "Error on Data Update. Please try new data. " . $pdo->errorInfo();
						  echo json_encode(array('status' => 'danger', 'message' => $errors));
					}	
				}
				//.if $is
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} // catch			
				break;
				exit();
			case 'setActive' :
				$id = $_POST['id'];
				$statusId = $_POST['statusId'];	
				
				$sql = "UPDATE ".$tb." SET statusId=:statusId WHERE id=:id ";
				$stmt = $pdo->prepare($sql);	
				$stmt->bindParam(':statusId', $statusId);
				$stmt->bindParam(':id', $id);
				$stmt->execute();	
				if ($stmt->execute()) {
				  header('Content-Type: application/json');
				  echo json_encode(array('success' => true, 'message' => 'Data Updated Complete.'));
				} else {
				  header('Content-Type: application/json');
				  $errors = "Error on Data Update. Please try new data. " . $pdo->errorInfo();
				  echo json_encode(array('success' => false, 'message' => $errors));
				}	
				break;
			case 'remove' :
				$id = $_POST['id'];
				
				$sql = "UPDATE ".$tb." SET statusId=2 WHERE id=:id ";
				$stmt = $pdo->prepare($sql);	
				$stmt->bindParam(':id', $id);
				$stmt->execute();	
				if ($stmt->execute()) {
				  header('Content-Type: application/json');
				  echo json_encode(array('success' => true, 'message' => 'Data Updated Complete.'));
				} else {
				  header('Content-Type: application/json');
				  $errors = "Error on Data Update. Please try new data. " . $pdo->errorInfo();
				  echo json_encode(array('success' => false, 'message' => $errors));
				}	
				break;
			case 'delete' :
				$id = $_POST['id'];
				
				$sql = "DELETE FROM ".$tb." WHERE id=:id ";
				$stmt = $pdo->prepare($sql);	
				$stmt->bindParam(':id', $id);
				$stmt->execute();	
				if ($stmt->execute()) {
				  header('Content-Type: application/json');
				  echo json_encode(array('success' => true, 'message' => 'Data Updated Complete.'));
				} else {
				  header('Content-Type: application/json');
				  $errors = "Error on Data Update. Please try new data. " . $pdo->errorInfo();
				  echo json_encode(array('success' => false, 'message' => $errors));
				}	
				break;
			case 'tableSubmit' :
				try{	
					$pdo->beginTransaction();
					
					foreach($_POST['id'] as $index => $item )
					{	
						$sql = "UPDATE ".$tb." set seqNo=:seqNo WHERE id=:id 
						";						
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':seqNo', $_POST['seqNo'][$index]);
						$stmt->bindParam(':id', $item);		
						$stmt->execute();			
					}
					
					$pdo->commit();
					
					header('Content-Type: application/json');
					echo json_encode(array('success' => true, 'message' => 'Data Updated Completed.'));
				}catch(Exception $e){
					//Rollback the transaction.
					$pdo->rollBack();
					//return JSON
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