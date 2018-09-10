<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php include 'head.php'; 
$rootPage = 'evaluate_summary';

//Check user roll.
switch($s_userGroupCode){
	case 1 : case 3 : 
		break;
	default : 
		header('Location: access_denied.php');
		exit();
}
?>	<!-- head.php included session.php! -->
 
    
</head>
<body class="hold-transition skin-yellow sidebar-mini sidebar-collapse">

<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>
   <?php
   		$DeptName=( isset($_GET['DeptName']) ? $_GET['DeptName'] : 'FALSE' );

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
                //$sql_user = "SELECT COUNT(*) AS COUNTUSER FROM wh_user";
               // $result_user = mysqli_query($link, $sql_user);
               // $count_user = mysqli_fetch_assoc($result_user);
				
				$search_word="";
                $sql = "
				SELECT COUNT(*) AS countTotal 
				FROM eval_term_person hdr
				INNER JOIN eval_person ps ON ps.Id=hdr.personId ";
				if(isset($_GET['search_word']) and isset($_GET['search_word'])){
					$search_word=$_GET['search_word'];
					$sql .= "and (hdr.name like '%".$_GET['search_word']."%' ) ";
				}			
                $result = mysqli_query($link, $sql);
                $countTotal = mysqli_fetch_assoc($result);
				
				$rows=20;
				$page=0;
				if( !empty($_GET["page"]) and isset($_GET["page"]) ) $page=$_GET["page"];
				if($page<=0) $page=1;
				$total_data=$countTotal['countTotal'];
				$total_page=ceil($total_data/$rows);
				if($page>=$total_page) $page=$total_page;
				$start=($page-1)*$rows;
				if($start<0) $start=0;		
          ?>
          <span id="countTotal" class="label label-primary">Total <?php echo $countTotal['countTotal']; ?> items</span>
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
				<form id="form1" action="<?=$rootPage;?>.php" method="get" class="form form-inline" novalidate>
				
					<div class="row">
							<div class="col-md-3">					
								<label for="search_word">แผนก </label>
								<select name="DeptName" class="form form-control">
									<?php
										$sql = "SELECT DISTINCT DeptName FROM eval_person ORDER BY DeptName";
										$stmt = $pdo->prepare($sql);
										$stmt->execute();	
										While ( $row = $stmt->fetch() ){
											$selected=($DeptName==$row['DeptName']?' selected ':'');
											echo '<option value="'.$row['DeptName'].'" '.$selected.' >'.$row['DeptName'].'</option>';
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
					<th>แผนก</th>
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
	function a (){
		return 'big';
	}
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
								$html=$html+'<option value="'+value.Id+'" tmpSelected'+value.Id+' >'+value.Fullname+'</option>';
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

	function getListTotal(DeptName){		
		var params = {
			action: 'getListTotal',
			DeptName: DeptName
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
	function getList(DeptName){ //alert(DeptName);
		if( getListTotal() <= 0 ) {

		}else{	//alert('getListTotal ok');		
			//alert(getEvaluatorList('evaluatorPersonId'));
			var params = {
				action: 'getList',
				DeptName: DeptName,
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
								/*<th>No.</th>
			                    <th>Code</th>
								<th>Fullname</th>
								<th>Department</th>
								<th>Position</th>
								<th>Evaluator 1</th>
								<th>Evaluator 2</th>
								<th>Evaluator 3</th>*/
								$.each($.parseJSON(data.data), function(key,value){ 
									$('#tblData').append(
										'<tr>'+
										'<input type="hidden" name=Id[] value="'+value.Id+'" />'+
										'<td style="text-align: center;">'+$rowNo+'</td>'+
										'<td style="text-align: left;">'+value.Code+'</td>'+
										'<td style="text-align: left;">'+value.Fullname+'</td>'+
										'<td style="text-align: left;">'+value.DeptName+'</td>'+
										'<td style="text-align: left;">'+value.PositionName+'</td>'+
										'<td style="text-align: left;">'+value.Score+'</td>'+
										'<td style="text-align: left;">'+value.Score2+'</td>'+
										'<td style="text-align: left;">'+value.Score3+'</td>'+
										'<td style="text-align: left;">'+(value.Score+value.Score2+value.Score3)/3+'</td>'+
										'<td><a target="_blank" href="evaluate_view.php?tpId='+value.Id+'" class="btn btn-primary"><i fa fa-static></i> สรุป</a></td>'+
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
	getList('<?=$DeptName;?>');

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
