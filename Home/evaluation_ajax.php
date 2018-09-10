<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'getHeader' :
				try{
					$termPersonId=$_POST['termPersonId'];

					$sql = "SELECT CONCAT(t.term,'/',t.year) as TermName, p.Fullname as PersonFullName 
					FROM eval_term_person tp
					INNER JOIN eval_term t ON t.Id=tp.TermId 
					INNER JOIN eval_person p ON p.Id=tp.PersonId 
					WHERE 1=1
					AND tp.Id=:Id ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':Id', $termPersonId);
					$stmt->execute();	

					$jsonData = array();
					$jsonData =$stmt->fetch();	

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getListTotal' :
				try{
					$termPersonId=$_POST['termPersonId'];

					$sql = "SELECT dt.Id 
					FROM eval_term_person tp
					INNER JOIN eval_data dt ON tp.Id=dt.termPersonId 
					WHERE 1=1
					AND tp.Id=:Id ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':Id', $termPersonId);
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getList' :
				try{
					$termPersonId=$_POST['termPersonId'];

					$sql = "SELECT dt.`Id`, dt.`termPersonId`, dt.`evalTypeId`, dt.`evalTypeName`, dt.`topicGroupId`, dt.`topicGroupName`, dt.SeqNo, dt.`topicId`, dt.`topicName`
					FROM eval_term_person tp
					INNER JOIN eval_data dt ON tp.Id=dt.termPersonId 
					WHERE 1=1
					AND tp.Id=:Id ";
					$sql .= "ORDER BY dt.TopicGroupId ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':Id', $termPersonId);
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					$jsonData = array();
					while ( $row=$stmt->fetch() ) {						
							$jsonData[] = $row;
					}

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getItemByStandardListTotal' :
				try{
					$positionGroupId=$_POST['positionGroupId'];
					$topicGroupId=$_POST['topicGroupId'];

					$sql = "SELECT t.`Id`, t.`PositionGroupId`, t.`TopicGroupId`, t.`SeqNo`, t.`Name`
					FROM eval_topic t
					INNER JOIN eval_topic_group tg ON tg.Id=t.TopicGroupId 
					WHERE 1=1
					AND t.PositionGroupId=:PositionGroupId
					AND t.TopicGroupId=:TopicGroupId ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':PositionGroupId', $positionGroupId);
					$stmt->bindParam(':TopicGroupId', $topicGroupId);
					$stmt->execute();	
					$rowCount=$stmt->rowCount();

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getItemByStandardList' :
				try{
					$positionGroupId=$_POST['positionGroupId'];
					$topicGroupId=$_POST['topicGroupId'];

					$sql = "SELECT t.`Id`, t.`PositionGroupId`, t.`TopicGroupId`, t.`SeqNo`, t.`Name`
					, tg.Name as TopicGroupName 
					FROM eval_topic t
					INNER JOIN eval_topic_group tg ON tg.Id=t.TopicGroupId 
					WHERE 1=1
					AND t.PositionGroupId=:PositionGroupId
					AND t.TopicGroupId=:TopicGroupId ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':PositionGroupId', $positionGroupId);
					$stmt->bindParam(':TopicGroupId', $topicGroupId);
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					$jsonData = array();
					while ( $row=$stmt->fetch() ) {						
							$jsonData[] = $row;
					}

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'itemSubmit' :		
				try{	
					$TermPersonId = $_POST['termPersonId'];
					
					$pdo->beginTransaction();	
					
					if(!empty($_POST['itmId']) and isset($_POST['itmId']))
					{						
						//$arrProdItems=explode(',', $prodItems);
						$SeqNo=1;
						foreach($_POST['itmId'] as $index => $Id )
						{	
							$sql = "INSERT INTO `eval_data` (`TermPersonId`, `TopicGroupId`, `TopicGroupName`, `SeqNo`, `TopicId`, `TopicName`) 
							SELECT :TermPersonId, tg.Id, tg.Name, :SeqNo, t.Id, t.Name 
							FROM eval_topic t 
							INNER JOIN eval_topic_group tg ON tg.Id=t.TopicGroupId 
							WHERE t.Id=:Id 
							";			
							$stmt = $pdo->prepare($sql);	
							$stmt->bindParam(':TermPersonId', $TermPersonId);
							$stmt->bindParam(':SeqNo', $SeqNo);			
							$stmt->bindParam(':Id', $Id);
							$stmt->execute();	
							$SeqNo+=1;		
						}
					}
					$pdo->commit();
					
					header('Content-Type: application/json');
					echo json_encode(array('success' => true, 'message' => 'Data Inserted Complete.', 'sdNo' => $sdNo));
				} 
				//Our catch block will handle any exceptions that are thrown.
				catch(Exception $e){
					//Rollback the transaction.
					$pdo->rollBack();
					//return JSON
					header('Content-Type: application/json');
					$errors = "Error on Data Update. Please try again. " . $e->getMessage();
					echo json_encode(array('success' => false, 'message' => $errors));
				}
				
				break;		

			case 'itmDelete' :
				try{					
					$Id = $_POST['Id'];
					
					
					$sql = "DELETE FROM eval_data WHERE Id=:Id ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':Id', $Id);
					$stmt->execute();	
					if ($stmt->execute()) {
					  header('Content-Type: application/json');
					  echo json_encode(array('status' => 'success', 'message' => 'Data Delete Complete.'));
					} else {
					  header('Content-Type: application/json');
					  $errors = "Error on Data Delete. " . $pdo->errorInfo();
					  echo json_encode(array('status' => 'danger', 'message' => $errors));
					}
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				}
				break;

			case 'getEvalPersonListTotal' :
				try{
					$PersonId=$_POST['PersonId'];

					$sql = "SELECT dt.Id 
					FROM eval_person_topic tp
					INNER JOIN eval_topic_group tg ON tg.Id=tp.TopicGroupId 
					WHERE 1=1
					AND tp.PersonId=:PersonId ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':PersonId', $PersonId);
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getEvalPersonList' :
				try{
					$PersonId=$_POST['PersonId'];

					$sql = "SELECT tp.`Id`, tp.`PersonId`, tp.`TopicGroupId`, tp.`SeqNo`, tp.`Name`
					, tg.Name as TopicGroupName 
					FROM eval_person_topic tp
					INNER JOIN eval_topic_group tg ON tg.Id=tp.TopicGroupId 
					WHERE 1=1
					AND tp.PersonId=:PersonId ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':PersonId', $PersonId);
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					$jsonData = array();
					while ( $row=$stmt->fetch() ) {						
							$jsonData[] = $row;
					}

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'itmTopicAdd' :
				try{					

					$pId = $_POST['pId'];
					$tgId = $_POST['tgId'];
					$Name = $_POST['Name'];
					
					$sql = "SELECT COUNT(*) as countTotal FROM eval_person_topic WHERE PersonId=:PersonId ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':PersonId', $pId);
					$stmt->execute();
					$row=$stmt->fetch();

					$countTotal=$row['countTotal'];
					$countTotal+=1;
					
					$sql = "INSERT INTO eval_person_topic (`PersonId`, `TopicGroupId`, `SeqNo`, `Name`) VALUES (:PersonId, :TopicGroupId, :SeqNo, :Name) ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':PersonId', $pId);
					$stmt->bindParam(':TopicGroupId', $tgId);
					$stmt->bindParam(':SeqNo', $countTotal);
					$stmt->bindParam(':Name', $Name);

					if ($stmt->execute()) {
					  header('Content-Type: application/json');
					  echo json_encode(array('status' => 'success', 'message' => 'Data Insert Complete.'));
					} else {
					  header('Content-Type: application/json');
					  $errors = "Error on Data Delete. " . $pdo->errorInfo();
					  echo json_encode(array('status' => 'danger', 'message' => $errors));
					}
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				}
				break;

			case 'itemTopicPersonSubmit' :		
				try{	
					$termPersonId = $_POST['termPersonId'];
					
					$pdo->beginTransaction();	
					
					if(!empty($_POST['itmId']) and isset($_POST['itmId']))
					{						
						//$arrProdItems=explode(',', $prodItems);
						$SeqNo=1;
						foreach($_POST['itmId'] as $index => $Id )
						{	
							$sql = "INSERT INTO `eval_data` (`TermPersonId`, `TopicGroupId`, `TopicGroupName`, `SeqNo`, `TopicId`, `TopicName`) 
							SELECT :TermPersonId, tg.Id, tg.Name, :SeqNo, t.Id, t.Name 
							FROM eval_person_topic t 
							INNER JOIN eval_topic_group tg ON tg.Id=t.TopicGroupId 
							WHERE t.Id=:Id 
							";			
							$stmt = $pdo->prepare($sql);	
							$stmt->bindParam(':TermPersonId', $termPersonId);
							$stmt->bindParam(':SeqNo', $SeqNo);			
							$stmt->bindParam(':Id', $Id);
							$stmt->execute();	
							$SeqNo+=1;		
						}
					}
					$pdo->commit();
					
					header('Content-Type: application/json');
					echo json_encode(array('success' => true, 'message' => 'Data Inserted Complete.', 'sdNo' => $sdNo));
				} 
				//Our catch block will handle any exceptions that are thrown.
				catch(Exception $e){
					//Rollback the transaction.
					$pdo->rollBack();
					//return JSON
					header('Content-Type: application/json');
					$errors = "Error on Data Update. Please try again. " . $e->getMessage();
					echo json_encode(array('success' => false, 'message' => $errors));
				}
				
				break;		

			default : 
				header('Content-Type: application/json');
				echo json_encode(array('success' => 'warning', 'message' => 'Unknow action.'));				
		}
	}