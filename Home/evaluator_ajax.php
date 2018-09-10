<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'getListTotal' :
				try{	

					$DeptName=$_POST['DeptName'];
					
					$sql = "SELECT hdr.Id 
					FROM eval_term_person hdr
					INNER JOIN eval_person ps ON ps.Id=hdr.personId ";
					if( $DeptName <> "" ) { $sql .= "WHERE ps.DeptName=:DeptName "; }
					$stmt = $pdo->prepare($sql);				
					if( $DeptName <> "" ) { $stmt->bindParam(':DeptName', $DeptName); }
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount ) );
				}catch(Exception $e){
				  	$errors = "Error : " . $e->getMessage();

					header('Content-Type: application/json');
				  	echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getList' :
				try{
					$start=$_POST['start'];
					$rows=$_POST['rows'];
					$DeptName=$_POST['DeptName'];

					$sql = "SELECT hdr.`Id`, hdr.`termId`, hdr.`personId`
					, hdr.`evaluatorPersonId`, hdr.`evaluatorPersonId2`, hdr.`evaluatorPersonId3`
					, ps.Code, ps.Fullname, ps.DeptName, ps.PositionName 
					FROM eval_term_person hdr
					INNER JOIN eval_person ps ON ps.Id=hdr.personId ";
					if( $DeptName <> "" ) { $sql .= "WHERE ps.DeptName=:DeptName "; }
					$sql .= "ORDER BY hdr.Id ASC ";
					//$sql.="LIMIT $start, $rows ";
					$stmt = $pdo->prepare($sql);					
					if( $DeptName <> "" ) { $stmt->bindParam(':DeptName', $DeptName); }
						
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					$jsonData = array();
					while ( $row=$stmt->fetch() ) {						
							$jsonData[] = $row;
					}


					//Evaluator List
					$sql = "SELECT hdr.`Id`, hdr.`Fullname`, hdr.`PositionName` 
					FROM eval_person hdr ";
					$sql .= "ORDER BY hdr.Id ASC ";
					//$sql.="LIMIT $start, $rows ";
					$stmt2 = $pdo->prepare($sql);
					$stmt2->execute();					
					$rowCount=$stmt2->rowCount();

					$jsonData2 = array();
					while ( $row=$stmt2->fetch() ) {						
							$jsonData2[] = $row;
					}
					
					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData), 'data2' => json_encode($jsonData2) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'itemSubmit' :		
				try{	
					//$sdNo = $_POST['sdNo'];
					
					$pdo->beginTransaction();	
					
					if(!empty($_POST['Id']) and isset($_POST['Id']))
					{						
						//$arrProdItems=explode(',', $prodItems);
						foreach($_POST['Id'] as $index => $Id )
						{	
							$sql = "UPDATE `eval_term_person` SET evaluatorPersonId=:evaluatorPersonId
							, evaluatorPersonId2=:evaluatorPersonId2, evaluatorPersonId3=:evaluatorPersonId3
							WHERE Id=:Id 
							";			
							$stmt = $pdo->prepare($sql);			
							$stmt->bindParam(':Id', $Id);	
							$stmt->bindParam(':evaluatorPersonId', $_POST['evaluatorPersonId'][$index]);
							$stmt->bindParam(':evaluatorPersonId2', $_POST['evaluatorPersonId2'][$index]);
							$stmt->bindParam(':evaluatorPersonId3', $_POST['evaluatorPersonId3'][$index]);
							$stmt->execute();			
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