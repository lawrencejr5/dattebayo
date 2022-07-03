<?php

include "conn.php";
session_start();

?>

    
<?php

$blog_id = $_GET['id'];


$squel = "SELECT * FROM blog_table where blog_id = :blog_id";
$stmt2 = $conn -> prepare($squel);
$stmt2 -> bindParam(':blog_id', $blog_id);
$stmt2 -> execute();
// print_r($stmt2 -> fetchall());

while($data = $stmt2 -> fetch(PDO::FETCH_ASSOC)){
    
    $_SESSION['blog_title'] = $data['blog_title'];
    $_SESSION['blog_message'] = $data['blog_message'];
    $_SESSION['blog_caption'] = $data['blog_caption'];
    $_SESSION['blog_category'] = $data['blog_category'];
    $_SESSION['blog_image'] = $data['blog_image'];
    $_SESSION['blog_id'] = $blog_id;
}



if(isset($_POST['submit'])){
    $blog_id = $_GET['id'];
    $blog_title = $_POST['title'];
    $blog_message = $_POST['message'];
    $blog_caption = $_POST['caption'];
    $blog_category = $_SESSION['blog_category'];
    $blog_image = $_POST['blog_img'];
    
    $file_name = $_FILES['blog_img']['name'];
    $file_temp_name = $_FILES['blog_img']['tmp_name'];
    
    $extension = explode('.', $file_name);
    $allowed = ['jpg', 'png', 'jpeg'];
    $file_extension = end($extension);
    
    $new_name = substr(str_shuffle('qqwertyuioplkjhgfdsazxcvbnmpoiuytrewasdfghjkmnbvcxz'),0,10);
    $img_name = $new_name . '.' . $file_extension;
    $path = 'passport/' . $img_name;
    $status = 1;

    if(empty($blog_title) || empty($blog_message)){
        echo "<script>window.alert('Title or Message cannot be empty!!')</script>";
    }else{
        $sql4 = "UPDATE blog_table SET blog_category=:blog_category, blog_message=:blog_message, blog_title=:blog_title, blog_caption=:blog_caption WHERE blog_id=:blog_id";
        $stmt4 = $conn -> prepare($sql4);

        $stmt4 -> bindParam(':blog_id', $blog_id);
        $stmt4 -> bindParam(':blog_title', $blog_title);
        $stmt4 -> bindParam(':blog_category', $blog_category);
        $stmt4 -> bindParam(':blog_message', $blog_message);
        $stmt4 -> bindParam(':blog_caption', $blog_caption);

        $res4 = $stmt4 -> execute();

        
        if($res4){
            echo "updated successfully";
            header("location: super-admin.php");
        }
        else{
            echo "An error occured!!";
        }
        // Sessions for blog_table


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
    <title>Edit_Posts</title>

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
</head>
<body>
    <center>
        <br><br><br>
        <h1>Edit Post</h1>
        <br><br><br>
    <form action="" method="post" enctype="multipart/form-data">
        <select name="cat" id="">
            <option value=""><?php echo $_SESSION['blog_category'];?></option>
            <?php  
            $sel_sql = "SELECT * FROM categories ORDER BY cat_id ASC";
            $sel_stmt = $conn -> prepare($sel_sql);
            
            $sel_stmt -> execute();
            while($row = $sel_stmt -> fetch(PDO::FETCH_ASSOC)){
                $cat_id = $row['cat_id'];
                $cat_name = $row['cat_name'];
               ?>
            <option value="<?php echo $cat_name ?>"><?php echo $cat_name ?></option>
            <?php  }?>
        </select>
        <br><br>
        <input type="text" name="title" placeholder="Title" value="<?php echo $_SESSION['blog_title'];?>"><br><br>
        <input type="text" name="caption" placeholder="caption" value="<?php echo $_SESSION['blog_caption'];?>"><br><br>
        <input type="file" name="blog_img" id="blog_img" accept=".jpg, .png, .jpeg" value="<?php echo $_SESSION['blog_image'];?>"><br>
        <br>
        <textarea name="message" id="" cols="50" rows="20"><?php echo $_SESSION['blog_message'];?></textarea>
        <br>
        <input type="submit" name="submit" id="" value="Update">
    </form>
    </center>
</body>
</html>