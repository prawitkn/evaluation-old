<?php
include 'session.php';

require_once '../sys/phpexcel/Classes/PHPExcel.php';

date_default_timezone_set("Asia/Bangkok");

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator($s_userFullname)
        ->setTitle("AK Evaluate")
        ->setSubject("Evaluate grade report")
        ->setDescription("Excel File")
        ->setKeywords("Evaluate")
        ->setCategory("HR");
		
$positionRankId=( isset($_GET['positionRankId']) ? $_GET['positionRankId'] : '' );
$sectionId=( isset($_GET['sectionId']) ? $_GET['sectionId'] : '' );

$sql = "
	SELECT hdr.`id`, hdr.`termId`, hdr.`personId`
	, hdr.`evaluatorPersonId`, hdr.`evaluatorPersonId2`, hdr.`evaluatorPersonId3`
	, hdr.`score`, hdr.`evaluatorTotal`, hdr.`gradeRankId`, hdr.`gradeId`, hdr.`statusId`
	, ps.code, ps.fullName,  ps.positionId
	, pos.name as positionName, pos.sectionId
	, sec.name as sectionName 
	, gr.name as gradeRankName 
	FROM eval_term_person hdr
	INNER JOIN eval_person ps ON ps.Id=hdr.personId 
	INNER JOIN eval_position pos ON pos.id=ps.positionId
	INNER JOIN eval_section sec ON sec.id=pos.sectionId
	LEFT JOIN eval_grade_rank gr ON gr.id=hdr.gradeRankId
	WHERE 1=1 
	";
	$sql .= "AND hdr.termId=(SELECT id FROM eval_term WHERE isCurrent=1) ";

	if( $positionRankId<>"" ){ $sql .= "AND pos.positionRankId=:positionRankId "; }
	if( $sectionId <> "" ) { $sql .= "AND pos.sectionId=:sectionId "; }


	$sql .= "ORDER BY hdr.score DESC ";
	//$sql .= "LIMIT $start, $rows ";		
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	if( $positionRankId <> "" ) { $stmt->bindParam(':positionRankId', $positionRankId); }
	if( $sectionId <> "" ) { $stmt->bindParam(':sectionId', $sectionId); }	
	$stmt->execute();	  

	$countTotal=$stmt->rowCount();

if($countTotal>0){
	// Add Header
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'รายงานวันที่ : '.date('Y-m-d H:m:s'));
		
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A2', 'รหัสพนักงาน')
		->setCellValue('B2', 'ชื่อ นามสกุล')
		->setCellValue('C2', 'ตำแหน่ง')
		->setCellValue('D2', 'แผนก')
		->setCellValue('E2', 'คะแนนเฉลี่ย')
		->setCellValue('F2', 'เกรดตามเกณฑ์')
		->setCellValue('G2', 'เกรดตามอัตรา');
	
	$iRow=3; while($row = $stmt->fetch() ){
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)		
			->setCellValue('A'.$iRow, $row['code'])
			->setCellValue('B'.$iRow, $row['fullName'])
			->setCellValue('C'.$iRow, $row['positionName'])
			->setCellValue('D'.$iRow, $row['sectionName'])
			->setCellValue('E'.$iRow, $row['score'])
			->setCellValue('F'.$iRow, $row['gradeRankName'])
			->setCellValue('G'.$iRow, $row['gradeName']);
			$iRow+=1;
	}
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Data');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="evaluate_report.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_clean();
$objWriter->save('php://output');
exit;