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

	$rootPage = 'positionGroup';
	$tb = 'eval_position_group';

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
		<h1><i class="fa fa-th-list"></i>
       กลุ่มตำแหน่ง
        <small>การจัดการข้อมูลหลัก</small>
      </h1>


      <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-home"></i>หน้าแรก</a></li>
       <!--<li><a href="<?=$rootPage;?>_list.php"><i class="fa fa-list"></i>รายการ กลุ่มตำแหน่ง</a></li>-->
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

<!-- To allow only admin to access the content -->      
    <div class="box box-primary">
        <div class="box-header with-border">
		<label class="box-title">รายการ กลุ่มตำแหน่ง</label>


			<a href="<?=$rootPage;?>_data.php?id=" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> เพิ่ม กลุ่มตำแหน่ง</a>
		
		
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
				FROM `".$tb."` hdr  
				WHERE 1=1 ";
				if(isset($_GET['search_word']) and isset($_GET['search_word'])){
					$search_word=$_GET['search_word'];
					$sql .= "and (hdr.name like '%".$_GET['search_word']."%' ) ";
				}			
                //$result = mysqli_query($link, $sql);
                //$countTotal = mysqli_fetch_assoc($result);
                $stmt = $pdo->prepare($sql);	
				$stmt->execute();	//echo $sql;
				$countTotal = $stmt->fetch()['countTotal'];
				
				$rows=100;
				$page=0;
				if( !empty($_GET["page"]) and isset($_GET["page"]) ) $page=$_GET["page"];
				if($page<=0) $page=1;
				$total_data=$countTotal;
				$total_page=ceil($total_data/$rows);
				if($page>=$total_page) $page=$total_page;
				$start=($page-1)*$rows;
				if($start<0) $start=0;		//echo $sql;  
          ?>
          <span class="label label-primary">จำนวน <?=$total_data; ?> รายการ</span>
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
			<div class="row col-md-12">				
				<form id="form1" action="<?=$rootPage;?>_list.php" method="get" class="form" novalidate>
					<div class="col-md-6">
						<div class="form-group">
	                        <label for="search_word">บางส่วนของ ชื่อกลุ่มตำแหน่ง เพื่อใช้ค้นหาข้อมูล</label>
							<input id="search_word" type="text" class="form-control" name="search_word" data-smk-msg="Require userFullname."required>
	                    </div>	
	                    <!--form-group-->
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

					
					</form>
			</div>
			<!--/.row-->
           <?php
				$sql = "
				SELECT hdr.`id`, hdr.`seqNo`, hdr.`code`, hdr.`name`, hdr.`statusId`
				, hdr.`createTime`, hdr.`createUserId`, hdr.`updateTime`, hdr.`updateUserId`
				, uc.userFullname as createUserName 
				, uu.userFullname as updateUserName 
				FROM `".$tb."` hdr 
				LEFT JOIN `eval_user` uc on uc.userId=hdr.createUserId 
				LEFT JOIN `eval_user` uu on uu.userId=hdr.updateUserId 
				WHERE 1=1 ";
				if(isset($_GET['search_word']) and isset($_GET['search_word'])){
					$search_word=$_GET['search_word'];
					$sql .= "and (hdr.name like '%".$_GET['search_word']."%' ) ";
				}	
				$sql .= "ORDER BY hdr.seqNo ASC
						LIMIT $start, $rows 
				";		
                //$result = mysqli_query($link, $sql);
				$stmt = $pdo->prepare($sql);	
				$stmt->execute();	//echo $sql;
              
           ?> 
            <form id="form2" class="form">
            	<input type="hidden" name="action" value="tableSubmit" />
            <table class="table table-striped">
                <tr>
					<th>ลำดับ</th>
					<th>รหัส</th>
					<th>ชื่อกลุ่มตำแหน่ง</th>
                    <th>สถานะ</th>
                    <th>#</th>
                </tr>
                <?php $c_row=($start+1); while ($row = $stmt->fetch()) { 
						?>
                <tr>
					<td>
						<input type="hidden" name="id[]" value="<?=$row['id'];?>"  />
						<input type="text" name="seqNo[]" class="form-control" style="width: 50px; text-align: right;" value="<?=$row['seqNo'];?>" onkeypress="return numbersOnly(this, event);" 
								onpaste="return false;" />
                    </td>
					 <td>
                         <?= $row['id']; ?>
                    </td>
                    <td>
                         <?= $row['name']; ?>
                    </td>
                    <td>
						 <?php
						 switch($row['statusId']){ 	
							case 1 :
								echo '<a class="btn btn-success" name="btnRowSetActive" data-statusId="0" data-id="'.$row['id'].'" >เปิดใช้งาน</a>';
								break;
							case 0 :
								echo '<a class="btn btn-default" name="btnRowSetActive" data-statusId="1" data-id="'.$row['id'].'" >ปิดการใช้งาน</a>';
								break;
							case 2 : 
								echo '<label style="color: red;" >ลบถาวร</label>';
								break;
							default :	
								echo '<label style="color: red;" >ไม่ปรากฏ </label>';
						}
						 ?>
                    </td>					
                    <td>
						
						<?php if($row['statusId']==1){ ?>
							<a class="btn btn-primary" name="btnRowEdit" href="<?=$rootPage;?>_data.php?act=edit&id=<?= $row['id']; ?>" >
								<i class="glyphicon glyphicon-edit"></i> แก้ไข</a>	
						<?php }else{ ?>	
							<a class="btn btn-primary"  disabled  > 
								<i class="glyphicon glyphicon-edit"></i> แก้ไข</a>	
						<?php } ?>
						
						<?php if($row['statusId']==0){ ?>
							<a class="btn btn-danger" name="btnRowRemove"  data-id="<?=$row['id'];?>" > 
								<i class="glyphicon glyphicon-remove"></i> ลบถาวร</a>	
						<?php }else{ ?>	
							<a class="btn btn-danger"  disabled  >
								<i class="glyphicon glyphicon-remove"></i> ลบถาวร</a>	
						<?php } ?>
						
						<?php if($row['statusId']==2 AND ($s_userGroupCode=='admin')){ ?>
							<a class="btn btn-danger" name="btnRowDelete"  data-id="<?=$row['id'];?>" > 
								<i class="glyphicon glyphicon-trash"></i> ลบถาวร</a>	
						<?php } ?>
                    </td>
                </tr>
                <?php $c_row+=1; } ?>
            </table>
			</form>
			<!--/.form2-->
			
			<a href="#" name="btnSubmit" class="btn btn-primary" ><i class="fa fa-save"></i> อัพเดต ลำดับการแสดงข้อมูลทั้งหมด</a>
				
			<nav>
			<ul class="pagination">
				<li <?php if($page==1) echo 'class="disabled"'; ?> >
					<a href="<?=$rootPage;?>_list.php?search_word=<?= $search_word;?>&=page=<?= $page-1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
				</li>
				<?php for($i=1; $i<=$total_page;$i++){ ?>
				<li <?php if($page==$i) echo 'class="active"'; ?> >
					<a href="<?=$rootPage;?>_list.php?search_word=<?= $search_word;?>&page=<?= $i?>" > <?= $i;?></a>			
				</li>
				<?php } ?>
				<li <?php if($page==$total_page) echo 'class="disabled"'; ?> >
					<a href="<?=$rootPage;?>_list.php?search_word=<?= $search_word;?>&page=<?=$page+1?>" aria-labels="Next"><span aria-hidden="true">&raquo;</span></a>
				</li>
			</ul>
			</nav>
			
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
	$('a[name=btnRowSetActive]').click(function(){
		var params = {
			action: 'setActive',
			id: $(this).attr('data-id'),
			statusId: $(this).attr('data-statusId')			
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
	//end btnRowSetActive
	
	$('a[name=btnRowRemove]').click(function(){
		var params = {
			action: 'remove',
			id: $(this).attr('data-id')
		};
		$.smkConfirm({text:'Are you sure to Remove ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
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
	//end btnRowRemove
	
	$('a[name=btnRowDelete]').click(function(){
		var params = {
			action: 'delete',
			id: $(this).attr('data-id')
		};
		$.smkConfirm({text:'Are you sure to Delete ?',accept:'Yes', cancel:'Cancel'}, function (e){if(e){
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
	//end btnRowDelete
	
	$('a[name=btnSubmit]').click(function(){
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

<!--Integers (non-negative)-->
<script>
  function numbersOnly(oToCheckField, oKeyEvent) {
    return oKeyEvent.charCode === 0 ||
        /\d/.test(String.fromCharCode(oKeyEvent.charCode));
  }
</script>

</body>
</html>
