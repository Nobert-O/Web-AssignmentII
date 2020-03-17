<?php
if (isset($_POST['signup-submit'])){
    require 'db_functions.php';
    $name  = $_POST['username'];
    $password  = $_POST['pass'];


    if(empty($name) || empty($password)){
        header("Location: ../Sign-in.php?error=emptyfields");
        exit();
    }
    else{
        $sql = "SELECT * FROM users WHERE Username = ?;";
        $stmt = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($stmt,$sql)){
          header("Location: ../Sign-in.php?error=sqlerror");
          exit(); 
        }else{
            mysqli_stmt_bind_param($stmt,"ss",$name,$name);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_result($stmt);
            if ($row = mysqli_fetch_assoc($result)){
                $pwdCheck  = password_verify($password,$row['Userpassword']);
                if($pwdCheck == false){
                    header("Location: ../index.php?wrongpwd");
                    exit();
                }
                elseif($pwdCheck == true){
                    session_start();
                    $_SESSION['userid'] = $row['id_user'];
                    $_SESSION['userUname'] = $row['Username'];
                    header("Location: /Sign-in.php?login=sucess");
                    exit();  
                }
            }
            else {
                header("Location: ../Sign-in.php?error=nouser")
            }
        }
    }

}else {
    header("Location: index.php");
    exit();
}
