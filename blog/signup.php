<?php

include "conn.php";

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if(empty($username) || empty($email) || empty($password) || empty($cpassword)){
        echo "<script>window.alert('Please Input All Fields')</script>";
    }
    else{
        if($password == $cpassword){

            $select = "SELECT username FROM users WHERE username = :username";
            $select_stmt = $conn -> prepare($select);
    
            $select_stmt -> bindParam(':username', $username);
    
            $select_result = $select_stmt -> execute();
    
            if($select_stmt -> rowcount() == 0){
    
                $sql = "INSERT INTO users( `username`, `email`, `password`) 
                VALUES(:username, :email, :password)";
        
                $stmt = $conn -> prepare($sql);
         
               
                $stmt -> bindParam(':username', $username);
                $stmt -> bindParam(':email', $email);
                $stmt -> bindParam(':password', $password);
        
               $res = $stmt -> execute();
        
                if($res){
                    $_SESSION['created'] = 'Account Created Successfully';
                    header("location: signin.php");
                }
                else{
                    echo "<script>window.alert('insertion failed')</script>";
                }
                
    
            }
            else{
                echo "<script>window.alert('Username already exists')</script>";
            }
    
           
     
        }
         else{
            echo "<script>window.alert('password not matched')</script>";
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
    <title>Signup</title>
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
            height: 420px;
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
<body> <center>
    <h1>REGISTER</h1>
    <form action="" method="post">
       
        <input type="text" name="username" placeholder="username">
        <input type="email" name="email" placeholder="email">
        <input type="password" name="password" placeholder="password">
        <input type="password" name="cpassword" placeholder="confirm password">
        <br><br>
        <input type="submit" name="submit" value="register" id="submit">
        <br><br>
        <span>Already have an account <a href="signin.php">Sign in</a> here</span>
        <br><br><br>
    </form>
    </center>

    
</body>
</html>