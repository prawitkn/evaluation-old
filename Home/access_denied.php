<?php
  include ("session.php");
  
  include 'head.php'; 
?>
<?php
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
      <h1>
        Access Denied
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Main Page</a></li>
        <li class="active">Access Denied</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
	<div class="error-page">
		<h2 class="headline text-red">Access Denied</h2>

		<div class="error-content">
		  <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>

		  <p>
			<a href="javascript:history.go(-1)" class="btn btn-primary">Go Back to Previous Page...</a>
		  </p>
		  
		</div>
	  </div>
	  <!-- /.error-page -->
	

	</section>
	<!--sec.content-->
	
	</div>
	<!--content-wrapper-->

</div>
<!--warpper-->

</body>
</html>
