<?php

include "conn.php";
session_start();

if(!isset($_SESSION['secret'])){
    header('location:s-user-login.php');
}

$sql = "SELECT * FROM users";
$stmt = $conn -> prepare($sql);
$stmt -> execute();

if(isset($_POST['delete'])){
    $id = $_POST['delete'];

    $del_sql = "DELETE FROM users WHERE id = :id";
    $del_stmt = $conn -> prepare($del_sql);
    $del_stmt -> bindParam(':id', $id);
    $res = $del_stmt -> execute();

    if($res){
        header("location: allaccounts.php");
    }
    else{
        echo "<script>window.alert('not deleted')</script>";
    }
}


?>

<style>
body{
    font-family: arial, sans-serif;
    background: grey;
    background-size: cover;
    background-position: fixed;
    color: white;
    /* font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif; */
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 60%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: tomato;
  color: black;
}
button{
    width: 100px;
    height: 30px;
    color: white;
    border: none;
    border-radius: 5px;
}
</style>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Accounts</title>
</head>
<body>
    <center>
    <h1>All Accounts</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
       
        <tbody>
            <?php if($stmt->rowcount()===0){ ?>
            <tr>
                <td>NOTHING HERE</td>
                <td>NOTHING HERE</td>
                <td>NOTHING HERE</td>
                <td>NOTHING HERE</td>
            </tr>
            <?php }else{
                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                    $username = $row['username'];
                    $email = $row['email'];
                    $rowid = $row['id'];?>
            <tr>
                <td><?php echo $username;?></td>
                <td><?php echo $email;?></td>
                <td><a href="editaccount.php?id=<?php echo $row['id'];?>"><button style="background: blue;">Edit</button></a></td>
                <td><br><form action="" method="post"><button style="background: red;" type="submit" name="delete" value="<?= $rowid;?>">Delete</button></form></td>
            </tr>
            <?php }
            }?>
        </tbody>
        
    </table>

    <p>
        <a style="color: tomato; font-weight:600; font-size: 20px; text-decoration: none; " href="super-admin.php">Go back to homepage</a>
        ----------
        <a style="color: tomato; font-weight:600; font-size: 20px; text-decoration: none; " href="signup.php">Add New Admin</a>
    </p>
    </center>


</body>
</html>