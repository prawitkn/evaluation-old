<?php
  include ("session.php");
	//Check user roll.
	switch($s_userGroupCode){
		case 3 :  
			break;
		default : 
			header('Location: access_denied.php');
			exit();
	}  
  include 'head.php'; 
?>

<?php 
	$rootPage = 'evaluate_result';
	$tb = '';

	//$searchWord=( isset($_GET['searchWord']) ? $_GET['searchWord'] : '' );
	//$positionRankId=( isset($_GET['positionRankId']) ? $_GET['positionRankId'] : '' );
?>	
</head>
<body class="hold-transition skin-yellow sidebar-mini sidebar-collapse">    

<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

   <?php
   		$positionRankId=( isset($_GET['positionRankId']) ? $_GET['positionRankId'] : '' );
   		$sectionId=( isset($_GET['sectionId']) ? $_GET['sectionId'] : '' );

   ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><i class="fa fa-pie-chart"></i>
       	สรุปผลการประเมิน
        <small></small>
      </h1>
	  <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-list"></i> สรุปผลการประเมิน</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

	<!-- To allow only admin to access the content -->      
    <div class="box box-primary">
        <div class="box-header with-border">
        	<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-list"></i> รายการผลการประเมิน</label>

			<!--<a href="<?=$rootPage;?>_add.php?id=" class="btn btn-primary"><i class="fa fa-plus"></i> Add user group</a>-->
		
		
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
          <?php
                $sql = "
				SELECT COUNT(*) AS countTotal 
				FROM eval_term_person hdr
				INNER JOIN eval_person ps ON ps.id=hdr.personId 
				INNER JOIN eval_position pos ON pos.id=ps.positionId
				INNER JOIN eval_section sec ON sec.id=pos.sectionId
				LEFT JOIN eval_grade_rank gr ON gr.id=hdr.gradeRankId 
				WHERE 1=1 ";
				if($positionRankId<>""){ $sql .= "AND pos.positionRankId=:positionRankId "; }
				if($sectionId<>""){ $sql .= "AND pos.sectionId=:sectionId "; }

				$sql .= "AND hdr.termId=(SELECT id FROM eval_term WHERE isCurrent=1) ";
				//echo $sql;
                $stmt = $pdo->prepare($sql);	
                if($positionRankId<>""){ $stmt->bindParam(':positionRankId', $positionRankId); }
                if($sectionId<>""){ $stmt->bindParam(':sectionId', $sectionId); }
				$stmt->execute();	
				$countTotal = $stmt->fetch()['countTotal'];			
				
				$rows=20;
				$page=0;
				if( !empty($_GET["page"]) and isset($_GET["page"]) ) $page=$_GET["page"];
				if($page<=0) $page=1;
				$total_data=$countTotal;
				$total_page=ceil($total_data/$rows);
				if($page>=$total_page) $page=$total_page;
				$start=($page-1)*$rows;
				if($start<0) $start=0;		
          ?>
          <span id="countTotal" class="label label-primary">จำนวน <?php echo $countTotal; ?> รายการ</span>
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
			<div class="row col-md-12">				
				<form id="form1" action="<?=$rootPage;?>.php" method="get" class="form" novalidate>

				<div class="col-md-3">					
					<label for="positionRankId">ระดับ ตำแหน่ง </label>
					<select name="positionRankId" id="positionRankId" class="form form-control">
						<option value="">--ทั้งหมด--</option>
						<?php
							$sql = "SELECT `id`, `seqNo`, `name` FROM eval_position_rank ORDER BY seqNo, id ";
							$stmt = $pdo->prepare($sql);
							$stmt->execute();	
							While ( $itm = $stmt->fetch() ){
								$selected=($positionRankId==$itm['id']?' selected ':'');
								echo '<option value="'.$itm['id'].'" '.$selected.' >'.$itm['name'].'</option>';
							}
						?>
					</select>
				</div>  
				<!--/.col-md-->


				<div class="col-md-3">					
					<label for="sectionId">แผนก </label>
					<select name="sectionId" id="sectionId" class="form form-control">
						<option value="">--ทั้งหมด--</option>
						<?php
							$sql = "SELECT `id`, `seqNo`, `code`, `name` FROM eval_section ORDER BY seqNo, id ";
							$stmt = $pdo->prepare($sql);
							$stmt->execute();	
							While ( $itm = $stmt->fetch() ){
								$selected=($sectionId==$itm['id']?' selected ':'');
								echo '<option value="'.$itm['id'].'" '.$selected.' >'.$itm['name'].'</option>';
							}
						?>
					</select>
				</div>  
				<!--/.col-md-->

				

				<div class="col-md-1">
					<div class="form-group">
                        <label for="submit">&nbsp;</label>
						<input type="submit" name="submit" class="form-control btn btn-default" value="ค้นหา" />
                    </div>	
                    <!--form-group-->
				</div>
				<!--/.col-md-->

				<div class="col-md-1">
					<div class="form-group">
                        <label for="submit">&nbsp;</label>
						<a href="#" name="btnAutoGrading" class="btn btn-primary" ><i class="fa fa-cut"></i> ตัดเกรด</a>
                    </div>	
                    <!--form-group-->
				</div>
				<!--/.col-md-->

				
				</form>
			</div>
			<!--/.row-->
			
           <?php
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
				if(isset($_GET['search_word']) and isset($_GET['search_word'])){
					$search_word=$_GET['search_word'];
					$sql .= "and (hdr.userFullname like '%".$_GET['search_word']."%' ) ";
				}	
				if( $positionRankId<>"" ){ $sql .= "AND pos.positionRankId=:positionRankId "; }
				if( $sectionId <> "" ) { $sql .= "AND pos.sectionId=:sectionId "; }


				$sql .= "ORDER BY hdr.score DESC ";
				$sql .= "LIMIT $start, $rows ";		
                //echo $sql;
				$stmt = $pdo->prepare($sql);
				if( $positionRankId <> "" ) { $stmt->bindParam(':positionRankId', $positionRankId); }
				if( $sectionId <> "" ) { $stmt->bindParam(':sectionId', $sectionId); }	
				$stmt->execute();	                
           ?> 
            <div class="row col-md-12 table-responsive">

            <form id="form2" action="<?=$rootPage;?>.php" method="get" class="form form-inline" novalidate>
            <input type="hidden" name="action" value="itemSubmit" />
            <table id="tblData" class="table table-hover">
                <thead><tr style="background-color: #ffcc99;">
					<th>ลำดำ</th>
                    <th>รหัส</th>
					<th>ชื่อ นามสกุล</th>
					<th>ตำแหน่ง</th>
					<th>แผนก</th>
					<th>เฉลี่ย</th>				
					<th>เกรดตามเกณฑ์</th>				
					<th>เกรด</th>
					<th>#</th>
                </tr></thead>
                <tbody>
                <?php $rowNo=($start+1); while ($row = $stmt->fetch()) { 
						?>
                <tr>
					 <td>
                         <?= $rowNo; ?>
                    </td>
                    <td>
                         <?= $row['code']; ?>
                    </td>
                    <td>
                         <?= $row['fullName']; ?>
                    </td>
                    <td>
                         <?= $row['positionName']; ?>
                    </td>
                    <td>
                         <?= $row['sectionName']; ?>
                    </td>
                    <td style="text-align: right;">
                         <?= $row['score']; ?>
                    </td>
                    <td style="text-align: center;">
                         <?= $row['gradeRankName']; ?>
                    </td>
                    <td>
                    	<input type="hidden" name="itmId[]" value="<?=$row['id'];?>" />
                         <select name="gradeId[]" class="form form-control">
                         	<option value="0"> - เลือก - </option>
							<?php
								$gradeId=$row['gradeId'];
								$sql = "SELECT `id`, `seqNo`, `name` FROM eval_grade ORDER BY seqNo, id ";
								$stmt2 = $pdo->prepare($sql);
								$stmt2->execute();	
								While ( $itm = $stmt2->fetch() ){
									$selected=($gradeId==$itm['id']?' selected ':'');
									echo '<option value="'.$itm['id'].'" '.$selected.' >'.$itm['name'].'</option>';
								}
							?>
						</select>
                    </td>
                    <td>
                    	<a href="evaluate_view.php?tpId=<?=$row['id'];?>" class="btn btn-primary"><i fa fa-static></i> รายละเอียด</a>
                    </td>
                </tr>
                <?php $rowNo+=1; } ?>	
                </tbody>
            </table>
			</form>
			<!--/.form2-->
			</div>

			<?php $condQuery="?positionRankId=".$positionRankId."&sectionId=".$sectionId; ?>			
			<div class="row col-md-12">		
				<a href="<?=$rootPage;?>_xls.php<?=$condQuery;?>" class="btn btn-default pull-right"><i class="fa fa-print"></i> นำออก Excel</a>


            	<a name="btnGradeSubmit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> บันทึกเกรด</a>
			</div>
    </div><!-- /.box-body -->
  <div class="box-footer">
      
      
    <!--The footer of the box -->
  </div><!-- box-footer -->
</div><!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include'footer.php'; ?>
  
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>

<script src="bootstrap/js/smoke.min.js"></script>
<script>
$(document).ready(function() {
	
	 $('#sectionId').change(function() { //alert('f');
        this.form.submit();
    });

	$('a[name=btnAutoGrading]').click(function(){
		var params = {
			action: 'autoGrading'
		};
		$.smkConfirm({text:'Are you sure ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
			$.post({
				url: '<?=$rootPage;?>_ajax.php',
				data: params,
				dataType: 'json'
			}).done(function (data) {					
				if (data.success){ 
					$.smkAlert({
						text: data.message,
						type: 'success',
						position:'top-center'
					});
					//location.reload();
				} else {
					alert(data.message);
					$.smkAlert({
						text: data.message,
						type: 'danger'//,
					//                        position:'top-center'
					});
				}
			}).error(function (response) {
				alert(response.responseText);
			}); 
		}});
		e.preventDefault();
	});
	//end btnRowDelete

	$('a[name=btnGradeSubmit]').click(function(){
		$.smkConfirm({text:'Are you sure to Submit ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
			$.post({
				url: '<?=$rootPage;?>_ajax.php',
				data: $("#form2").serialize(),
				dataType: 'json'
			}).done(function (data) {					
				if (data.success){ 
					$.smkAlert({
						text: data.message,
						type: 'success',
						position:'top-center'
					});
					location.reload();
				} else {
					alert(data.message);
					$.smkAlert({
						text: data.message,
						type: 'danger'//,
					//                        position:'top-center'
					});
				}
			}).error(function (response) {
				alert(response.responseText);
			}); 
		}});
		e.preventDefault();
	});
	//end btnSubmit
	
});
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
