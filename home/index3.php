<?php
  include ("session.php");
 
  include 'head.php'; 
?>

</head>
<body class="hold-transition skin-yellow sidebar-mini">    
  
<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

   <?php
	$rootPage = 'index3';

  //term sql
  $termId=( isset($_GET['termId']) ? $_GET['termId'] : '' );
  $positionRankId=( isset($_GET['positionRankId']) ? $_GET['positionRankId'] : '' );
  $sectionId=( isset($_GET['sectionId']) ? $_GET['sectionId'] : '' );

  $sql = "SELECT hdr.id 
  FROM eval_term hdr 
  WHERE 1=1 ";
  if( $termId<> "" ){ $sql .= "AND hdr.id=:id "; }
  $sql .= "ORDER BY hdr.isCurrent DESC, hdr.id DESC ";
  $sql .= "LIMIT 1 ";

  $stmt = $pdo->prepare($sql);  
  if( $termId<> "" ){ $stmt->bindParam(':id', $termId); }      
  
  $stmt->execute(); 
  $termId=$stmt->fetch()['id'];

  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-home"></i>
       หน้าแรก
        <small></small>
      </h1>

	  <ol class="breadcrumb">
        <li><a href="<?=$rootPage;?>.php"><i class="glyphicon glyphicon-list"></i>หน้าแรก</a></li>
		<!--<li><a href="#"><i class="glyphicon glyphicon-edit"></i>View</a></li>-->
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="box box-primary">
        <div class="box-body">
      <div class="row col-md-12">       
        <form id="form1" action="<?=$rootPage;?>.php" method="get" class="form form-inline" novalidate>
         <label for="termId">ห้วงเวลาประเมิน</label>
          <select name="termId" id="termId" class="form form-control">
            <?php
              $sql = "SELECT `id`, `seqNo`, CONCAT(`term`, '/',`year`) AS name FROM eval_term ORDER BY isCurrent DESC, seqNo, id ";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(); 
              While ( $itm = $stmt->fetch() ){
                $selected=($termId==$itm['id']?' selected ':'');
                echo '<option value="'.$itm['id'].'" '.$selected.' >'.$itm['name'].'</option>';
              }
            ?>
          </select>

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

        <label for="submit">&nbsp;</label>
            <input type="submit" name="submit" class="form-control btn btn-default" value="ค้นหา" />
        </form>
      </div>
      <!--/.row-->
      

    </div><!-- /.box-body -->
</div><!-- /.box -->

<div class="row">
<div class="col-md-6">
          <!-- MAP & BOX PANE -->
            <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-area-chart"></i> สรุปภาพรวม</h3>
              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <div id="container" style="width:100%; height:400px;">
                
              </div> 
            </div>
            <!-- /.box-body -->
            </div>
            <!-- /.box -->
                        
        </div>
        <!--col-md-8-->

        <div class="col-md-6">
            <div class="box box-primary">
<div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-star"></i> 10 อันดับคะแนนสูงสุด</h3>
              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
        <div class="box-body">
       <?php
              $sql = "SELECT  hdr.personId, psp.fullName as personFullName, hdr.score
        FROM eval_term_person hdr
        LEFT JOIN eval_person psp ON psp.id=hdr.personId 
        WHERE 1=1 
        AND hdr.termId=:termId 
                ";
                $sql .= "ORDER BY hdr.score DESC ";
                $sql .= "LIMIT 10 ";   
                       // echo $sql;
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':termId', $termId);
                $stmt->execute();                 
                ?> 
            <div class="row col-md-12 table-responsive">

            <table id="tblData" class="table table-hover">
                <thead><tr style="background-color: #ffcc99;">
          <th>ลำดับ</th>
                    <th>ชื่อ นามสกุล</th>
          <th>คะแนน</th>
                </tr></thead>
                <tbody>
                <?php $rowNo=($start+1); while ($row = $stmt->fetch()) { 
            ?>
                <tr style="<?php if($rowNo==1) echo 'color: #ff9900;'; 
                          if($rowNo==2) echo 'color: #ffa31a;';
                          if($rowNo==3) echo 'color: #ffad33;';
                    ?>">
           <td style="text-align: center;">
                         <?= $rowNo; ?>
                    </td>
                    <td  >
                         <?= $row['personFullName']; ?>
                    </td>
                    <td style="text-align: right;">
                         <?= $row['score']; ?>
                    </td>
                </tr>
                <?php $rowNo+=1; } ?> 
                </tbody>
            </table>
      </div>
      <!--div table-->
    

     
    </div><!-- /.box-body -->

  </div><!-- /.box -->
        </div>
        <!--col-md-4-->

</div>
<!--row-->



<div class="box box-danger">
        <?php
                $sql = "
        SELECT COUNT(*) AS countTotal 
        FROM eval_term_person hdr
LEFT JOIN eval_person pse ON pse.id=hdr.evaluatorPersonId
LEFT JOIN eval_person psp ON psp.id=hdr.personId 
WHERE 1=1 
AND hdr.termId=:termId 
AND hdr.evaluatorPersonId<>0
AND NOT EXISTS (SELECT * FROM eval_result x WHERE x.termPersonId=hdr.id 
                AND x.evaluatorPersonId=hdr.evaluatorPersonId 
                AND x.statusId=2)
                ";

        //if($positionRankId<>""){ $sql .= "AND pos.positionRankId=:positionRankId "; }
       // if($sectionId<>""){ $sql .= "AND pos.sectionId=:sectionId "; }
        //echo $sql;
                $stmt = $pdo->prepare($sql);  
                $stmt->bindParam(':termId', $termId);
             //   if($positionRankId<>""){ $stmt->bindParam(':positionRankId', $positionRankId); }
             //   if($sectionId<>""){ $stmt->bindParam(':sectionId', $sectionId); }
        $stmt->execute(); 
        $countTotal = $stmt->fetch()['countTotal'];     
        
        $rows=100;
        $page=0;
        if( !empty($_GET["page"]) and isset($_GET["page"]) ) $page=$_GET["page"];
        if($page<=0) $page=1;
        $total_data=$countTotal;
        $total_page=ceil($total_data/$rows);
        if($page>=$total_page) $page=$total_page;
        $start=($page-1)*$rows;
        if($start<0) $start=0;    
          ?>
        <div class="box-header with-border">
          <label class="box-tittle" style="font-size: 20px;"><i class="fa fa-warning"></i> รายการที่ยังไม่ได้ยืนยันผลการประเมิน</label>

      <!--<a href="<?=$rootPage;?>_add.php?id=" class="btn btn-primary"><i class="fa fa-plus"></i> Add user group</a>-->
    
    
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
          
          <span id="countTotal" class="label label-primary">จำนวน <?php echo $countTotal; ?> รายการ</span>
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
       <?php
              $sql = "SELECT hdr.evaluatorPersonId, hdr.personId 
        ,pse.fullName as evaluatorFullName, psp.fullName as personFullName 
        FROM eval_term_person hdr
        LEFT JOIN eval_person pse ON pse.id=hdr.evaluatorPersonId
        LEFT JOIN eval_person psp ON psp.id=hdr.personId 
        WHERE 1=1 
        AND hdr.termId=:termId 
        AND hdr.evaluatorPersonId<>0
        AND NOT EXISTS (SELECT * FROM eval_result x WHERE x.termPersonId=hdr.id 
                        AND x.evaluatorPersonId=hdr.evaluatorPersonId 
                        AND x.statusId=2)
                ";
                $sql .= "ORDER BY hdr.evaluatorPersonId ASC ";
                $sql .= "LIMIT $start, $rows ";   
                       // echo $sql;
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':termId', $termId);
                $stmt->execute();                 
                ?> 
            <div class="row col-md-12 table-responsive">

            <form id="form2" action="<?=$rootPage;?>.php" method="get" class="form form-inline" novalidate>
            <input type="hidden" name="action" value="itemSubmit" />
            <table id="tblData" class="table table-hover">
                <thead><tr style="background-color: #ffcc99;">
          <th>ลำดับ</th>
                    <th>ผู้ประเมิน</th>
          <th>ผู้รับการประเมิน</th>
                </tr></thead>
                <tbody>
                <?php $rowNo=($start+1); while ($row = $stmt->fetch()) { 
            ?>
                <tr>
           <td>
                         <?= $rowNo; ?>
                    </td>
                    <td>
                         <?= $row['evaluatorFullName']; ?>
                    </td>
                    <td>
                         <?= $row['personFullName']; ?>
                    </td>
                </tr>
                <?php $rowNo+=1; } ?> 
                </tbody>
            </table>
      </form>
      <!--/.form2-->
      </div>

     
    </div><!-- /.box-body -->
  <div class="box-footer">
      
      
    <!--The footer of the box -->
  </div><!-- box-footer -->
</div><!-- /.box -->

	</section>
	<!--sec.content-->
	
	</div>
	<!--content-wrapper-->
	
	<!-- Main Footer -->
  <?php include'footer.php'; ?>
  
</div>
<!--warpper-->

<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>

<script src="bootstrap/js/smoke.min.js"></script>

<!-- Add Spinner feature -->
<script src="bootstrap/js/spin.min.js"></script>

<!-- Hightchart -->
<script src="plugins/highcharts-5.0.12/code/highcharts.js"></script>
<script src="plugins/highcharts-5.0.12/code/modules/exporting.js"></script>

 <?php
        $sql = "SELECT hdr.id, hdr.name as gradeName
        ,(SELECT COUNT(*) FROM eval_term_person tp 
                WHERE tp.termId=:termId 
                AND tp.gradeId=hdr.id 
                GROUP BY hdr.id 
                ) as countTotal 
        FROM eval_grade hdr
        WHERE hdr.statusId=1 
            ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':termId', $termId);
    $stmt->execute();
        $gradeName = array();
        $countTotal = array();
        while($row = $stmt->fetch()){
            $gradeName[] = $row['gradeName'];            
            $countTotal[] = $row['countTotal'];
        }
  ?>
<script>
$(function () { 
  Highcharts.setOptions({
    colors: ['#ffcc99', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
});

    var myChart = Highcharts.chart('container', {
        chart: {
        type: 'area'
    },
    title: {
        text: 'จำนวนคน ตามสัดส่วนเกรด'
    },
    credits: {
        enabled: false
    },
    subtitle: {
        text: '<?=$termId;?>'
    },
    xAxis: {
        allowDecimals: false,
        labels: {
            formatter: function () {
                return this.value; // clean, unformatted number for year
            }
        }
    },
    yAxis: {
        title: {
            text: 'Nuclear weapon states'
        },
        labels: {
            formatter: function () {
                return this.value / 1000 + 'k';
            }
        }
    },
    tooltip: {
        pointFormat: 'มีจำนวน <b>{point.y:,.0f}</b> คน'
    },
        xAxis: {
            
            //categories: ['Apples', 'Bananas', 'Oranges'],
            categories: [<?php echo "'" . implode("','", $gradeName) . "'"; ?>]
                        //'prod5','prod6','prod7'
        },
        yAxis: {
            title: {
                text: ' คน'
            }
        },
        series: [{
            name: 'countTotal',
            data: [<?php echo implode(",", $countTotal); ?>],
            //data: [1, 0, 4]
            dataLabels: {
                enabled: true,
                inside: true,
                rotation: 270,
                y: -50,
                style: {
                            fontWeight: 'bold'
                        },
                        format: '{point.y:,.0f} คน'
                    }
       }
        ]
    });
  });
</script>

<script> 
$(document).ready(function() {
	$('#barcode').select();
	
	//setTimeout(function(){ getList(); }, 5000);
	
	//setInterval( getList(), 5000);
	/**
	 * Number.prototype.format(n, x)
	 * 
	 * @param integer n: length of decimal
	 * @param integer x: length of sections
	 */
	Number.prototype.format = function(n, x) {
	    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
	    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
	};

});
//doc ready
</script>





</body>
</html>
