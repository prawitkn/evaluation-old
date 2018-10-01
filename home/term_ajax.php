<?php
    include 'session.php';	

	$rootPage = 'term';
	$tb = 'eval_term';
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'save' :				
				try{
				$id = $_POST['id'];
				$year = $_POST['year'];
				$term = $_POST['term'];
				
				if ( $id == "" ){
					//Insert				
					// Check duplication?
					$sql = "SELECT id FROM `".$tb."` WHERE (year=:year AND term=:term) ";
					$stmt = $pdo->prepare($sql);	 
					$stmt->bindParam(':year', $year);
					$stmt->bindParam(':term', $term);
					$stmt->execute();
					if ($stmt->rowCount() >= 1){
					  header('Content-Type: application/json');
					  $errors = "ผิดพลาด : ข้อมูลซ้ำ";
					  echo json_encode(array('status' => 'danger', 'message' => $errors));  
					  exit;    
					}   
		
					$sql = "INSERT INTO `".$tb."` (`year`, `term`, `statusId`, `createTime`, `createUserId`)
					 VALUES (:year, :term,1,NOW(),:createUserId) ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':year', $year);
					$stmt->bindParam(':term', $term);
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
					$year = $_POST['year'];
					$term = $_POST['term'];
					$statusId = $_POST['statusId'];
					
					// Check user name duplication?
					$sql = "SELECT id FROM `".$tb."` WHERE (year=:year AND term=:term) AND id<>:id ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':year', $year);
					$stmt->bindParam(':term', $term);
					$stmt->bindParam(':id', $id);
					$stmt->execute();
					if ($stmt->rowCount() >= 1){
					  header('Content-Type: application/json');
					  $errors = "Error on Data Insertion. Duplicate data.";
					  echo json_encode(array('status' => 'warning', 'message' => $errors));  
					  exit;    
					} 	   
					
					//Sql
					$sql = "UPDATE `".$tb."` SET `year`=:year 
					, `term`=:term
					, `statusId`=:statusId
					, `updateTime`=NOW()
					, `updateUserId`=:updateUserId
					WHERE id=:id 
					";	
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':year', $year);
					$stmt->bindParam(':term', $term);
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
			case 'setCurrent' :
				try{
					$id = $_POST['id'];
					
					//Set All is not Current
					$sql = "UPDATE ".$tb." SET isCurrent=0
					,updateTime=NOW()
					,updateUserId=:updateUserId
					WHERE isCurrent=1 ";
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':updateUserId', $s_userId);
					$stmt->execute();	

					//Set selected to current.
					$sql = "UPDATE ".$tb." SET isCurrent=1
					,updateTime=NOW()
					,updateUserId=:updateUserId
					WHERE id=:id ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':updateUserId', $s_userId);
					$stmt->bindParam(':id', $id);
					$stmt->execute();	

					header('Content-Type: application/json');
				  	echo json_encode(array('success' => true, 'message' => 'Data Updated Complete.'));
				}catch(Exception $e){
					//Rollback the transaction.
					$pdo->rollBack();
					//return JSON
					header('Content-Type: application/json');
					$errors = "Error : " . $e->getMessage();
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
			case 'createData' :
				//$id = $_POST['id'];
				
				$sql = "
				INSERT INTO `eval_term_person`(`termId`, `personId`)
				SELECT t.id, p.id 
				FROM eval_term t 
				LEFT JOIN eval_person p ON p.statusId=1 
				WHERE t.isCurrent=1 
				AND NOT EXISTS (SELECT * FROM eval_term_person x
				                WHERE x.termId=t.id AND x.personId=p.id) 
				";
				$stmt = $pdo->prepare($sql);	
				$stmt->execute();	

				$sql = "
				INSERT INTO eval_data (`termPersonId`, `topicGroupId`, `topicGroupName`, `topicGroupRatio`, `seqNo`, `topicId`, `topicName`, `topicDesc`)
				SELECT tp.`id`, tg.id, tg.name, tg.ratio
				,t.seqNo, t.id, t.name, t.nameDesc
				FROM `eval_term_person` tp 
				LEFT JOIN eval_topic_group tg ON 1=1 
				INNER JOIN eval_topic t ON t.topicGroupId=tg.id AND t.positionGroupId=1 AND t.statusId=1 
				WHERE tg.id IN (2,3,4)
				AND NOT EXISTS (SELECT * FROM eval_data x WHERE x.termPersonId=tp.id)
				";
				$stmt = $pdo->prepare($sql);	
				$stmt->execute();	
				
				header('Content-Type: application/json');
				  echo json_encode(array('success' => true, 'message' => 'Data Updated Complete.'));
				break;

			default : 
				header('Content-Type: application/json');
				echo json_encode(array('success' => false, 'message' => 'Unknow action.'));				
		}
	}