<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'getListTotal' :
				try{	

					$sectionId=$_POST['sectionId'];
					
					$sql = "SELECT hdr.id 
					FROM eval_term_person hdr
					INNER JOIN eval_term t ON t.id=hdr.termId AND t.isCurrent=1 
					INNER JOIN eval_person ps ON ps.id=hdr.personId ";
					if( $sectionId <> "" ) { $sql .= "WHERE ps.sectionId=:sectionId "; }
					$stmt = $pdo->prepare($sql);				
					if( $sectionId <> "" ) { $stmt->bindParam(':sectionId', $sectionId); }
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
					$sectionId=$_POST['sectionId'];

					$sql = "
					SELECT *, ROUND((score+score2+score3)/evaluatorTotal, 2) as avgScore 
					FROM (
						SELECT hdr.`id`, hdr.`termId`, hdr.`personId`
						,IFNULL((SELECT xh.score FROM eval_result xh 
						WHERE xh.termPersonId=hdr.id 
						AND xh.evaluatorPersonId=hdr.evaluatorPersonId),0) as score
						,IFNULL((SELECT xh.score FROM eval_result xh 
						WHERE xh.termPersonId=hdr.id 
						AND xh.evaluatorPersonId=hdr.evaluatorPersonId2),0) as score2
						,IFNULL((SELECT xh.Score FROM eval_result xh 
						WHERE xh.termPersonId=hdr.Id 
						AND xh.evaluatorPersonId=hdr.evaluatorPersonId3),0) as score3
						,IFNULL((SELECT COUNT(xh.id) FROM eval_result xh
						WHERE xh.evaluatorPersonId<>hdr.personId),1) as evaluatorTotal
						, ps.code, ps.fullName, ps.positionId
						, pos.name as positionName 
						FROM eval_term_person hdr
						INNER JOIN eval_term t ON t.id=hdr.termId AND t.isCurrent=1 
						INNER JOIN eval_person ps ON ps.id=hdr.personId 
						LEFT JOIN eval_position pos ON pos.id=ps.positionId 
						WHERE 1=1 
						";
						if( $sectionId <> "" ) { $sql .= "AND ps.sectionId=:sectionId "; }
					$sql .= ") as tmp "; 
					$sql .= "ORDER BY tmp.id ASC ";

					$stmt = $pdo->prepare($sql);					
					if( $sectionId <> "" ) { $stmt->bindParam(':sectionId', $sectionId); }
						
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

			default : 
				header('Content-Type: application/json');
				echo json_encode(array('success' => 'warning', 'message' => 'Unknow action.'));				
		}
	}