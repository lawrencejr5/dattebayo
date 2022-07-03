<?php

include "conn.php";
session_start();


if(!isset($_SESSION['secret'])){
    header('location:s-user-login.php');
}
$id = $_GET['id'];

$sql5 = "SELECT * FROM users WHERE id = :id";
$stmt5 = $conn -> prepare("$sql5");
$stmt5 -> bindParam(':id', $id);
$stmt5 -> execute();

while($row = $stmt5 -> fetch(PDO::FETCH_ASSOC)){
    $usernamerow = $row['username'];
    $emailrow = $row['email'];
}

echo $usernamerow;
if(isset($_POST['submit'])){
    $id = $_GET['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    if(empty($username) || empty($email)){
        echo "<script>window.alert('Please Input All Fields')</script>";
    }
    else{
      
        $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";

        $stmt = $conn -> prepare($sql);
    
        
        $stmt -> bindParam(':username', $username);
        $stmt -> bindParam(':email', $email);
        $stmt -> bindParam(':id', $id);

        $res = $stmt -> execute();

        if($res){
            $_SESSION['created'] = 'Account Updated Successfully';
            header("location: allaccounts.php");
        }
        else{
            echo "<script>window.alert('insertion failed')</script>";
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
    <title>Update</title>
</head>
<body>
    <h1>Edit Account</h1>
    <form action="" method="post">
        <input type="text" name="username" placeholder="username" value="<?php echo $usernamerow; ?>">
        <input type="email" name="email" placeholder="email" value="<?php echo $emailrow; ?>">
        <input type="submit" name="submit" value="Update">
    </form>
</body>
</html>