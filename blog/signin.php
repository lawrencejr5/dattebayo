<?php

include "conn.php";
session_start();

if(isset($_POST["signin"])){
    // echo "<script>window.alert('connection successful')</script>";

    $username = $_POST['username'];
    $pass = $_POST['password'];

    if(empty($username) || empty($pass)){
        echo "<script>window.alert('Please Input All Fields')</script>";
    }
    else{
        $sql = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':username', $username);
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
                $_SESSION['username'] = $data['username'];
                $_SESSION['password'] = $data['password'];
                $_SESSION['date_created'] = $data['date_created'];
                
                header("location: dashboard.php");
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
    <title>Signin</title>
    <link rel="stylesheet" href="style.css">
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
            height: 370px;
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
        <form action="" method="post">
            <br>
        <h1>LOGIN</h1>
            <br>
            <div class="inp_grp">
                <input type="text" placeholder="Username" name="username">
                <input type="password" placeholder="Password" name="password">
                <input type="submit" name="signin" value="Sign In" id="submit">
            </div>
            <br>
            <span>If you don't have an account <a href="signup.php">Register</a> here</span>
            <br><br><br>
        </form>
    </center>
</body>
</html>