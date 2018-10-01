<?php
  include ("session.php");
	//Check user roll.
	switch($s_userGroupCode){
		case 1 : case 3 :   
			break;
		default : 
			header('Location: access_denied.php');
			exit();
	}  
  include 'head.php'; 
?>

<?php 
	$rootPage = 'topic';
	$tb = 'eval_topic';

	$searchWord=( isset($_GET['searchWord']) ? $_GET['searchWord'] : '' );
	$topicGroupId=( isset($_GET['topicGroupId']) ? $_GET['topicGroupId'] : '' );
	$positionGroupId=( isset($_GET['positionGroupId']) ? $_GET['positionGroupId'] : '' );
	$positionId=( isset($_GET['positionId']) ? $_GET['positionId'] : '' );
?>	
</head>
<body class="hold-transition skin-yellow sidebar-mini ">    

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
       หัวข้อการประเมิน
        <small>การจัดการข้อมูลหลัก</small>
      </h1>


      <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-home"></i>หน้าแรก</a></li>
       <!--<li><a href="<?=$rootPage;?>_list.php"><i class="fa fa-list"></i>รายการ ตำแหน่ง</a></li>-->
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

<!-- To allow only admin to access the content -->      
    <div class="box box-primary">
        <div class="box-header with-border">
		<label class="box-title">รายการ หัวข้อการประเมิน</label>


			<a href="<?=$rootPage;?>_data.php?id=" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> เพิ่ม หัวข้อการประเมิน</a>
		
		
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
          <?php
                //$sql_user = "SELECT COUNT(*) AS COUNTUSER FROM wh_user";
               // $result_user = mysqli_query($link, $sql_user);
               // $count_user = mysqli_fetch_assoc($result_user);
	
                $sql = "
				SELECT COUNT(*) AS countTotal 
				FROM `".$tb."` hdr  
				WHERE 1=1 ";
				if($searchWord<>""){ $sql .= "AND hdr.name like :searchWord "; }
				if($topicGroupId<>""){ $sql .= "AND hdr.topicGroupId like :topicGroupId "; }
				if($positionGroupId<>""){ $sql .= "AND hdr.positionGroupId like :positionGroupId "; }
				if($positionId<>""){ $sql .= "AND hdr.positionId like :positionId "; }

                $stmt = $pdo->prepare($sql);	
                if($searchWord<>""){ $tmp='%'.$searchWord.'%'; $stmt->bindParam(':searchWord', $tmp); }
                if($topicGroupId<>""){ $stmt->bindParam(':topicGroupId', $topicGroupId); }
                if($positionGroupId<>""){ $stmt->bindParam(':positionGroupId', $positionGroupId); }
                if($positionId<>""){ $stmt->bindParam(':positionId', $positionId); }
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
					<div class="col-md-2">
						<div class="form-group">
	                        <label for="topicGroupId">กลุ่ม หัวข้อประเมิน</label>
							<select id="topicGroupId" name="topicGroupId" class="form-control"  data-smk-msg="จำเป็น" required>
								<option value="">--ทั้งหมด--</option>
								<?php
								$sql = "SELECT `id`, `code`, `name`, `statusId`  FROM `eval_topic_group` WHERE StatusId=1 ";		
								$stmt = $pdo->prepare($sql);		
								$stmt->execute();
								while($itm = $stmt->fetch()){
									$selected=( $topicGroupId==$itm['id'] ? ' selected ' : '' );
									echo '<option value="'.$itm['id'].'" '.$selected.'
										 >'.$itm['name'].'</option>';
								}
								?>
							</select>
	                    </div>		                    
					</div>
					<!--/.col-md-->

					<div class="col-md-2">
						<div class="form-group">
	                        <label for="positionId">ตำแหน่ง</label>
							<select id="positionId" name="positionId" class="form-control" >
								<option value="">--ทั้งหมด--</option>
								<?php
								$sql = "SELECT pos.`id`, pos.`code`, pos.`name`, pos.`statusId`  
								, sec.name as sectionName 
								FROM `eval_position` pos, eval_section sec 
								WHERE pos.sectionId=sec.id 
								AND pos.statusId=1 
								ORDER BY sec.seqNo, pos.seqNo 
								";		
								$stmt = $pdo->prepare($sql);		
								$stmt->execute();
								while($itm = $stmt->fetch()){
									$selected=( $positionId==$itm['id'] ? ' selected ' : '' );
									echo '<option value="'.$itm['id'].'" '.$selected.'
										 >'.$itm['sectionName'].' - '.$itm['name'].'</option>';
								}
								?>
							</select>
	                    </div>		                    
					</div>
					<!--/.col-md-->

					<div class="col-md-4">
						<div class="form-group">
	                        <label for="searchWord">บางส่วนของ หัวข้อการประเมิน เพื่อใช้ค้นหาข้อมูล</label>
							<input id="searchWord" type="text" class="form-control" name="searchWord" value="<?=$searchWord;?>">
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
				SELECT hdr.`id`, hdr.`seqNo`, hdr.`name`, hdr.`nameDesc`, hdr.`statusId`
				, hdr.`createTime`, hdr.`createUserId`, hdr.`updateTime`, hdr.`updateUserId`
				, IFNULL(tg.name,'ทั้งหมด') as topicGroupName
				, IFNULL(pg.name,'ทั้งหมด') as positionGroupName
				, IFNULL(p.name,'ทั้งหมด') as positionName 


				, uc.userFullname as createUserName 
				, uu.userFullname as updateUserName 
				FROM `".$tb."` hdr 
				LEFT JOIN eval_topic_group tg ON tg.id=hdr.topicGroupId 
				LEFT JOIN eval_position_group pg ON pg.id=hdr.positionGroupId 
				LEFT JOIN eval_position p ON p.id=hdr.positionId 

				LEFT JOIN `eval_user` uc on uc.userId=hdr.createUserId 
				LEFT JOIN `eval_user` uu on uu.userId=hdr.updateUserId 
				WHERE 1=1 ";
				if($searchWord<>""){ $sql .= "AND hdr.name like :searchWord "; }
				if($topicGroupId<>""){ $sql .= "AND hdr.topicGroupId like :topicGroupId "; }
				if($positionGroupId<>""){ $sql .= "AND hdr.positionGroupId like :positionGroupId "; }
				if($positionId<>""){ $sql .= "AND hdr.positionId like :positionId "; }

                $stmt = $pdo->prepare($sql);	
				$sql .= "ORDER BY hdr.seqNo ASC
						LIMIT $start, $rows 
				";		
				$stmt = $pdo->prepare($sql);	
                if($searchWord<>""){ $tmp='%'.$searchWord.'%'; $stmt->bindParam(':searchWord', $tmp); }
                if($topicGroupId<>""){ $stmt->bindParam(':topicGroupId', $topicGroupId); }
                if($positionGroupId<>""){ $stmt->bindParam(':positionGroupId', $positionGroupId); }
                if($positionId<>""){ $stmt->bindParam(':positionId', $positionId); }
				$stmt->execute();	//echo $sql;
              
           ?> 
            <form id="form2" class="form">
            	<input type="hidden" name="action" value="tableSubmit" />
            <table class="table table-striped">
                <tr style="background-color: #ffcc99;">
					<th>ID</th>
					<th>ลำดับ</th>
					<th>หัวข้อประเมิน</th>
					<th>เป้าหมาย/คำอธิบาย</th>
                    <th>สถานะ</th>
                    <th>#</th>
                </tr>
                <?php $c_row=($start+1); while ($row = $stmt->fetch()) { 
						?>
                <tr>
					 <td>
                         <?= $row['id']; ?>
                    </td>
					<td>
						<input type="hidden" name="id[]" value="<?=$row['id'];?>"  />
						<input type="text" name="seqNo[]" class="form-control" style="width: 50px; text-align: right;" value="<?=$row['seqNo'];?>" onkeypress="return numbersOnly(this, event);" 
								onpaste="return false;" />
                    </td>
                    <td>
                         <?= $row['name']; ?>
                    </td>
                    <td>
                         <?= $row['nameDesc']; ?>
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
			
			<a href="#" name="btnSubmit" class="btn btn-primary" ><i class="fa fa-save"></i> อัพเดต ลำดับการแสดงข้อมูลทั้งหมด ตามกลุ่มหัวข้อประเมินและตำแหน่ง</a>
				
			<nav>
			<ul class="pagination">
				<li <?php if($page==1) echo 'class="disabled"'; ?> >
					<a href="<?=$rootPage;?>_list.php?searchWord=<?= $searchWord;?>&=page=<?= $page-1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
				</li>
				<?php for($i=1; $i<=$total_page;$i++){ ?>
				<li <?php if($page==$i) echo 'class="active"'; ?> >
					<a href="<?=$rootPage;?>_list.php?searchWord=<?= $searchWord;?>&page=<?= $i?>" > <?= $i;?></a>			
				</li>
				<?php } ?>
				<li <?php if($page==$total_page) echo 'class="disabled"'; ?> >
					<a href="<?=$rootPage;?>_list.php?searchWord=<?= $searchWord;?>&page=<?=$page+1?>" aria-labels="Next"><span aria-hidden="true">&raquo;</span></a>
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
