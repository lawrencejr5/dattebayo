<?php

include "conn.php";
session_start();

if(!isset($_SESSION['secret'])){
    header('location:signin.php');
}
?>

    
<?php

if(isset($_POST['submit'])){



    $squel = "SELECT * FROM blog_table";
    $stmt2 = $conn -> prepare($squel);
    $stmt2 -> execute();
    $stmt2 -> fetch(PDO::FETCH_ASSOC);
    while($data = $stmt2 -> fetch()){
        $_SESSION['blog_id'] = $data['blog_id'];
        $_SESSION['blog_status'] = $data['blog_status'];
        $_SESSION['blog_title'] = $data['blog_title'];
    }



    $blog_id = $_SESSION['blog_id'];
    $blog_message = $_POST['message'];
    $blog_caption = $_POST['caption'];
    $blog_category = $_POST['cat'];
    $blog_title = $_POST['title'];
    $blog_author = $_SESSION['username'];

    // blog images variables

    $file_name = $_FILES['blog_img']['name'];
    $file_temp_name = $_FILES['blog_img']['tmp_name'];

    $extension = explode('.', $file_name);
    $allowed = ['jpg', 'png', 'jpeg'];
    $file_extension = end($extension);

    $new_name = substr(str_shuffle('qqwertyuioplkjhgfdsazxcvbnmpoiuytrewasdfghjkmnbvcxz'),0,10);
    $img_name = $new_name . '.' . $file_extension;
    $path = 'passport/' . $img_name;
    $status = 1;

    

    // blog images variables ends

    

    if(empty($blog_title) || empty($blog_message)){
        echo "<script>window.alert('Title or Message cannot be empty!!')</script>";
    }else{
        $sql = "INSERT INTO blog_table(blog_category, blog_message, blog_title, blog_caption, blog_author) VALUES(:blog_category, :blog_message, :blog_title, :blog_caption, :blog_author)";
        $stmt = $conn -> prepare($sql);

        $stmt -> bindParam(':blog_category', $blog_category);
        $stmt -> bindParam(':blog_title', $blog_title);
        $stmt -> bindParam(':blog_message', $blog_message);
        $stmt -> bindParam(':blog_caption', $blog_caption);
        $stmt -> bindParam(':blog_author', $blog_author);

        $res = $stmt -> execute();

        if($res){
            echo "inserted successfully";
            header("location: dashboard.php");
        }
        else{
            echo "An error occured!!";
        }
        // Sessions for blog_table
        $squel = "SELECT * FROM blog_table";
        $stmt2 = $conn -> prepare($squel);
        $stmt2 -> execute();
        $stmt2 -> fetch(PDO::FETCH_ASSOC);
        while($data = $stmt2 -> fetch()){
            $_SESSION['blog_id'] = $data['blog_id'];
            $_SESSION['blog_status'] = $data['blog_status'];
        }

        $blog_id = $_SESSION['blog_id'];


        if(in_array($file_extension, $allowed)){
        if(move_uploaded_file($file_temp_name, $path)){
            $file_sql = "UPDATE blog_table SET blog_image = :blog_img, blog_status = :blog_status WHERE blog_id = :blog_id";
            $file_stmt = $conn -> prepare($file_sql);

            $file_stmt -> bindParam(':blog_id', $blog_id);
            $file_stmt -> bindParam(':blog_status', $status);
            $file_stmt -> bindParam(':blog_img', $img_name);

            $file_stmt -> execute();
        }
    }
    else{
        echo "<script>alert('Data is not allowed')</script>";
    }
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog_Posts</title>
</head>
<style>
    body{
        background: white;
        background-size: cover;
        background-position: fixed;
        font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    h1{
        color: tomato;
    }
    select,input{
        width: 800px;
        height: 35px;
        outline: none;
        border: 1px solid tomato;

    }
    textarea{
        width: 800px;
        height: 500px;
        outline: none;
        border: 1px solid tomato;
    }
</style>
<body>
    <center>
        <h1>POST BLOG</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <select name="cat" id="">
            <option value="">Select Category</option>
            <?php  
            $sel_sql = "SELECT * FROM categories ORDER BY cat_id ASC";
            $sel_stmt = $conn -> prepare($sel_sql);
            
            $sel_stmt -> execute();
            while($row = $sel_stmt -> fetch(PDO::FETCH_ASSOC)){
                $cat_id = $row['cat_id'];
                $cat_name= $row['cat_name'];
               ?>
            <option value="<?php echo $cat_name ?>"><?php echo $cat_name ?></option>
            <?php  }?>
        </select>
        <br><br>
        <input type="text" name="title" placeholder="Title"><br><br>
        <input type="text" name="caption" placeholder="caption"><br><br>
        <input type="file" name="blog_img" id="blog_img" accept=".jpg, .png, .jpeg"><br>
        <br>
        <textarea name="message" id="" cols="50" rows="20" placeholder="Type Your Message"></textarea>
        <br>
        <input type="submit" name="submit" id="" value="send">
    </form>
    </center>
</body>
</html>