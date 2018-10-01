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

	$rootPage = 'evaluation';
	$tb = '';

   	$termPersonId=( isset($_GET['tpId']) ? $_GET['tpId'] : '' );
   	$topicGroupId=( isset($_GET['tgId']) ? $_GET['tgId'] : '1' );

?>	
</head>
<body class="hold-transition skin-yellow sidebar-mini sidebar-collapse">

<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>
   <?php

   	
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
        <li><a href="#"><i class="fa fa-list"></i> รายการ</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

<!-- To allow only admin to access the content -->      
    <div class="box box-primary">
        <div class="box-header with-border">
        	<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-list"></i> กำหนดหัวข้อการประเมิน </label>

        	<a href="javascript:history.go(-1)" class="btn btn-primary"><i class="fa fa-back"></i> กลับ</a>



			
		
		
	        <div class="box-tools pull-right">
	          <!-- Buttons, labels, and many other things can be placed here! -->
	          <!-- Here is a label for example -->
	         
	          <span class="label label-primary">Total <?php echo $countTotal['countTotal']; ?> items</span>
	        </div>
	        <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->

        <div class="box-body">
			
			<div class="row">
					<div class="col-md-12">
						<label style="font-size: 22px; color: black;" >ผู้รับการประเมิน : </label>
						<label style="font-size: 22px; color: blue;" id="evaluateFullName"></label>

						
					</div>
					<!--/.col-md-->


			</div>
			<!--/.row-->

			<div class="row col-md-12">
			<form id="form1" action="#" method="post" class="form form-inline" novalidate>
        		<label for="topicGroupId">กลุ่มหัวข้อประเมิน</label>
				<select name="topicGroupId" id="topicGroupId" class="form form-control">
					<?php
						$sql = "SELECT `id`, `name` FROM `eval_topic_group`";
						$stmt = $pdo->prepare($sql);
						$stmt->execute();	
						While ( $row = $stmt->fetch() ){
							$selected=($topicGroupId==$row['id']?' selected ':'');
							echo '<option value="'.$row['id'].'" '.$selected.' >'.$row['id'].' : '.$row['name'].'</option>';
						}
					?>
				</select>
				<a href="#" name="btnSearch" class="btn btn-primary"><i class="fa fa-search"></i> ดูข้อมูล</a>

				<a href="#" name="btnAddStandard" class="btn btn-primary"><i class="fa fa-plus"></i> เพิ่มจาก หัวข้อมาตรฐาน</a>

				<a href="#" name="btnAddPosition" class="btn btn-primary"><i class="fa fa-plus"></i> เพิ่มจาก ตำแหน่ง</a>
        	</form>
			</div>			
          
            <div class="row col-md-12 table-responsive">

            <form id="form2" action="<?=$rootPage;?>.php" method="get" class="form form-inline" novalidate>
            <input type="hidden" name="action" value="itemSubmit" />
            <table id="tblData" class="table table-hover">
                <thead><tr style="background-color: #ffcc99;">
					<th style="width: 100px;">ลำดับ</th>
					<th>รายการ</th>
					<th>#</th>
                </tr></thead>
                <tbody>
                	
                </tbody>
            </table>

            <!--<a name="btnSubmit2" class="btn btn-default"><i class="fa fa-save"></i> Submit</a>-->
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
			  	if ( data.status === "success" ) {	
			  		data = $.parseJSON(data.data);
					$('#evaluateFullName').text(data.personFullName);
			  	}else{ 
			  		alert(data.message);
					return 0;
			  	}
					
			  }   
			}).error(function (response) {
				alert(response.responseText);
			}); 
	}
	function getListTotal(termPersonId, topicGroupId){		
		var params = {
			action: 'getListTotal',
			termPersonId: termPersonId,
			topicGroupId: topicGroupId
		}; 
		/* Send the data using post and put the results in a div */
		$.ajax({
		  url: "<?=$rootPage;?>_ajax.php",
		  type: "post",
		  data: params,
		datatype: 'json',
		  success: function(data){
		  	if ( data.status === "success" ) {
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
	function getList(termPersonId, topicGroupId){ //alert('getList');
		if( getListTotal(termPersonId, topicGroupId) <= 0 ) {

		}else{	//alert('getListTotal ok');		
			//alert(getEvaluatorList('evaluatorPersonId'));
			var params = {
				action: 'getList',
				termPersonId: termPersonId,
				topicGroupId: topicGroupId
			}; //alert(params.sendDate);
			/* Send the data using post and put the results in a div */
			  $.ajax({
				  url: "<?=$rootPage;?>_ajax.php",
				  type: "post",
				  data: params,
				datatype: 'json',
				  success: function(data){	//alert(data);
				  	if ( data.status === "success" ) {
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
										'<td>'+value.seqNo+'</td>'+
										'<td>'+value.topicName+'</td>'+
										'<td>'+
										'<a href="#" name="btnRowDelete" data-id="'+value.id+'" class="btn btn-danger"><i fa fa-trash></i> ลบ</a>'+
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
	getList('<?=$termPersonId;?>','<?=$topicGroupId;?>');

	$('#tblData').on("click", "a[name=btnRowDelete]", function(){
		
		var params = {
			action: 'itmDelete',
			id: $(this).attr('data-id')
		};
		$.smkConfirm({text:'Are you sure to Delete ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
			$.post({
				url: '<?=$rootPage;?>_ajax.php',
				data: params,
				dataType: 'json'
			}).done(function (data) {					
				if (data.status === "success"){ 
					$.smkAlert({
						text: data.message,
						type: data.status,
						position:'top-center'
					});
					
					var topicGroupId=$('#topicGroupId').val();
					getList('<?=$termPersonId;?>', topicGroupId);
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
		}});
		e.preventDefault();
	});
	//end btn_row_delete

	$('a[name=btnSearch]').click(function(){	//alert('btnSearch');
		var topicGroupId=$('#topicGroupId').val();
		getList('<?=$termPersonId;?>', topicGroupId);

	});
	//end btnAddStandard

	$('a[name=btnAddStandard]').click(function(){	 
		var topicGroupId=$('#topicGroupId').val();
		window.location.href = '<?php echo $rootPage; ?>_add.php?tpId=<?php echo $termPersonId; ?>&tgId='+topicGroupId;

	});
	//end btnAddStandard

	$('a[name=btnAddPosition]').click(function(){	 
		var topicGroupId=$('#topicGroupId').val();
		window.location.href = '<?php echo $rootPage; ?>_add_position.php?tpId=<?php echo $termPersonId; ?>&tgId='+topicGroupId;

	});
	//end btnAddStandard
});
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
