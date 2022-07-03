<?php

session_start();
include "conn.php";

if(!isset($_SESSION['super-secret'])){
    header('location:s-user-login.php');
}

// latest blog
$sql = "SELECT * FROM blog_table ORDER BY blog_id DESC LIMIT 4";
$stmt = $conn -> prepare($sql);
$stmt -> execute();

// upcoming blog
$squel = "SELECT * FROM up_table ORDER BY up_id DESC LIMIT 3";
$stmt2 = $conn -> prepare($squel);
$stmt2 -> execute();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Super Admin</title>
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
							<h1 style="margin-left: 1rem;">Oga</h1>
						</a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav navbar-right">
							<li><a href="super-admin.php">Home</a></li>
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
									<li><a href="ss-cat.php?category=<?php echo $row['cat_name']; ?>"><?php echo $cat_name ?></a></li>
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
							<li><a href="allaccounts.php">All Accounts</a></li>
							<li><a href="s-dashboard.php">Manage Posts</a></li>
							<li><a href="">Our Team</a></li>
							<li><a href="">Contact</a></li>
							<li><a href="">About</a></li>
							<li><a href="super-logout.php">Logout</a></li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>
		</header>

		<!-- banner -->
		<div class="banner" style="background-image: url('img/4757790.jpg'); background-size: cover; ">
			<div class="container">
				<!-- heading -->
				<h1>Dattebayo</h1>
				<!-- paragraph -->
				<p>"I'm not going to run away and i never go back on my word, that is my ninja way"</p>
			</div>
		</div>
		<!-- banner end -->

		<!-- events -->
		<div class="event" id="event">
			<div class="container">
				<div class="default-heading">
					<!-- heading -->
					<h2>Upcoming events</h2>
				</div>
				<div class="row">
				<?php  if($stmt -> rowcount()===0){?>
                    <div class="post-preview">
                       <h1>THERE IS NOTHING AT THE MOMENT</h1>
                    </div>
                <hr>
                
				<div class="row">
				<?php }else{
                        while($data = $stmt2 -> fetch(PDO::FETCH_ASSOC)){
                            $_SESSION['up_id'] = $data['up_id'];
                            $_SESSION['up_title'] = $data['up_title'];
                            $_SESSION['up_message'] = $data['up_message'];
                            $_SESSION['up_category'] = $data['up_category'];
                            $_SESSION['up_image'] = $data['up_image'];
                    ?>
					<div class="col-md-4 col-sm-4">
						<!-- event item -->
						<div class="event-item">
							<!-- image -->
							<img style="display: block; max-width: 100%; height: 200px;" src="passport/<?php echo $_SESSION['up_image'] ?>" alt="Events" />
							<!-- heading -->
							<h4><a href="#" style="text-transform: capitalize;"><?php echo $_SESSION['up_title'] ?></a></h4>
							<!-- sub text -->
							<!-- <span class="sub-text">Integrating technology and software solutions.</span> -->
							<!-- paragraph -->
							<p style="text-transform: capitalize;"><?php echo $_SESSION['up_message'] ?></p>

						</div>
					</div>
					<?php } 
                    } ?>
				</div>
                <br><br>
				<center>
				<span class="input-group-btn">
					<a href="s-moreup.php"><button class="btn btn-default" type="button">See More</button></a>
				</span>
				</center>
			</div>
			<hr>
		</div>
		<!-- events end -->

		<!-- blog -->
		<div class="blog" id="blog">
			<div class="container">
				<div class="default-heading">
					<!-- heading -->
					<h2>Latest Blogs</h2>
				</div>
                <?php  if($stmt -> rowcount()===0){?>
                    <div class="post-preview">
                       <h1>THERE IS NOTHING AT THE MOMENT</h1>
                    </div>
                <hr>
                
				<div class="row">
				<?php }else{
                        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                            $_SESSION['blog_id'] = $row['blog_id'];
                            $_SESSION['blog_title'] = $row['blog_title'];
                            $_SESSION['blog_message'] = $row['blog_message'];
                            $_SESSION['blog_caption'] = $row['blog_caption'];
                            $_SESSION['blog_category'] = $row['blog_category'];
                            $_SESSION['blog_image'] = $row['blog_image'];
                            $_SESSION['blog_author'] = $row['blog_author'];
                            $_SESSION['blog_datetime'] = $row['blog_datetime'];
                    ?>
					<div class="col-md-6 col-sm-6">
						<!-- blog entry -->
						<div class="entry">
							<!-- blog entry image -->
							<img style="  display: block; max-width: 100%; height: 300px;" src="passport/<?php echo $_SESSION['blog_image'];?>" alt="Blog" />
							<!-- heading / blog post title -->
							<h3 style="text-transform: capitalize;"><a href="blog_page_super.php?id=<?php echo $_SESSION['blog_id']?>"><?php echo $_SESSION['blog_title'];?></a></h3>
							<!-- blog information -->
							<span class="meta">
                            <?php echo $_SESSION['blog_datetime'];?> | Tag: <a style="text-transform: capitalize;"  href="ss-cat.php?category=<?php echo $row['blog_category']; ?>"> <?php echo $_SESSION['blog_category'];?> </a>  | By: <?php echo $_SESSION['blog_author'];?>
							</span>
							<!-- paragraph -->
							<p style="text-transform: capitalize;"><?php echo $_SESSION['blog_caption'];?></p>
						</div>
					</div>
                    <?php } 
                    } ?>
                </div>
		</div>
        <br><br>
				<center>
				<span class="input-group-btn">
					<a href="s-morerecent.php"><button class="btn btn-default" type="button">See More</button></a>
				</span>
				</center>
		<!-- blog end -->

		<!-- subscribe -->
		<div class="subscribe" id="subscribe">
			<div class="container">
				<!-- subscribe content -->
				<div class="sub-content">
					<h3>Subscribe Here for Updates</h3>
					<form role="form">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Email...">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button">Subscribe</button>
							</span>
						</div><!-- /input-group -->
					</form>
				</div>
			</div>
		</div>
		<!-- subscribe end -->

		<!-- team -->
		<div class="team" id="team">
			<div class="container">
				<div class="default-heading">
					<!-- heading -->
					<h2>Executing Team</h2>
				</div>
				<div class="row">
					<div class="col-md-3 col-sm-3">
						<!-- team member -->
						<div class="member">
							<!-- images -->
							<img class="img-responsive" src="img/team/ifeanyi.png" alt="Team Member" />
							<!-- heading -->
							<h3>Oputa Lawrence</h3>
							<!-- designation -->
							<span class="dig">Admin</span>
							<!-- email -->
							<a href="#">oputalawrence@gmail.com</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<!-- team member -->
						<div class="member">
							<!-- images -->
							<img class="img-responsive" src="img/team/emperor.png" alt="Team Member" />
							<!-- heading -->
							<h3>Kings Dibie</h3>
							<!-- designation -->
							<span class="dig">Servant</span>
							<!-- email -->
							<a href="#">kingsdibie@gmail.com</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<!-- team member -->
						<div class="member">
							<!-- images -->
							<img class="img-responsive" src="img/team/1.jpg" alt="Team Member" />
							<!-- heading -->
							<h3>Chuks Peace</h3>
							<!-- designation -->
							<span class="dig">Erand Girl</span>
							<!-- email -->
							<a href="#">chukspeace@gmail.com.com</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<!-- team member -->
						<div class="member">
							<!-- images -->
							<img class="img-responsive" src="img/team/2.jpg" alt="Team Member" />
							<!-- heading -->
							<h3>Adam Miser</h3>
							<!-- designation -->
							<span class="dig">Sub Admin</span>
							<!-- email -->
							<a href="#">adammiser@bloger.com</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- team end -->

		<!-- footer -->
		<footer>
			<div class="container">
				<p><a href="#">Home</a> | <a href="#work">works</a> | <a href="#team">Team</a> | <a
						href="#contact">Contact</a></p>
				<div class="social">
					<a href="#"><i class="fa fa-facebook"></i></a>
					<a href="#"><i class="fa fa-twitter"></i></a>
					<a href="#"><i class="fa fa-dribbble"></i></a>
					<a href="#"><i class="fa fa-linkedin"></i></a>
					<a href="#"><i class="fa fa-google-plus"></i></a>
				</div>
				<!-- copy right -->
				<!-- This theme comes under Creative Commons Attribution 4.0 Unported. So don't remove below link back -->
				<p class="copy-right">Copyright &copy; 2014 <a href="#">Your Site</a> | Designed By : <a
						href="http://www.indioweb.in/portfolio">IndioWeb</a>, All rights reserved. </p>
			</div>
		</footer>

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