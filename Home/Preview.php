<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php include 'head.php'; 

function to_mysql_date($thai_date){
    if(strlen($thai_date) != 10){
        return null;
    }else{
        $new_date = explode('/', $thai_date);

        $new_y = (int)$new_date[2] - 543;
        $new_m = $new_date[1];
        $new_d = $new_date[0];

        $mysql_date = $new_y . '-' . $new_m . '-' . $new_d;

        return $mysql_date;
    }
}

?>


<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

   <?php
        $DateBegin=$_POST['DateBegin'];
        $DateEnd=$_POST['DateEnd'];  
        $RankCode=$_POST['RankCode'];
        $PSR=$_POST['PSR'];
        $Salary=$_POST['Salary'];
        $DateOfBirth=$_POST['DateOfBirth'];


        $DateBeginYmd = to_mysql_date($DateBegin);
        $DateEndYmd = to_mysql_date($DateEnd);
      
        try{
          $sql = "DELETE FROM `cr_extra` WHERE CreateUserId=:CreateUserId ";
          $stmt = $pdo->prepare($sql); 
          $stmt->bindParam(':CreateUserId', $s_userId);   
          $stmt->execute();    

        if(!empty($_POST['ExtraName']) and isset($_POST['ExtraName']))
        {            
            foreach($_POST['ExtraName'] as $index => $ExtraName )
            {   
                if ( $ExtraName<>"" ) {
                  $sql = "INSERT INTO `cr_extra`
                  (`SeqNo`, `Name`, `DateBegin`, `DateEnd`, `CreateUserId`) 
                  VALUES 
                  (:SeqNo, :Name, :DateBegin, :DateEnd, :CreateUserId)
                  ";         
                  $stmt = $pdo->prepare($sql); 
                  $stmt->bindParam(':SeqNo', $_POST['ExtraSeqNo'][$index]);            
                  $stmt->bindParam(':Name', $ExtraName);

                  $ExtraDateBegin = to_mysql_date($_POST['ExtraDateBegin'][$index]);
                  $stmt->bindParam(':DateBegin', $ExtraDateBegin);  

                  $ExtraDateEnd = to_mysql_date($_POST['ExtraDateEnd'][$index]);
                  $stmt->bindParam(':DateEnd', $ExtraDateEnd);  

                  $stmt->bindParam(':CreateUserId', $s_userId);   
                  $stmt->execute();    
                }//.if  
            }//.foreach
        }
        //.if

        $d1 = new DateTime($DateBeginYmd);
        $d2 = new DateTime($DateEndYmd);
        $diff = $d2->diff($d1);

        $AgeYear=$diff->y;
        $AgeMonth=$diff->m;
        $AgeDay=$diff->d;

        $AgeYearTotal=$AgeYear;
        $AgeMonthTotal=$AgeMonth;
        $AgeDayTotal=$AgeDay;



        $DateOfBirth=$_POST['DateOfBirth'];
          $DateOfBirthYmd = to_mysql_date($DateOfBirth);


        $d1 = new DateTime($DateOfBirthYmd);
        $d2 = new DateTime($DateEndYmd);
        $diff = $d2->diff($d1);

        $PersonAgeYear=$diff->y;
        $PersonAgeMonth=$diff->m;
        $PersonAgeDay=$diff->d;


     


        $Wat=0;
        $WatMsg="";
       

        switch($AgeYear){
          case $AgeYear>=1 && $AgeYear <=14 : $Wat=15*$Salary/50; 
            $WatMsg="เวลาราชการ 1 - 14 ปี เท่ากับ 15 คูณ เงินเดือน หาร 50 (15*".$Salary."/50)";
            break;
          case $AgeYear>=15 && $AgeYear <=24 : $Wat=25*$Salary/50; 
            $WatMsg="เวลาราชการ 15 - 24 ปี เท่ากับ 25 คูณ เงินเดือน หาร 50 (25*".$Salary."/50)";
            break;
          case $AgeYear>=25 && $AgeYear <=29 : $Wat=30*$Salary/50; 
            $WatMsg="เวลาราชการ 25 - 29 ปี เท่ากับ 35 คูณ เงินเดือน หาร 50 (35*".$Salary."/50)";
            break;
          case $AgeYear>=30 && $AgeYear <=34 : $Wat=35*$Salary/50; 
            $WatMsg="เวลาราชการ 30 - 34 ปี เท่ากับ 35 คูณ เงินเดือน หาร 50 (35*".$Salary."/50)";
            break;
          case $AgeYear>=35 && $AgeYear <=40 : $Wat=40*$Salary/50; 
            $WatMsg="เวลาราชการ 35 - 40 ปี เท่ากับ 45 คูณ เงินเดือน หาร 50 (40*".$Salary."/50)";
            break;
          case $AgeYear>=41 : $Wat=$AgeYear*$Salary/50; 
            $WatMsg="เวลาราชการ 41 ปีขึ้นไป เท่ากับ อายุราชการ คูณ เงินเดือน หาร 50 (".$AgeYear."*".$Salary."/50)";
            break;
          default : $Wat=0;
            $WatMsg="อายุราชการไม่ถึง 1 ปี";
            break;
        }//.switch 

        if($Wat>$Salary){
          $WatMsg.="เบี้ยหวัดคำนวณ (".$Wat.") ได้สูงเกินเงินเดือนสุดท้าย (".$Salary.") ";
          $Wat=$Salary;
        }  



        $BumNetOld=$AgeYearTotal*($Salary+$PSR);
        $BumNanOld=0;
        if( $Salary <= (($Salary*$AgeYearTotal)/50) ){
            $BumNanOld=(($Salary*$AgeYearTotal)/50);
        }else{
            $BumNanOld=$Salary;
        }

         $BumNet=$AgeYearTotal*($Salary+$PSR);
          $BumNan=$AgeYearTotal*($Salary+$PSR)/50;
          if( $BumNan <= (($Salary+$PSR)*0.70) ){
            $BumNan=$BumNan;
        }else{
            $BumNan=(($Salary+$PSR)*0.70);
        }



 }catch(Exception $e){
        echo $e;
      }
   ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
		คำนวณอายุราชการ เบี้ยหวัด บำเหน็จ บำนาญ สบ.ทหาร
      </h1>
      <ol class="breadcrumb">
        <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i> หน้าแรก</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

	
	<div class="box box-primary">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">คำนวณอายุราชการ เบี้ยหวัด บำเหน็จ บำนาญ สบ.ทหาร</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <div class="row col-md-12">
                    <div class="col-md-3">
                        <label for="DateBegin">วันเริ่มต้น : </label>
                        <?=$DateBegin; ?>
                    </div>
                    <div class="col-md-3">
                        <label for="DateEnd">วันสุดท้าย : </label>
                        <?=$DateEnd;?>
                    </div>
                    <div class="col-md-3">
                        <label for="DateEnd">พ.ส.ร. : </label>
                        <?=$PSR;?>
                    </div>
                    <div class="col-md-3">
                        <label for="DateEnd">เงินเดือนสุดท้าย : </label>
                        <?=$Salary;?>
                    </div>
                </div>
                <!--/.row-->  
                 <div class="row col-md-12">                    
                    <div class="col-md-8">
                        <table class="table table-hover" border="1">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รายการ</th>
                                    <th>อายุ</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>ปกติ</td>
                                    <td><?php  echo $AgeYear.' ปี '.$PersonAgeMonth.' เดือน'; ?></td>                                    
                                </tr>
                                <?php try{
                                $sql = "SELECT `SeqNo`, `Name`, `DateBegin`, `DateEnd` FROM `cr_extra` WHERE `CreateUserId`=:CreateUserId ORDER BY SeqNo ";        
                                $stmt = $pdo->prepare($sql); 
                                $stmt->bindParam(':CreateUserId', $s_userId);   
                                $stmt->execute(); 


                                  $ExtraYearTotal=0;
                                  $ExtraMonthTotal=0;  
                                while( $row = $stmt->fetch() ) { 
                                  $d1 = new DateTime($row['DateBegin']);
                                  $d2 = new DateTime($row['DateEnd']);
                                  $diff = $d2->diff($d1);
                                  $ExtraYear=$diff->y;
                                  $ExtraMonth=$diff->m+1;
                                  if($ExtraMonth>=12){
                                    $ExtraYear+=1;
                                    $ExtraMonth-=12;
                                  }

                                  echo '<tr>
                                    <td>'.$row['SeqNo'].'</td>
                                    <td>'.$row['Name'].' ('.$row['DateBegin'].' - '.$row['DateEnd'].')</td>
                                    <td>'.$ExtraYear.' ปี '.$ExtraMonth.' เดือน'.'</td>
                                  </tr>';

                                  $ExtraYearTotal+=$ExtraYear;
                                  $ExtraMonthTotal+=$ExtraMonth;
                                }//.While

                                $AgeYearTotal+=$ExtraYearTotal;   
                                $AgeMonthTotal+=$ExtraMonthTotal;
                                if($AgeMonthTotal>=12){
                                    $AgeYearTotal+=1;
                                    $AgeMonthTotal-=12;
                                  }   

                                                            
                                  }catch(Exception $e){
        echo $e;
      }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-4">

                        <h3 for="DateBegin" style="color: blue;">อายุ : <?php echo $PersonAgeYear.' ปี '.$PersonAgeMonth.' เดือน';?></h3>
                        <h3 for="DateBegin" style="color: red;">อายุราชการ : <?php echo $AgeYearTotal.' ปี '.$AgeMonthTotal.' เดือน';?></h3>
                        <h3 style="color: red;"></h3>                        
                    </div>
                </div>
                <!--/.row-->  

                <div class="row col-md-12">
                    <div class="col-md-4">
                        
                    </div>
                    <!--/.col-md-->
                    <div class="col-md-4">
                        
                        
                    </div>
                    <!--/.col-md-->
                    <div class="col-md-4">
                       
                    </div>
                    <!--/.col-md-->                    
                </div>
                <!--/.row-->  
            </div> 
            <!-- /.box-body -->
        </div> 
        <!-- /.box -->

        <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12">
           <div class="info-box">
             <span class="info-box-icon bg-blue"><i class="fa fa-hand-grab"></i></span>
             <div class="info-box-content"> 
               
               <h3 for="DateBegin" style="color: blue;">เบี้ยหวัด : </h3>
                        <h3 style="color: blue;"><?=$Wat; ?></h3>
                        <?=$WatMsg;?>
               
             </div><!-- /.info-box-content -->
           </div> <!-- /.info-box -->
        </div> <!-- /.col --> 
        
        <div class="col-md-4 col-sm-6 col-xs-12">
           <div class="info-box">
             <span class="info-box-icon bg-green"><i class="fa fa-diamond"></i></span>
             <div class="info-box-content"> 
              
              <h3 for="DateBegin" style="color: green;">บำเหน็จ : </h3>
                <h3 style="color: green;">แบบเก่า => <?=$BumNetOld; ?></h3>
                <h3 style="color: green;">กบข. => <?=$BumNet; ?></h3>
               
             </div><!-- /.info-box-content -->
           </div> <!-- /.info-box -->
        </div> <!-- /.col --> 
                
        <div class="clearfix visible-sm-block"></div>
        
        <div class="col-md-4 col-sm-6 col-xs-12">
           <div class="info-box">
             <span class="info-box-icon bg-red"><i class="fa fa-calendar"></i></span>
             <div class="info-box-content"> 
             
                <h3 for="DateBegin" style="color: Fuchsia;">บำนาญ : </h3>
                <h3 style="color: Fuchsia;">แบบเก่า => <?=$BumNanOld; ?></h3>
                <h3 style="color: Fuchsia;">กบข. => <?=$BumNan; ?></h3>
                        
               
             </div><!-- /.info-box-content -->
           </div> <!-- /.info-box -->
        </div> <!-- /.col --> 
        
    </div> <!-- /.row -->   
    			

	



	
	
	</section>
	<!--sec.content-->


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
<!-- smoke validate -->
<script src="bootstrap/js/smoke.min.js"></script>

</body>
</html>


	
	