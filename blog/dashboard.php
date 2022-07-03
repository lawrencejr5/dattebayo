<?php

session_start();
include "conn.php";

if(!isset($_SESSION['secret'])){
    header('location:signin.php');
}

// latest blog
$sql = "SELECT * FROM blog_table ORDER BY blog_id DESC LIMIT 4";
$stmt = $conn -> prepare($sql);
$stmt -> execute();

// upcoming blog
$squel = "SELECT * FROM up_table ORDER BY up_id DESC LIMIT 3";
$stmt2 = $conn -> prepare($squel);
$stmt2 -> execute();

$stmt3 = $conn -> prepare("SELECT * FROM blog_table WHERE blog_author=:blog_author ORDER BY blog_id DESC");
$stmt3 -> bindParam(':blog_author', $_SESSION['username']);
$stmt3 -> execute();

if(isset($_POST['delete'])){
	$blog_id = $_POST['delete'];

	$sql4 = "DELETE FROM blog_table WHERE blog_id = :blog_id";
	$stmt4 = $conn -> prepare($sql4);
	$stmt4 -> bindParam(':blog_id', $blog_id);
	$res4 = $stmt4 -> execute();

	if($res4){
		header("location: admin.php");
	}
	else{
		echo "something went wrong";
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Dashboard</title>
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

	<style>
		button{
			height: 25px;
			width: 80px;
			color: white;
		}
	</style>
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
									<li><a href="s-cat.php?category=<?php echo $row['cat_name']; ?>"><?php echo $cat_name ?></a></li>
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
							<li><a href="logout.php">Logout</a></li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>
		</header>
		
        <div class="blog" id="blog">
			<div class="container">
				<div class="default-heading">
					<!-- heading -->
					<h1 style="text-transform: capitalize;">Dashboard Of <?php echo $_SESSION['username'] ?></h1>
					
				</div>
                <?php  if($stmt3 -> rowcount()===0){?>
                    <div class="post-preview">
                       <h1>NO POSTS YET</h1>
                       <h1><a href="blog_post.php">Create Ur First Post</a></h1>
                    </div>
                <hr>
                
				<div class="row">
				<?php }else{
                        while($row = $stmt3 -> fetch(PDO::FETCH_ASSOC)){
                            $_SESSION['blog_id'] = $row['blog_id'];
                            $_SESSION['blog_title'] = $row['blog_title'];
                            $_SESSION['blog_message'] = $row['blog_message'];
                            $_SESSION['blog_caption'] = $row['blog_caption'];
                            $_SESSION['blog_category'] = $row['blog_category'];
                            $_SESSION['blog_image'] = $row['blog_image'];
                            $_SESSION['blog_author'] = $row['blog_author'];
                            $_SESSION['blog_datetime'] = $row['blog_datetime'];
                    ?>
					<h2>All Your Posts</h2>
					<div class="col-md-6 col-sm-6">
						<!-- blog entry -->
						<div class="entry">
							<!-- blog entry image -->
							<img style="display: block; max-width: 100%; height: 300px;" src="passport/<?php echo $_SESSION['blog_image'];?>" alt="Blog" />
							<!-- heading / blog post title -->
							<h3 style="text-transform: capitalize;"><a href="#"><?php echo $_SESSION['blog_title'];?></a></h3>
							<!-- blog information -->
							<span class="meta">
                            <?php echo $_SESSION['blog_datetime'];?> | Tag: <a style="text-transform: capitalize;" href="s-cat.php?category=<?php echo $row['blog_category']; ?>"> <?php echo $_SESSION['blog_category'];?> </a>| By: <?php echo $_SESSION['blog_author'];?>
							</span>
							<!-- paragraph -->
							<p style="text-transform: capitalize;"><?php echo $_SESSION['blog_caption'];?></p>
                            
                            <a href="editpost.php?id=<?php echo $row['blog_id']?>"><button style="background: blue;" >Edit</button></a>
							<form action="" method="post">
                            	<button style="background: red;" type="submit" name="delete" id="delete" value="<?php echo $row['blog_id']?>">Delete</button>
                            </form>
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