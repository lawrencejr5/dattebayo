<?php

include "conn.php";
session_start();
if(!isset($_SESSION['secret'])){
    header('location:signup.php');
}
if(isset($_POST['submit'])){
    $cat_name = $_POST['cat_name'];
    $cat_des = $_POST['cat_des'];

    $sql = "INSERT INTO categories(cat_name, cat_des) VALUES(:cat_name, :cat_des)";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindParam(':cat_name', $cat_name);
    $stmt -> bindParam(':cat_des', $cat_des);

    $res = $stmt -> execute();

    if($res){
        echo "inserted successfully";
    }
    else{
        echo "An error occured!!";
    }
    
}

$sel_sql = "SELECT * FROM categories ORDER BY cat_id ASC";
$sel_stmt = $conn -> prepare($sel_sql);

$sel_stmt -> execute();

$sel_fetch = $sel_stmt -> setfetchmode(PDO::FETCH_ASSOC);

if(isset($_POST['delete'])){
    $cat_id = $_POST['delete'];
    $del_sql = "DELETE FROM categories WHERE cat_id = :cat_id";
    $del_stmt = $conn -> prepare($del_sql);
    $del_stmt -> bindParam(':cat_id',$cat_id);
    $del_res = $del_stmt -> execute();

    if($del_res){
        echo "<script>window.alert('deleted successfully')</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>

    <style>
        body{
            background: #ff6d1f;
            background-size: cover;
            background-position: fixed;
            font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h1,h2{
            text-transform: capitalize;
        }
        .body{
            background: #ff6d1f;
            height: auto;
            width: 800px;
            border-radius: 5px;
        }
        .category{
            background: whitesmoke;
            color: grey;
            height: auto;
            width: 500px;
            margin-bottom: 1rem;
            padding-bottom: 1rem;

        }
        #delete{
            float: right;
            background: red;
            color: white;
            border: none;
            border-radius: 5px;
            height: 20px;
            width: 80px;
        }
        input{
            width: 300px;
            height: 40px;
            outline: none;
            border: none;
        }
    </style>
</head>
<body>
    <center>
    <h1 style="color: white; font-weight: 900; text-decoration: underline;">Create Category</h1>
    <h2 style="color: white;" >WELCOME <?php echo $_SESSION['username'] ?></h2>
    <form action="" method="post">
        <input type="text" name="cat_name" placeholder="New Category"><br><br>
        <input type="text" name="cat_des" placeholder="Description"><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
    </center>
    <br><br><br><br>
    <center>
        <div class="body">
            <form action="" method="post">
                <?php  if($sel_stmt -> rowcount() === 0){?>
                    <div class="empty">
                        <p>NO CATEGORIES CREATED YET</p>
                    </div>
                <?php  }else{
                    while($row = $sel_stmt -> fetch(PDO::FETCH_ASSOC)){
                        $_SESSION['cat_id'] = $row['cat_id'];
                        $_SESSION['cat_name'] = $row['cat_name'];
                        $_SESSION['cat_des'] = $row['cat_des'];
                        $_SESSION['cat_datetime'] = $row['cat_datetime'];
                ?>
                        
                        <div class="category">
                            <a href="s-cat.php?category=<?php echo $row['cat_name']; ?>">
                            <h1 style="text-transform: uppercase;"><?php echo $_SESSION['cat_name'] ?></h1>
                            </a>
                            <h3><?php echo $_SESSION['cat_des'] ?></h3>
                            <small><?php echo $row['cat_datetime'] ?></small>

                            <button type="submit" name="delete" id="delete" value="<?= $row['cat_id']?>">Delete</button>
                        </div>
                        <hr>
                <?php  
                    }
                }?>
            </form>
        </div>
    </center>

    <br><br>

    <a href="logout.php"> signout</a> here
</body>
</html>