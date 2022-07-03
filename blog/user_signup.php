<?php
include "conn.php";
if(isset($_POST['submit'])){
 $username = $_POST['username'];
 $email = $_POST['email'];
 $age = $_POST['age'];
 $password = $_POST['password'];
 $cpassword = $_POST['cpassword'];

 if(empty($username) || empty($email) || empty($password) || empty($password) || empty($cpassword)){
     echo "<script>window.alert('Please Input All Fields')</script>";
 }
 else{
     if($password == $cpassword){

         $select = "SELECT email FROM user WHERE email = :email";
         $select_stmt = $conn -> prepare($select);
 
         $select_stmt -> bindParam(':email', $email);
 
         $select_result = $select_stmt -> execute();
 
         if($select_stmt -> rowcount() == 0){
 
             $sql = "INSERT INTO user(`username`, `email`, `age`, `password`) 
             VALUES(:username, :email, :age, :password)";
     
             $stmt = $conn -> prepare($sql);
      
            
             $stmt -> bindParam(':username', $username);
             $stmt -> bindParam(':email', $email);
             $stmt -> bindParam(':age', $age);
             $stmt -> bindParam(':password', $password);
     
            $res = $stmt -> execute();
     
             if($res){
                 header("location: user_signin.php");
             }
             else{
                 echo "<script>window.alert('insertion failed')</script>";
             }
             
 
         }
         else{
             echo "<script>window.alert('Email already exists')</script>";
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
            height: 600px;
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
        <h1 style="color: white; font-weight:900; font-size: 3rem;">Sign Up Page</h1>
        <form action="" method="post">
            <br><br>
            
            <div class="details">
                <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" style="text-transform: capitalize;"><br>
                <input type="email" name="email" id="email" placeholder="Email" autocomplete="off"><br>
                <input type="int" name="age" id="age" placeholder="Age" autocomplete="off"><br>
                <input type="password" name="password" id="password" placeholder="Password"><br>
                <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password"><br>
                <input type="submit" name="submit" id="submit" value="Sign Up"><br>
            </div>
            <br>
            <div class="donot">
                <span>Already have an account with us </span> <a href="user_signin.php">Sign In</a><span> here</span>
            </div>
        </form>
    </center>
</body>
</html>