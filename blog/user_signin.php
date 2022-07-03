<?php

include "conn.php";
session_start();

if(isset($_POST["submit"])){
    // echo "<script>window.alert('connection successful')</script>";

    $email = $_POST['email'];
    $pass = $_POST['password'];

    if(empty($email) || empty($pass)){
        echo "<script>window.alert('Please Input All Fields')</script>";
    }
    else{
        $sql = "SELECT * FROM user WHERE email = :email AND password = :password";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':email', $email);
        $stmt -> bindParam(':password', $pass);
    
        $stmt -> execute();
        $row = $stmt -> rowCount();
    
        if($row > 0){
            $stmt -> setfetchmode(PDO::FETCH_ASSOC);
            while($data = $stmt->fetch()){ 
                
            // sessions
                $_SESSION['secret'] = "lawrencejroputaifeanyilawrence";
                $_SESSION['id'] = $data['id'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['age'] = $data['age'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['password'] = $data['password'];
                
                header("location: blog_page/index.php");
                echo "<script>window.alert('Login Successful')</script>";
            }
        }
        else{
            echo "<script>window.alert('Account does not exist')</script>";
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
    <title>User_Signup</title>


    <style>
        body{
            background: tomato;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        form{
            background: white;
            width: 800px;
            height: 350px;
            border-radius: 10px;
            margin-top: 5rem;
        }
        input{
            margin-top: 1rem;
            width: 600px;
            height: 50px;
            padding-left: 1rem;
            border-top: none;
            border-left: none;
            border-right: none;
            border-bottom: 1px solid black;
            outline: none;
            font-size: 1rem;
        }
        #submit{
            border: 1px solid grey;
            border-radius: 5px;
            background: tomato;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
        }
       #submit:hover{
            background: white;
            color: tomato;
            border: 2px solid black;
       }
    </style>
</head>
<body>
    <center>
        <br>
        <h1 style="color: white; font-weight:900; font-size: 3rem;">Sign In Page</h1>
        <form action="" method="post">
            <br><br>
            
            <div class="details">
                <input type="email" name="email" id="email" placeholder="Email" autocomplete="off"><br>
                <input type="password" name="password" id="password" placeholder="Password"><br>
                <input type="submit" name="submit" id="submit" value="Sign Up"><br>
            </div>
            <br>
            <div class="donot">
                <span>Don't have an account with us </span> <a href="user_signup.php">Sign Up</a><span> here</span>
            </div>
        </form>
    </center>
</body>
</html>