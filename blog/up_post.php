<?php

include "conn.php";
session_start();

if(!isset($_SESSION['secret'])){
    header('location:signin.php');
}
?>

    
<?php

if(isset($_POST['submit'])){
    $up_message = $_POST['message'];
    $up_category = $_POST['cat'];
    $up_title = $_POST['title'];

    // up images variables

    $file_name = $_FILES['up_img']['name'];
    $file_temp_name = $_FILES['up_img']['tmp_name'];

    $extension = explode('.', $file_name);
    $allowed = ['jpg', 'png', 'jpeg'];
    $file_extension = end($extension);

    $new_name = substr(str_shuffle('qqwertyuioplkjhgfdsazxcvbnmpoiuytrewasdfghjkmnbvcxz'),0,10);
    $img_name = $new_name . '.' . $file_extension;
    $path = 'passport/' . $img_name;
    $status = 1;

    

    // up images variables ends

    

    if(empty($up_title) || empty($up_message)){
        echo "<script>window.alert('Title or Message cannot be empty!!')</script>";
    }else{
        $sql = "INSERT INTO up_table(up_category, up_message, up_title) VALUES(:up_category, :up_message, :up_title)";
        $stmt = $conn -> prepare($sql);

        $stmt -> bindParam(':up_category', $up_category);
        $stmt -> bindParam(':up_title', $up_title);
        $stmt -> bindParam(':up_message', $up_message);

        $res = $stmt -> execute();

        if($res){
            echo "inserted successfully";
            header("location: admin.php");
        }
        else{
            echo "An error occured!!";
        }
        // Sessions for up_table
        $squel = "SELECT * FROM up_table";
        $stmt2 = $conn -> prepare($squel);
        $stmt2 -> execute();
        $stmt2 -> fetch(PDO::FETCH_ASSOC);
        while($data = $stmt2 -> fetch()){
            $_SESSION['up_id'] = $data['up_id'];
            $_SESSION['up_status'] = $data['up_status'];
        }

        $up_id = $_SESSION['up_id'];


        if(in_array($file_extension, $allowed)){
        if(move_uploaded_file($file_temp_name, $path)){
            $file_sql = "UPDATE up_table SET up_image = :up_img, up_status = :up_status WHERE up_id = :up_id";
            $file_stmt = $conn -> prepare($file_sql);

            $file_stmt -> bindParam(':up_id', $up_id);
            $file_stmt -> bindParam(':up_status', $status);
            $file_stmt -> bindParam(':up_img', $img_name);

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
    <title>Up_Posts</title>
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
        height: 60px;
        outline: none;
        border: 1px solid tomato;
    }
    </style>
</head>
<body>
    <center>
        <br><br><br>
        <h1>POST UPCOMING</h1>
        <br><br><br>
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
        </select><br><br>
        <input type="text" name="title" placeholder="Title"><br><br>
        <textarea name="message" id="" cols="30" rows="20" placeholder="Caption"></textarea><br><br>
        <input type="file" name="up_img" id="up_img" accept=".jpg, .png, .jpeg">
        <br>
        <input type="submit" name="submit" id="" value="send">
    </form>
    </center>
</body>
</html>