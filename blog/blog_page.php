<?php

session_start();
include "conn.php";

// latest blog


$id = $_GET['id'];

$squel = "SELECT * FROM blog_table WHERE blog_id = :blog_id";
$stmt3 = $conn -> prepare($squel);
$stmt3 -> bindParam(':blog_id', $id);
$stmt3 -> execute();

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Bloger Admin</title>
	<!-- Description, Keywords and Author -->
	<meta name="description" content="Your description">
	<meta name="keywords" content="Your,Keywords">
	<meta name="author" content="ResponsiveWebInc">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Styles -->
	<!-- Bootstrap CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- Font awesome CSS -->
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="css/style.css" rel="stylesheet">

	<!-- Favicon -->
	<link rel="shortcut icon" href="#">
</head>

<body>

	<div class="wrapper">

		<!-- header -->
		<header>
			<!-- navigation -->
			<nav class="navbar navbar-default" role="navigation">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse"
							data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#">
							<h1 style="margin-left: 1rem;">Admin</h1>
						</a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav navbar-right">
						<li><a href="dashboard.php">Dashboard</a></li>
							<li><a href="admin.php">Home</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories <span
										class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
                                <?php  
                                    $sel_sql = "SELECT * FROM categories ORDER BY cat_id ASC";
                                    $sel_stmt = $conn -> prepare($sel_sql);
                                    
                                    $sel_stmt -> execute();
                                    while($row = $sel_stmt -> fetch(PDO::FETCH_ASSOC)){
                                        $cat_id = $row['cat_id'];
                                        $cat_name= $row['cat_name'];
                                    ?>
									<li><a href="#"><?php echo $cat_name ?></a></li>
                                    <?php  }?>
                                    <li><a  style="color: blue;" href="categories.php">Create Categories</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Post Blog<span
										class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
								    <li><a href="blog_post.php">New Blog</a></li>
								    <li><a href="up_post.php">Upcoming Blog</a></li>
								</ul>
							</li>
							<li><a href="">Our Team</a></li>
							<li><a href="">Contact</a></li>
							<li><a href="">About</a></li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>
		</header>

        <div class="container">
        <?php  if($stmt3 -> rowcount()===0){?>
                    <div class="post-preview">
                       <h1>THERE IS NOTHING AT THE MOMENT</h1>
                    </div>
                <hr>
                
				<div class="row">
				<?php }else{
                        while($data = $stmt3 -> fetch(PDO::FETCH_ASSOC)){
                            $_SESSION['blog_id'] = $data['blog_id'];
                            $_SESSION['blog_title'] = $data['blog_title'];
                            $_SESSION['blog_message'] = $data['blog_message'];
                            $_SESSION['blog_category'] = $data['blog_category'];
                            $_SESSION['blog_image'] = $data['blog_image'];
                    ?>
					<div class="col-md-4 col-sm-4">
						<!-- event item -->
						<div class="post">
							<!-- image -->
							<img style=" height: 700px;" src="passport/<?php echo $_SESSION['blog_image'] ?>" alt="Events" />
							<!-- heading -->
							<h2 style="text-transform: capitalize; width: 120rem; color: tomato;"><?php echo $_SESSION['blog_title'] ?></h2><br><br>
							<!-- sub text -->
							<!-- <span class="sub-text">Integrating technology and software solutions.</span> -->
							<!-- paragraph -->
							<p style="text-transform: capitalize; width: 120rem;"><?php echo $_SESSION['blog_message'] ?></p>
                            <div></div>

						</div>
					</div>
					<?php } 
                    } ?>
				</div>
        </div>
            <!-- Javascript files -->
            <!-- jQuery -->
            <script src="js/jquery.js"></script>
            <!-- Bootstrap JS -->
            <script src="js/bootstrap.min.js"></script>
            <!-- Respond JS for IE8 -->
            <script src="js/respond.min.js"></script>
            <!-- HTML5 Support for IE -->
            <script src="js/html5shiv.js"></script>
            <!-- Custom JS -->
            <script src="js/custom.js"></script>
</body>

</html>