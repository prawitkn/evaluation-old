<?php
    include 'session.php';	
		
	$rootPage = 'person';
	$tb = 'eval_person';
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'save' :				
				try{
				$id = $_POST['id'];
				$code = $_POST['code'];
				$sectionId = $_POST['sectionId'];
				$positionId = $_POST['positionId'];
				$fullName = $_POST['fullName'];

				$sectionId=($sectionId<>""?$sectionId:'0');
				$positionId=($positionId<>""?$positionId:'0');			
				
				if ( $id == "" ){
					//Insert				
					// Check duplication?
					$sql = "SELECT id FROM `".$tb."` WHERE (code=:code
					 OR fullName=:fullName) ";
					$stmt = $pdo->prepare($sql);	 
					$stmt->bindParam(':code', $code);
					$stmt->bindParam(':fullName', $fullName);
					$stmt->execute();
					if ($stmt->rowCount() >= 1){
					  header('Content-Type: application/json');
					  $errors = "ผิดพลาด : ข้อมูลซ้ำ";
					  echo json_encode(array('status' => 'danger', 'message' => $errors));  
					  exit;    
					}   
		
					$sql = "INSERT INTO `".$tb."` (`code`, `fullName`, `sectionId`, `positionId`, `statusId`, `createTime`, `createUserId`)
					 VALUES (:code, :fullName, :sectionId, :positionId, 1,NOW(),:createUserId) ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':code', $code);
					$stmt->bindParam(':fullName', $fullName);
					$stmt->bindParam(':sectionId', $sectionId);
					$stmt->bindParam(':positionId', $positionId);
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
					$sectionId = $_POST['sectionId'];
					$positionId = $_POST['positionId'];
					$fullName = $_POST['fullName'];
					$statusId = $_POST['statusId'];

					$sectionId=($sectionId<>""?$sectionId:'0');
					$positionId=($positionId<>""?$positionId:'0');			
					
					// Check user name duplication?
					$sql = "SELECT id FROM `".$tb."`WHERE (code=:code
					 AND fullName=:fullName)
					 AND id<>:id ";
					$stmt = $pdo->prepare($sql);	 
					$stmt->bindParam(':code', $code);
					$stmt->bindParam(':fullName', $fullName);
					$stmt->bindParam(':id', $id);
					$stmt->execute();
					if ($stmt->rowCount() >= 1){
					  header('Content-Type: application/json');
					  $errors = "Error on Data Insertion. Duplicate data.";
					  echo json_encode(array('status' => 'warning', 'message' => $errors));  
					  exit;    
					} 	   
					
					//Sql
					$sql = "UPDATE `".$tb."` SET  `code`=:code 
					, `sectionId`=:sectionId 
					, `positionId`=:positionId
					, `fullName`=:fullName
					, `statusId`=:statusId
					, `updateTime`=NOW()
					, `updateUserId`=:updateUserId
					WHERE id=:id 
					";	
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':code', $code);
					$stmt->bindParam(':sectionId', $sectionId);
					$stmt->bindParam(':positionId', $positionId);
					$stmt->bindParam(':fullName', $fullName);
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