<?php
if (isset($_POST['signup-submit'])){
    require 'db_functions.php';

    $username = $_POST['name'];
    $email = $_POST['mail'];
    $password = $_POST['pass'];
    $passwordrepeat = $_POST['repass'];


    if(empty($username)|| empty($email)|| empty($password) ||empty($passwordrepeat)){
        header("Location: ../signup.php?error=emptyfields&name=".$username."&mail=".$email);
        exit();  
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-ZO-9]*$/",$username)){
            header("Location: ../signup.php?error=invalidmail&name");
            exit();
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../signup.php?error=invalidmail&name=".$username);
        
        exit();
    }
    // elseif(!preg_match("/^[a-zA-Z0-9]*$/",$username)){
    //     header("Location: ../signup.php?error=invalidname&mail=".$email);
    //     exit();
    // }

    elseif($password !== $passwordrepeat){
        echo 'enter email';

    }
    else{
        $sql = "SELECT Username FROM users WHERE Username =?";
        $stmt = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($stmt,$sql)){
            header("Location: ../signup.php?error=sqlerror");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt,"s",$username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultcheck = mysqli_stmt_num_rows($stmt);
            if($resultcheck > 0 ){
                header("Location: ../signup.php?error=usertaken&mail=".$email);
                exit();
            }
            else{
                $sql = "INSERT INTO users (Username,Emailuser,Userpassword) VALUES (?,?,?)";
                $stmt = mysqli_stmt_init($connection);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    header("Location: ../signup.php?error=sqlerror");
                    exit();
                }
                else {
                    $hashedpwd = password_hash($password,PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt,"sss",$username,$email,$hashedpwd);
                    mysqli_stmt_execute($stmt);
                    header("Location: index.php");
                    exit();
                }
            }
        } 
    }
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

}else {
    header("Location: index.php");
    exit();
}