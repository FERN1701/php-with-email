<?php 
$pass = 0;
require_once "../central_control.php";
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if($email != false && $password != false){
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if($stmt->execute()){
        $fetch_info = $stmt->fetch(PDO::FETCH_ASSOC);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        $roles = $fetch_info['roles'];
        $id = $fetch_info['usersID'];
        if($status == "verified"){
            if($code != 0){
                header('Location: ../reset-code.php');
            }
        }else{
            header('Location: ../user-otp.php');
        }
        if($roles == 1 ){
            $title = "System Administrator";
        }
        if($roles == 2){
            header('Location: ../teacher/home.php');
        }
        if($roles == 3){
            header('Location: ../user/home.php');
        }
    }
}else{
    header('Location: ../login.php');
}