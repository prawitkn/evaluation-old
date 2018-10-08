<?php

include('session.php');
//include('prints_function.php');
//include('inc_helper.php');

// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// Set font
		$this->SetFont('THSarabun', '', 16, '', true);
		// Title
        		
		//$this->SetY(11);			
		//if($this->page != 1){
			$this->SetFont('Times', '', 10, '', true);
			$this->Cell(0, 5, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
			//$this->Cell(0, 5, '- '.$this->getAliasNumPage().' -', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		//}
		 // Logo
        //$image_file = '../asset/img/logo-asia-kangnam.jpg';		
		//$img = file_get_contents('img\logo-asia-kangnam.jpg');
        //$this->Image($image_file, 10, 10, 15, 15, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		
		/*$this->SetFont('Times', 'B', 16, '', true);		
		$this->SetY(11);	
		$this->Cell(0, 5, 'Asia Kungnum Co.,Ltd.', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln(7);
		$this->SetFont('Times', 'B', 14, '', true);	
        $this->Cell(0, 5, 'Sales Order', 0, false, 'C', 0, '', 0, false, 'M', 'M');*/
    }
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        ///$this->SetY(-15);
        // Set font 
        $this->SetFont('THSarabun', '', 12, '', true);
        // Page number
		$tmp = date('Y-m-d H:i:s');
		//$tmp = to_thai_short_date_fdt($tmp);
		//$this->Cell(0, 10,'FM-MS-003; rev.03', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 10,'Print : '. $tmp, 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
	public function head($hdr){
		//head 
		$this->AddPage('P');
		
		$this->SetFont('THSarabun', 'B', 12, '', true);
		
		$this->setCellHeightRatio(1.0);
		
		$html='<table width="100%"  >	
		<tr>
			<td colspan="10">แบบประเมินผลงาน (สำหรับหัวหน้างาน-ผู้บริหาร)</td> 
		</tr>
		<tr>
			<td colspan="10">ครั่งที่ '.$hdr['term'].' ปี'.$hdr['year'].'</td> 
		</tr>
		<tr>			
			<td colspan="5">ผู้รับการประเมิน '.$hdr['personFullName'].'</td>
			<td colspan="5" >ผู้ประเมิน '.$hdr['fullName'].'</td>
		</tr>
		<tr>			
			<td colspan="5">ตำแหน่ง '.$hdr['personPositionName'].'</td>
			<td colspan="5" >ตำแหน่ง '.$hdr['positionName'].'</td>
		</tr>	
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		</table>
		';
		$this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		$this->setCellHeightRatio(1.50);
	}
	
	public function foot($hdr, $html){
		$html .='</tbody></table>';
		$this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		//$pdf->Ln(2);
		
				
	}
}

date_default_timezone_set("Asia/Bangkok");

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Prawit Khamnet');
$pdf->SetTitle('PDF');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

//remove header
//$pdf->setPrintHeader(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins (left, top, right)
$pdf->SetMargins(15, 15, 10);	//หน้า ๓ บนถึงตูดเลขหน้า ๒ ตูดเลขหน้าถึงตูดบรรทัดแรก ๑.๕
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('THSarabun', '', 16, '', true);

//Set Line spacing
$pdf->setCellHeightRatio(1.50);

// Set some content to print
if( isset($_GET['personId']) ){	
			$pdf->SetTitle($_GET['personId']);
						
		  
			//term sql
	  $termId=( isset($_GET['termId']) ? $_GET['termId'] : '' );

	  $sql = "SELECT hdr.id 
	  FROM eval_term hdr 
	  WHERE 1=1 
	  AND hdr.statusId=1 ";
	  if( $termId<> "" ){ $sql .= "AND hdr.id=:id "; }
	  $sql .= "ORDER BY hdr.isCurrent DESC, hdr.id DESC ";
	  $sql .= "LIMIT 1 ";

	  $stmt = $pdo->prepare($sql);  
	  if( $termId<> "" ){ $stmt->bindParam(':id', $termId); }      
	  //echo $sql;
	  $stmt->execute(); 
	  $termId=$stmt->fetch()['id'];




	  //personId 
	  $personId=( isset($_GET['personId']) ? $_GET['personId'] : $s_personId );

	  $evaluatorId=$s_personId; 
	  
	   $sql = "SELECT tp.id as termPersonId, CONCAT(t.term,'/',t.year) as termName, p.fullName as personFullName, p.positionId
	 	,tp.evaluatorPersonId, tp.evaluatorPersonId2, tp.evaluatorPersonId3
	 	,tp.score, tp.evaluatorTotal 
		  , pos.name as positionName, pos.positionRankId, pos.sectionId 
		  , sec.name as sectionName 
		  FROM eval_term_person tp
		  INNER JOIN eval_term t ON t.id=tp.termId 
		  INNER JOIN eval_person p ON p.id=tp.personId 
		  LEFT JOIN eval_position pos ON pos.id=p.positionId 
		  LEFT JOIN eval_section sec ON sec.id=pos.sectionId	  	
		  WHERE 1=1
		   AND tp.termId=:termId 
	 	 AND tp.personId=:personId
		  ";

		  $stmt = $pdo->prepare($sql);        
		  $stmt->bindParam(':termId', $termId);
		  $stmt->bindParam(':personId', $personId);
		  $stmt->execute(); 
		  $row=$stmt->fetch();

		  $termPersonId=$row['termPersonId'];
		  $evaluatorPersonId=$row['evaluatorPersonId'];
		  $evaluatorPersonId2=$row['evaluatorPersonId2'];
		  $evaluatorPersonId3=$row['evaluatorPersonId3'];

		   $sql = "SELECT hd.id, hd.statusId 
		   , tp.personId, ps2.fullName as personFullName, pos2.name as personPositionName 
		   , t.term, t.year 
		   , ps.fullName, pos.name as positionName 
		  FROM eval_result hd 
		  INNER JOIN eval_term_person tp ON tp.id=hd.termPersonId 
		  INNER JOIN eval_term t ON t.id=tp.termId 
		  LEFT JOIN eval_person ps2 ON ps2.id=hd.personId  
		  LEFT JOIN eval_position pos2 ON pos2.id=ps2.positionId 
		  LEFT JOIN eval_person ps ON ps.id=hd.evaluatorPersonId 
		  LEFT JOIN eval_position pos ON pos.id=ps.positionId 
		  WHERE 1=1
		   AND hd.termPersonId=:termPersonId 
	 	 AND hd.evaluatorPersonId=:evaluatorPersonId
		  ";

		    $stm = $pdo->prepare($sql);        
		  $stm->bindParam(':termPersonId', $termPersonId);
		  $stm->bindParam(':evaluatorPersonId', $evaluatorId);
		  $stm->execute(); 
		  $hdr=$stm->fetch();





		$sql = "SELECT `id`, `termPersonId`, `evalTypeId`, `evalTypeName`, `topicGroupId`, `topicGroupName`, `seqNo`, `topicId`, `topicName`, `topicDesc`
				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS scoreOwn

				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.evaluatorPersonId=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS score1

				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.evaluatorPersonId2=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS score2

				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.evaluatorPersonId3=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS score3

				,(SELECT IF(COUNT(rHdr.id)=0,1,COUNT(*)) 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId<>rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ) AS evaluatorTotal

			FROM `eval_data` t 
			WHERE t.topicGroupId=1 
			AND t.termPersonId=:termPersonId
			ORDER BY t.SeqNo 
			";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':termPersonId', $termPersonId);
			$stmt->execute();
			
			
			
			//Loop all item
			$iRow=0;
			$row_no = 1;  while ($row = $stmt->fetch()) { 
				if($iRow==0){
					
					$pdf->head($hdr);
					
					$html="";					
					$html ='
							<table class="table table-striped no-margin" style="width:100%; table-layout: fixed;"  >
								<thead>	
									<tr>										
										<th style="font-weight: bold; text-align: center; width: 150px; border: 0.1em solid black;">Product Series</th>
										<th style="font-weight: bold; text-align: center; width: 150px; border: 0.1em solid black;">Product Code</th>
										<th style="font-weight: bold; text-align: center; width: 170px; border: 0.1em solid black;">Description</th>								
										<th style="font-weight: bold; text-align: center; width: 60px; border: 0.1em solid black;">Quantity</th>								
										<th style="font-weight: bold; text-align: center; width: 40px; border: 0.1em solid black;">Unit</th>
										<th style="font-weight: bold; text-align: center; width: 80px; border: 0.1em solid black;"><span style="font-size: 75%">Delivery/Load Date</span></th>
									</tr>
								</thead>
								  <tbody>
							'; 
				}


				$html .='<tr>							
							<td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 150px;
										border: 0.1em solid black; padding: 10px; width: 150px;"> 
										 '.$row['prodName'].'</td>
							<td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;  max-width: 150px;
										border: 0.1em solid black; padding: 10px; width: 150px;"> '.$row['prodCode'].'</td>
							<td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;  max-width: 150px;
										border: 0.1em solid black; padding: 10px; width: 170px;"> '.$row['remark'].' '.($row['rollLengthId']<>'0'?'[RL:'.$row['rollLengthName'].']':'').'</td>
							<td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;  max-width: 60px;
										border: 0.1em solid black; text-align: right; width: 60px;">'.number_format($row['qty'],0,'.',',').'</td>						
							<td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;  max-width: 40px;
										border: 0.1em solid black; text-align: right; width: 40px;">'.$row['prodUomCode'].'</td>						
							<td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 65px;
										border: 0.1em solid black; padding: 10px; width: 80px;"> '.date('d M Y',strtotime( $row['deliveryDate'] )).'</td>
						</tr>';	
				
				//Loop item per page
				$iRow+=1;
				if($iRow==8){
					$pdf->foot($hdr, $html);
					
					
					$iRow=0;
				}
			}//end loop all item
			
			if($iRow<>9){
				for($iRowRemain=$iRow; $iRowRemain<=8; $iRowRemain++){
					$html .='<tr>
							<td style="font-weight: bold; text-align: center; width: 150px;border: 0.1em solid black;"></td>
							<td style="font-weight: bold; text-align: center; width: 150px;border: 0.1em solid black;"></td>
							<td style="font-weight: bold; text-align: center; width: 170px;border: 0.1em solid black;"></td>								
							<td style="font-weight: bold; text-align: center; width: 60px;border: 0.1em solid black;"></td>								
							<td style="font-weight: bold; text-align: center; width: 40px;border: 0.1em solid black;"></td>
							<td style="font-weight: bold; text-align: center; width: 80px;border: 0.1em solid black;"></td>							
						</tr>';	
				}
			}
			
			
			
			$pdf->foot($hdr, $html);
			
			



			}
			//<!--if isset $_GET['from_date']-->
		
		 
		   

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($personId.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
	?>