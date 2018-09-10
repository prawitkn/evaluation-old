<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php include 'head.php'; 
$rootPage = 'evaluation';

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
   	$termPersonId=$_GET['tpId'];

   ?>

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
        	<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-list"></i> เลือก หัวข้อประเมินจากกลุ่มการประเมิน</label>

        	<a href="javascript:history.go(-1)" class="btn btn-primary"><i class="fa fa-back"></i> กลับ</a>
		
		
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
         
          <span class="label label-primary">Total <?php echo $countTotal['countTotal']; ?> items</span>
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
				<form id="form1" action="<?=$rootPage;?>.php" method="get" class="form form-inline" novalidate>
					<div class="row">
							<div class="col-md-6">
								<label style="font-size: 22px; color: black;" >ผู้รับการประเมิน : </label>
								<label style="font-size: 22px; color: blue;" id="EvaluateFullName"></label>
							</div>
							<!--/.col-md-->
					</div>
					<!--/.row-->

					<div class="row">
							<div class="col-md-2">					
								<label for="positionGroupId">ประเภทผู้รับการประเมิน</label>
								<select name="positionGroupId" id="positionGroupId" class="form form-control">
									<?php
										$positionGroupId=$_GET['positionGroupId'];
										$sql = "SELECT `id`, `name` FROM `eval_position_group`";
										$stmt = $pdo->prepare($sql);
										$stmt->execute();	
										While ( $row = $stmt->fetch() ){
											$selected=($positionGroupId==$row['id']?' selected ':'');
											echo '<option value="'.$row['id'].'" '.$selected.' >'.$row['id'].' : '.$row['name'].'</option>';
										}
									?>
								</select>
							</div>  
							<!--/.col-md-->
							<div class="col-md-3">					
								<label for="topicGroupId">กลุ่มหัวข้อประเมิน</label>
								<select name="topicGroupId" id="topicGroupId" class="form form-control">
									<?php
										$topicGroupId=$_GET['topicGroupId'];
										$sql = "SELECT `id`, `name` FROM `eval_topic_group`";
										$stmt = $pdo->prepare($sql);
										$stmt->execute();	
										While ( $row = $stmt->fetch() ){
											$selected=($topicGroupId==$row['id']?' selected ':'');
											echo '<option value="'.$row['id'].'" '.$selected.' >'.$row['id'].' : '.$row['name'].'</option>';
										}
									?>
								</select>
							</div>  
							<!--/.col-md-->

							<div class="col-md-3">
								<br/>
								<a href="#" name="btnSubmit" class="btn btn-primary"><i class="fa fa-search"></i> ค้นหา</a>
							</div>  
							<!--/.col-md-->
							
					</div>
					<!--/.row-->
			
				</form>
				<!--/.form1-->
          
            <div class="row col-md-12 table-responsive">

            <form id="form2" action="<?=$rootPage;?>.php" method="get" class="form form-inline" novalidate>
            <input type="hidden" name="action" value="itemSubmit" />
            <input type="hidden" name="termPersonId" value="<?=$termPersonId;?>" />
            <table id="tblData" class="table table-hover">
                <thead><tr style="background-color: #ffcc99;">
                	<th>เลือก</th>
					<th>ลำดับ</th>
                    <th>กลุ่ม</th>
					<th>รายการ</th>
                </tr></thead>
                <tbody>
                	
                </tbody>
            </table>
            <a name="btnSubmit2" class="btn btn-default"><i class="fa fa-save"></i> Submit</a>
			</form>
			<!--/.form2-->

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
	function getHeader(termPersonId){
		var params = {
			action: 'getHeader',
			termPersonId: termPersonId
		}; //alert(params.sendDate);
		/* Send the data using post and put the results in a div */
		  $.ajax({
			  url: "<?=$rootPage;?>_ajax.php",
			  type: "post",
			  data: params,
			datatype: 'json',
			  success: function(data){	//alert(data);
			  	if ( data.success === "success" ) {	
			  		data = $.parseJSON(data.data);
					$('#EvaluateFullName').text(data.PersonFullName);
			  	}else{ 
			  		alert(data.message);
					return 0;
			  	}
					
			  }   
			}).error(function (response) {
				alert(response.responseText);
			}); 
	}
	function getListTotal(){		
		var params = {
			action: 'getItemByStandardListTotal',
			positionGroupId: $('#positionGroupId').val(),
			topicGroupId: $('#topicGroupId').val()
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
	function getList(){
		if( getListTotal() <= 0 ) {

		}else{	//alert('getListTotal ok');		
			//alert(getEvaluatorList('evaluatorPersonId'));
			var params = {
				action: 'getItemByStandardList',
				positionGroupId: $('#positionGroupId').val(),
				topicGroupId: $('#topicGroupId').val()
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
							case 0 : //alert('Data not found.');
								$('#tblData tbody').empty();
								return false; break;
							default : 

								//$('#tbl_items tbody').empty();
								$('#tblData tbody').fadeOut('slow').empty();
								$rowNo=1;
								$.each($.parseJSON(data.data), function(key,value){ 
									$('#tblData').append(
										'<tr>'+
										'<td style="text-align: center;">'+
										'<input type="checkbox" name="itmId[]" class="itmId" value="'+value.Id+'" />'+
										'</td>'+
										'<td style="text-align: center;">'+$rowNo+'</td>'+
										'<td >'+value.TopicGroupName+'</td>'+
										'<td >'+value.Name+'</td>'+
										'<a href="#" name="btnRowDelete" data-id="'+value.Id+'" class="btn btn-danger"><i fa fa-trash></i> ลบ</a>'+
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
	getHeader('<?=$termPersonId;?>');
	//

	$('a[name=btnSubmit]').click(function(){ //alert('big');
		getList();
	});
	//end btnSubmit

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
