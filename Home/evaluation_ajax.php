<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('status' => 'warning', 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'getHeader' :
				try{
					$termPersonId=$_POST['termPersonId'];

					$sql = "SELECT CONCAT(t.term,'/',t.year) as termName, p.fullName as personFullName, p.positionId
					, pos.name as positionName, pos.positionRankId, pos.sectionId 
					, sec.name as sectionName 
					FROM eval_term_person tp
					INNER JOIN eval_term t ON t.id=tp.termId 
					INNER JOIN eval_person p ON p.id=tp.personId 
					LEFT JOIN eval_position pos ON pos.id=p.positionId 
					LEFT JOIN eval_section sec ON sec.id=pos.sectionId
					WHERE 1=1
					AND tp.id=:id ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':id', $termPersonId);
					$stmt->execute();	

					$jsonData = array();
					$jsonData =$stmt->fetch();	

					header('Content-Type: application/json');				
					echo json_encode( array('status' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getListTotal' :
				try{
					$termPersonId=$_POST['termPersonId'];
					$topicGroupId=$_POST['topicGroupId'];

					$sql = "SELECT dt.id 
					FROM eval_term_person tp
					INNER JOIN eval_data dt ON tp.Id=dt.termPersonId 
					WHERE 1=1
					AND tp.id=:id
					AND dt.topicGroupId=:topicGroupId 
					 ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':id', $termPersonId);				
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					header('Content-Type: application/json');				
					echo json_encode( array('status' => 'success', 'rowCount' => $rowCount ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getList' :
				try{
					$termPersonId=$_POST['termPersonId'];
					$topicGroupId=$_POST['topicGroupId'];

					$sql = "SELECT dt.`id`, dt.`termPersonId`, dt.`evalTypeId`, dt.`evalTypeName`, dt.`topicGroupId`, dt.`topicGroupName`, dt.seqNo, dt.`topicId`, dt.`topicName`
					FROM eval_term_person tp
					INNER JOIN eval_data dt ON tp.id=dt.termPersonId 
					WHERE 1=1
					AND tp.id=:id
					AND dt.topicGroupId=:topicGroupId ";
					$sql .= "ORDER BY dt.topicGroupId, dt.seqNo ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':id', $termPersonId);				
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					$jsonData = array();
					while ( $row=$stmt->fetch() ) {						
							$jsonData[] = $row;
					}

					header('Content-Type: application/json');				
					echo json_encode( array('status' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getItemByStandardListTotal' :
				try{
					$positionRankId=$_POST['positionRankId'];
					$topicGroupId=$_POST['topicGroupId'];
					$positionGroupId=0;
					switch($positionRankId){
						case 7 : $positionGroupId=2; break;
						default : $positionGroupId=1; 
					}

					$sql = "SELECT t.`id`, t.`positionGroupId`, t.`topicGroupId`, t.`seqNo`, t.`name`
					FROM eval_topic t
					INNER JOIN eval_topic_group tg ON tg.id=t.topicGroupId 
					WHERE 1=1
					AND t.positionGroupId=:positionGroupId
					AND t.topicGroupId=:topicGroupId ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':positionGroupId', $positionGroupId);
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->execute();	
					$rowCount=$stmt->rowCount();

					header('Content-Type: application/json');				
					echo json_encode( array('status' => 'success', 'rowCount' => $rowCount ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getItemByStandardList' :
				try{
					$positionRankId=$_POST['positionRankId'];
					$topicGroupId=$_POST['topicGroupId'];
					$positionGroupId=0;
					switch($positionRankId){
						case 7 : $positionGroupId=2; break;
						default : $positionGroupId=1; 
					}

					$sql = "SELECT t.`id`, t.`positionGroupId`, t.`topicGroupId`, t.`seqNo`, t.`name`
					FROM eval_topic t
					INNER JOIN eval_topic_group tg ON tg.id=t.topicGroupId 
					WHERE 1=1
					AND t.positionGroupId=:positionGroupId
					AND t.topicGroupId=:topicGroupId ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':positionGroupId', $positionGroupId);
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->execute();				
					$rowCount=$stmt->rowCount();

					$jsonData = array();
					while ( $row=$stmt->fetch() ) {						
							$jsonData[] = $row;
					}

					header('Content-Type: application/json');				
					echo json_encode( array('status' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getItemByPositionListTotal' :
				try{
					$topicGroupId=$_POST['topicGroupId'];
					$positionId=$_POST['positionId'];

					$sql = "SELECT t.`id`, t.`positionGroupId`, t.`topicGroupId`, t.`seqNo`, t.`name`
					FROM eval_topic t
					INNER JOIN eval_topic_group tg ON tg.id=t.topicGroupId 
					WHERE 1=1
					AND t.topicGroupId=:topicGroupId 
					AND t.positionId=:positionId
					";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->bindParam(':positionId', $positionId);
					$stmt->execute();				
					$rowCount=$stmt->rowCount();

					header('Content-Type: application/json');				
					echo json_encode( array('status' => 'success', 'rowCount' => $rowCount ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getItemByPositionList' :
				try{
					$topicGroupId=$_POST['topicGroupId'];
					$positionId=$_POST['positionId'];

					$sql = "SELECT t.`id`, t.`positionGroupId`, t.`topicGroupId`, t.`seqNo`, t.`name`
					FROM eval_topic t
					INNER JOIN eval_topic_group tg ON tg.id=t.topicGroupId 
					WHERE 1=1
					AND t.topicGroupId=:topicGroupId 
					AND t.positionId=:positionId
					";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':topicGroupId', $topicGroupId);
					$stmt->bindParam(':positionId', $positionId);
					$stmt->execute();				
					$rowCount=$stmt->rowCount();

					$jsonData = array();
					while ( $row=$stmt->fetch() ) {						
							$jsonData[] = $row;
					}

					header('Content-Type: application/json');				
					echo json_encode( array('status' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 
				break;	


			case 'itemSubmit' :		
				try{	
					$termPersonId = $_POST['termPersonId'];
					
					$pdo->beginTransaction();	
					
					if(!empty($_POST['itmId']) and isset($_POST['itmId']))
					{						
						//$arrProdItems=explode(',', $prodItems);
						$seqNo=1;
						foreach($_POST['itmId'] as $index => $id )
						{	
							$sql = "INSERT INTO `eval_data` (`termPersonId`, `topicGroupId`, `topicGroupName`, `seqNo`, `topicId`, `topicName`) 
							SELECT :termPersonId, tg.id, tg.name, :seqNo, t.id, t.name 
							FROM eval_topic t 
							INNER JOIN eval_topic_group tg ON tg.id=t.topicGroupId 
							WHERE t.id=:id 
							";			
							$stmt = $pdo->prepare($sql);	
							$stmt->bindParam(':termPersonId', $termPersonId);
							$stmt->bindParam(':seqNo', $seqNo);			
							$stmt->bindParam(':id', $id);
							$stmt->execute();	
							$SeqNo+=1;		
						}
					}
					$pdo->commit();
					
					header('Content-Type: application/json');
					echo json_encode(array('status' => 'success', 'message' => 'Data Inserted Complete.'));
				} 
				//Our catch block will handle any exceptions that are thrown.
				catch(Exception $e){
					//Rollback the transaction.
					$pdo->rollBack();
					//return JSON
					header('Content-Type: application/json');
					$errors = "Error on Data Update. Please try again. " . $e->getMessage();
					echo json_encode(array('status' => 'danger', 'message' => $errors));
				}
				
				break;		

			case 'itmDelete' :
				try{					
					$id = $_POST['id'];
					
					
					$sql = "DELETE FROM eval_data WHERE id=:id ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':id', $id);
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

					$sql = "SELECT dt.id 
					FROM eval_person_topic tp
					INNER JOIN eval_topic_group tg ON tg.id=tp.topicGroupId 
					WHERE 1=1
					AND tp.personId=:personId ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':personId', $personId);
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
					$personId=$_POST['personId'];

					$sql = "SELECT tp.`id`, tp.`personId`, tp.`topicGroupId`, tp.`seqNo`, tp.`name`
					, tg.name as topicGroupName 
					FROM eval_person_topic tp
					INNER JOIN eval_topic_group tg ON tg.id=tp.topicGroupId 
					WHERE 1=1
					AND tp.personId=:personId ";

					$stmt = $pdo->prepare($sql);				
					$stmt->bindParam(':personId', $personId);
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
					echo json_encode(array('success' => 'success', 'message' => 'Data Inserted Complete.', 'sdNo' => $sdNo));
				} 
				//Our catch block will handle any exceptions that are thrown.
				catch(Exception $e){
					//Rollback the transaction.
					$pdo->rollBack();
					//return JSON
					header('Content-Type: application/json');
					$errors = "Error on Data Update. Please try again. " . $e->getMessage();
					echo json_encode(array('success' => 'danger', 'message' => $errors));
				}
				
				break;		

			default : 
				header('Content-Type: application/json');
				echo json_encode(array('success' => 'warning', 'message' => 'Unknow action.'));				
		}
	}