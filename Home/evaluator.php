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

	$rootPage = 'evaluator';
	$tb = 'eval_section';

	$sectionId=( isset($_GET['sectionId']) ? $_GET['sectionId'] : '' );

?>	
</head>
<body class="hold-transition skin-yellow sidebar-mini sidebar-collapse">    

<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><i class="fa fa-chain"></i>
       กำหนดการประเมิน
        <small></small>
      </h1>
	  <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-list"></i>กำหนดการประเมิน</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

<!-- To allow only admin to access the content -->      
    <div class="box box-primary">
        <div class="box-header with-border">
        	<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-chain"></i> กำหนดผู้ประเมิน</label>

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

            <div class="row col-md-12 table-responsive">

            <form id="form2" action="<?=$rootPage;?>.php" method="get" class="form" novalidate>
            <input type="hidden" name="action" value="itemSubmit" />
            <table id="tblData" class="table table-hover" style="overflow: scroll;">
                <thead><tr style="background-color: #ffcc99;">
					<th>ลำดับ</th>
                    <th style="width: 50px;">รหัส</th>
					<th style="width: 250px;">ชื่อ นามสกุล</th>
					<th>ตำแหน่ง</th>
					<th style="width: 150px;">ผู้ประเมินคนที่ 1</th>
					<th style="width: 150px;">ผู้ประเมินคนที่ 2</th>
					<th style="width: 150px;">ผู้ประเมินคนที่ 3</th>
					<th>#</th>
                </tr></thead>
                <tbody>
                	
                </tbody>
            </table>
			</form>
			<!--/.form2-->
			</div>

			<div class="row col-md-12">				
            	<a name="btnSubmit2" class="btn btn-primary  pull-right"><i class="fa fa-save"></i> บันทึกผู้ประเมิน</a>
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
	function getEvaluatorList(name){

		$html='';
		var params = {
			action: 'getEvaluatorList'
		}; //alert(params.sendDate);
		/* Send the data using post and put the results in a div */
		  $.ajax({
			  url: "<?=$rootPage;?>_ajax.php",
			  type: "post",
			  data: params,
			datatype: 'json',
			  success: function(data){	
			  	if ( data.success === "success" ) {
			  		alert(data.rowCount);
			  		switch(data.rowCount){
						case 0 : alert('Data not found.');
							//$('#tbl_items tbody').empty();
							return false; break;
						default : 
							$html='<select name="'+name+'[]">';	
							$.each($.parseJSON(data.data), function(key,value){
								$html=$html+'<option value="'+value.id+'" tmpSelected'+value.id+' >'+value.fullName+'</option>';
							});
							$html=$html+'</select>';
							//alert($html);
							return $html;
					}//.switch
			  	}else{ 
			  		alert(data.message);
					return '';
			  	}
					
			  }   
			}).error(function (response) {
				alert(response.responseText);
			}); 
			//return $html;
	}

	function getListTotal(sectionId){	
		if( sectionId == "") {return 0;	}
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
	function getList(sectionId){ //alert(DeptName);
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
								$sslEvaluator='<select name="evaluatorPersonId[]" class="form form-control" ><option value="" > - - เลือก - - </option>';
								$sslEvaluator2='<select name="evaluatorPersonId2[]" class="form form-control" ><option value="" > - - เลือก - - </option>'
								$sslEvaluator3='<select name="evaluatorPersonId3[]" class="form form-control" ><option value="" > - - เลือก - - </option>';	
								$.each($.parseJSON(data.data2), function(key,value){
									$option='<option value="'+value.id+'" selected'+value.id+' >'+value.fullName+'</option>';
									$sslEvaluator=$sslEvaluator+$option;
									$sslEvaluator2=$sslEvaluator2+$option;
									$sslEvaluator3=$sslEvaluator3+$option;
								});
								$sslEvaluator=$sslEvaluator+'</select>';
								$sslEvaluator2=$sslEvaluator2+'</select>';
								$sslEvaluator3=$sslEvaluator3+'</select>';

								//$('#tbl_items tbody').empty();
								$('#tblData tbody').fadeOut('slow').empty();
								$rowNo=1;
								$.each($.parseJSON(data.data), function(key,value){
									$sslEvaluator = $sslEvaluator.replace(new RegExp('selected'+value.evaluatorPersonId, 'g'), 'selected');
									$sslEvaluator2 = $sslEvaluator2.replace(new RegExp('selected'+value.evaluatorPersonId2, 'g'), 'selected');
									$sslEvaluator3 = $sslEvaluator3.replace(new RegExp('selected'+value.evaluatorPersonId3, 'g'), 'selected');
									$('#tblData').append(
										'<tr>'+
										'<input type="hidden" name=Id[] value="'+value.id+'" />'+
										'<td style="text-align: center;">'+$rowNo+'</td>'+
										'<td style="text-align: left;">'+value.code+'</td>'+
										'<td style="text-align: left;">'+value.fullName+'</td>'+
										'<td style="text-align: left;">'+value.positionName+'</td>'+
										'<td style="text-align: left;">'+$sslEvaluator+'</td>'+
										'<td style="text-align: left;">'+$sslEvaluator2+'</td>'+
										'<td style="text-align: left;">'+$sslEvaluator3+'</td>'+
										'<td>'+
										'<a href="evaluation.php?tpId='+value.id+'" class="btn btn-primary"><i class="fa fa-edit"></i> กำหนดหัวข้อประเมิน</a>'+
										'</td>'+
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
