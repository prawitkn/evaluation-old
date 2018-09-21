<?php
  include ("session.php");
	//Check user roll.
	switch($s_userGroupCode){
		case 1 :  
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
				INNER JOIN eval_person ps ON ps.Id=hdr.personId ";
				if($sectionId<>""){ $sql .= "AND hdr.sectionId=:sectionId "; }

                $stmt = $pdo->prepare($sql);	
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
				<form id="form1" action="<?=$rootPage;?>.php" method="get" class="form form-inline" novalidate>
				
					<div class="row">
							<div class="col-md-3">					
								<label for="sectionId">แผนก </label>
								<select name="sectionId" class="form form-control">
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
								<label for="submit">&nbsp;</label>
								<input type="submit" name="submit" class="btn btn-default" value="ค้นหา">
							</div>  
							<!--/.col-md-->
					</div>
					<!--/.row-->
			
			
				</form>
				<!--/.form1-->	
			
           <?php
				$sql = "
				SELECT hdr.`Id`, hdr.`termId`, hdr.`personId`
				, hdr.`evaluatorPersonId`, hdr.`evaluatorPersonId2`, hdr.`evaluatorPersonId3`
				, ps.Fullname, ps.DeptName, ps.PositionName 
				FROM eval_term_person hdr
				INNER JOIN eval_person ps ON ps.Id=hdr.personId 
				WHERE 1=1 ";
				if(isset($_GET['search_word']) and isset($_GET['search_word'])){
					$search_word=$_GET['search_word'];
					$sql .= "and (hdr.userFullname like '%".$_GET['search_word']."%' ) ";
				}	
				$sql .= "ORDER BY hdr.id ASC
						LIMIT $start, $rows 
				";		
                //$result = mysqli_query($link, $sql);
				$stmt = $pdo->prepare($sql);	
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
					<th>ผู้ประเมินคนที่ 1</th>
					<th>ผู้ประเมินคนที่ 2</th>
					<th>ผู้ประเมินคนที่ 3</th>
					<th>เฉลี่ย</th>
					<th>#</th>
                </tr></thead>
                <tbody>
                	
                </tbody>
            </table>
			</form>
			<!--/.form2-->
			</div>

			<div class="row col-md-12">				
            	<a name="btnSubmit2" class="btn btn-primary  pull-right"><i class="fa fa-file"></i> นำออก Excel</a>
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
	
	function getListTotal(sectionId){		
		var params = {
			action: 'getListTotal',
			sectionId: sectionId
		}; //alert(params.sendDate);
		/* Send the data using post and put the results in a div */
		$.ajax({
		  url: "<?=$rootPage;?>_ajax.php",
		  type: "post",
		  data: params,
		datatype: 'json',
		  success: function(data){
		  	if ( data.success === "success" ) {
		  		//alert(data.rowCount);
				return data.rowCount;
		  	}else{ 
		  		alert(data.message);
				return 0;
		  	}
		  }   
		}).error(function (response) {
			alert(response.responseText);
			return 0;
		}); 
	}
	function getList(sectionId){ //alert(sectionId);
		if( getListTotal(sectionId) <= 0 ) {

		}else{	//alert('getListTotal ok');		
			//alert(getEvaluatorList('evaluatorPersonId'));
			var params = {
				action: 'getList',
				sectionId: sectionId,
				start: 0,
				rows: 200
			}; //alert(params.sendDate);
			/* Send the data using post and put the results in a div */
			  $.ajax({
				  url: "<?=$rootPage;?>_ajax.php",
				  type: "post",
				  data: params,
				datatype: 'json',
				  success: function(data){	//alert(data);
				  	if ( data.success === "success" ) {
				  		switch(data.rowCount){
							case 0 : alert('Data not found.');
								//$('#tbl_items tbody').empty();
								return false; break;
							default :
								//$('#tbl_items tbody').empty();
								$('#tblData tbody').fadeOut('slow').empty();
								$rowNo=1;
								$.each($.parseJSON(data.data), function(key,value){ 
									$('#tblData').append(
										'<tr>'+
										'<input type="hidden" name=Id[] value="'+value.id+'" />'+
										'<td style="text-align: center;">'+$rowNo+'</td>'+
										'<td style="text-align: left;">'+value.code+'</td>'+
										'<td style="text-align: left;">'+value.fullName+'</td>'+
										'<td style="text-align: left;">'+value.positionName+'</td>'+
										'<td style="text-align: left;">'+value.score+'</td>'+
										'<td style="text-align: left;">'+value.score2+'</td>'+
										'<td style="text-align: left;">'+value.score3+'</td>'+
										'<td style="text-align: left;">'+value.avgScore+'</td>'+
										'<td><a href="evaluate_view.php?tpId='+value.id+'" class="btn btn-primary"><i fa fa-static></i> สรุป</a></td>'+
										'</tr>');
									$rowNo+=1;
								});
								$('#tblData tbody').fadeIn('slow');
						}//.switch
				  	}else{ 
				  		alert(data.message);
						return 0;
				  	}
						
				  }   
				}).error(function (response) {
					alert(response.responseText);
				}); 
		}//.if rowCount <=0 
	}
	getList('<?=$sectionId;?>');

	$('a[name=btnSubmit2]').click(function(){
		$.post({
			url: '<?=$rootPage;?>_ajax.php',
			data: $("#form2").serialize(),
			dataType: 'json'
		}).done(function (data) {					
			if (data.status === "success"){ 
				$.smkAlert({
					text: data.message,
					type: data.status,
					position:'top-center'
				});
				location.reload();
			} else {
				alert(data.message);
				$.smkAlert({
					text: data.message,
					type: data.status
				});
			}
		}).error(function (response) {
			alert(response.responseText);
		}); 
		e.preventDefault();
	});
	//end btnSubmit2
	
});
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
