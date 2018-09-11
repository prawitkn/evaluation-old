<?php
    include 'session.php';	
		
$rootPage = 'topic_type_list';
$tb = 'eval_topic_group';
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'add' :				
				$code = $_POST['code'];
				$name = $_POST['name'];
								
				// Check duplication?
				$sql = "SELECT id FROM `".$tb."` WHERE code=:code OR name=:name ";
				$stmt = $pdo->prepare($sql);	
				$stmt->bindParam(':code', $code);
				$stmt->bindParam(':name', $name);
				$stmt->execute();
				if ($stmt->rowCount() >= 1){
				  header('Content-Type: application/json');
				  $errors = "Error on Data Insertion. Duplicate data, Please try new username. " . $pdo->errorInfo()[2];
				  echo json_encode(array('success' => false, 'message' => $errors));  
				  exit;    
				}   
	
				$sql = "INSERT INTO `".$tb."` (`code`, `name`, `statusId`, `createTime`, `createById`)
				 VALUES (:code,:name,1,NOW(),:s_userId)";
				$stmt = $pdo->prepare($sql);	
				$stmt->bindParam(':code', $code);
				$stmt->bindParam(':name', $name);
				$stmt->bindParam(':s_userId', $s_userId);
				if ($stmt->execute()) {
					header('Content-Type: application/json');
					echo json_encode(array('success' => true, 'message' => 'Data Inserted Complete.'));
				} else {
					header('Content-Type: application/json');
					$errors = "Error on Data Insertion. Please try new username. " . mysqli_error($link);
					echo json_encode(array('success' => false, 'message' => $errors));
				}				
				break;
				exit();
			case 'edit' :
				$id = $_POST['id'];
				$code = $_POST['code'];
				$name = $_POST['name'];
				$statusId = $_POST['statusId'];
				
				// Check user name duplication?
				$sql = "SELECT id FROM `".$tb."` WHERE (code=:code OR name=:name) AND id<>:id ";
				$stmt = $pdo->prepare($sql);	
				$stmt->bindParam(':code', $code);
				$stmt->bindParam(':name', $name);
				$stmt->bindParam(':id', $id);
				$stmt->execute();
				if ($stmt->rowCount() >= 1){
				  header('Content-Type: application/json');
				  $errors = "Error on Data Insertion. Duplicate data, Please try new username. " . $pdo->errorInfo()[2];
				  echo json_encode(array('success' => false, 'message' => $errors));  
				  exit;    
				} 	   
				
				//Sql
				$sql = "UPDATE `".$tb."` SET `code`=:code 
				, `name`=:name
				, `statusId`=:statusId
				WHERE id=:id 
				";	
				$stmt = $pdo->prepare($sql);	
				$stmt->bindParam(':code', $code);
				$stmt->bindParam(':name', $name);
				$stmt->bindParam(':statusId', $statusId);
				$stmt->bindParam(':id', $id);
				if ($stmt->execute()) {
					  header('Content-Type: application/json');
					  echo json_encode(array('success' => true, 'message' => 'Data Updated Complete.'));
				   } else {
					  header('Content-Type: application/json');
					  $errors = "Error on Data Update. Please try new data. " . $pdo->errorInfo();
					  echo json_encode(array('success' => false, 'message' => $errors));
				}	
				break;
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