<link rel="stylesheet" href="vendor/sweetalert2/dist/sweetalert2.min.css">
<?php 
session_start();
error_reporting(0);
require "connection.php";
$email = "";
$name = "";
$errors = array();

        //<Mail> ---------------------------------------------------------------------------------------------------
                            use PHPMailer\PHPMailer\PHPMailer;
                            use PHPMailer\PHPMailer\SMTP;
                            use PHPMailer\PHPMailer\Exception;

                            //Load Composer's autoloader

                            require 'vendor/autoload.php';

                            function emails($email, $subject, $message){

                                
                                $mail = new PHPMailer(true);
                                
                                try {
                                    //Server settings
                                $mail->SMTPDebug = 0;                    //Enable verbose debug output
                                    $mail->isSMTP();                                            //Send using SMTP
                                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                                    $mail->Username   = 'argonfernando453@gmail.com';                     //SMTP username
                                    $mail->Password   = 'uyjjiovbpmoynysx';                               //SMTP password
                                    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                                    $mail->Port       = 587;                                         //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                                
                                    //Recipients
                                    $mail->setFrom('from@example.com', 'Sign up Verification');
                                    $mail->addAddress($email);     //Add a recipient
                                    //$mail->addReplyTo('info@example.com', 'Information');
                                    //$mail->addCC('cc@example.com');
                                    //$mail->addBCC('bcc@example.com');
                                
                                    //Attachments
                                    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                                
                                    //Content
                                    $mail->isHTML(true);                                  //Set email format to HTML
                                    //$mail->Subject = 'Here is the subject';
                                    //$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
                                    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                    $mail->Subject = $subject;
                                    $mail->Body    = "<fieldset><b> ".$message."</b></fieldset>";
                                
                                    if(!$mail->send()) { 
                                        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
                                    } else {
                                        $_SESSION['STATUS'] = "ACC_SUCCESS";
                                        $_SESSION['info'] = "We've sent an OTP Code on your Gmail <i> '$email'</i> thank you. ";
                                        echo "<script>window.location.href='user-otp.php'</script>";
                                    } 
                                } catch (Exception $e) {
                                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }
                            }
                            
                            function forgotpass($email, $subject, $message){

                                
                                $mail = new PHPMailer(true);
                                
                                try {
                                    //Server settings
                                $mail->SMTPDebug = 0;                    //Enable verbose debug output
                                    $mail->isSMTP();                                            //Send using SMTP
                                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                                    $mail->Username   = 'argonfernando453@gmail.com';                     //SMTP username
                                    $mail->Password   = 'uyjjiovbpmoynysx';                               //SMTP password
                                    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                                    $mail->Port       = 587;                                         //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                                
                                    //Recipients
                                    $mail->setFrom('from@example.com', 'Code for Password Reset');
                                    $mail->addAddress($email);     //Add a recipient
                                    //$mail->addReplyTo('info@example.com', 'Information');
                                    //$mail->addCC('cc@example.com');
                                    //$mail->addBCC('bcc@example.com');
                                
                                    //Attachments
                                    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                                
                                    //Content
                                    $mail->isHTML(true);                                  //Set email format to HTML
                                    //$mail->Subject = 'Here is the subject';
                                    //$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
                                    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                    $mail->Subject = $subject;
                                    $mail->Body    = "<fieldset><b> ".$message."</b></fieldset>";
                                
                                    if(!$mail->send()) { 
                                        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
                                    } else { 
                                        echo "<script>alert('Email Reset code sent, please check your gmail ');</script>
                                            <script>window.location.href=' reset-code.php'</script>";
                                    } 
                                } catch (Exception $e) {
                                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }
                            }
        //</Mail> ---------------------------------------------------------------------------------------------------

        //< external control> ---------------------------------------------------------------------------------------
        if(isset($_POST['signup'])){
            // Assuming you have a PDO connection established:
            try {
                
                $email = $_POST['email'];
                $password = $_POST['password'];
                $cpassword = $_POST['cpassword'];
        
                if ($password !== $cpassword) {
                    $errors['password'] = "Confirm password not matched!";
                }
        
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    $errors['email'] = "Email that you have entered is already exist!";
                }
        
                if (count($errors) === 0) {
                    $encpass = password_hash($password, PASSWORD_BCRYPT);
                    $code = rand(999999, 111111);
                    $status = "notverified";
        
        
                    $stmt = $pdo->prepare("INSERT INTO users (email, password, code, status) VALUES (:email, :password, :code, :status)");
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $encpass);
                    $stmt->bindParam(':code', $code);
                    $stmt->bindParam(':status', $status);
        
                    if ($stmt->execute()) {
                        $subject = "Email Verification Code";
                        $message = "Dear $email, <br>

                        
                        OTP Code: <b style='color: orange;'>$code</b>
                        <br>
                        ";
                        if(emails($email, $subject, $message)){
                            $info = "We've sent a verification code to your email - $email";
                            $_SESSION['info'] = $info;
                            $_SESSION['email'] = $email;
                            $_SESSION['password'] = $password;
                            header('location: user-otp.php');
                            exit();
                        }else{
                            $errors['otp-error'] = "Failed while sending code!";
                        }
                    
                    } else {
                        $errors['db-error'] = "Failed while inserting data into database!";
                    }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }


        if (isset($_POST['check'])) {
            $_SESSION['info'] = "";
        
            try {

                $otp_code = $_POST['otp'];
        
                $stmt = $pdo->prepare("SELECT * FROM users WHERE code = :otp_code");
                $stmt->bindParam(':otp_code', $otp_code);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    $fetch_data = $stmt->fetch(PDO::FETCH_ASSOC);
                    $fetch_code = $fetch_data['code'];
                    $email = $fetch_data['email'];
        
                    $stmt = $pdo->prepare("UPDATE users SET code = 0, status = 'verified' WHERE code = :fetch_code");
                    $stmt->bindParam(':fetch_code', $fetch_code);
        
                    if ($stmt->execute()) {
                        echo "<script>alert('Account verified')</script>";
        
                        $_SESSION['name'] = $fetch_data['name']; // Assuming 'name' is a column in your 'users' table
                        $_SESSION['email'] = $email;
        
                        $roles = $fetch_data['roles'];
        
                        if ($roles == 1) {
                            header('location: ./admin/index.php');
                        } elseif ($roles == 2) {
                            header('location: ./user1/index.php');
                        } else {
                            header('location: ./user2/index.php');
                        }
        
                        exit();
                    } else {
                        $errors['otp-error'] = "Failed while updating code!";
                    }
                } else {
                    $errors['otp-error'] = "You've entered incorrect code!";
        
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }


        if (isset($_POST['login'])) {
            try {
        
                $email = $_POST['email'];
                $password = $_POST['password'];
        
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
                    $fetch_pass = $fetch['password'];
        
                    if (password_verify($password, $fetch_pass)) {
                        $_SESSION['email'] = $email;
                        $status = $fetch['status'];
        
                        if ($status == 'verified') {
                                $_SESSION['email'] = $email;
                                        $_SESSION['password'] = $password;
                                        $roles = $fetch['roles'];
                                        if($roles == 1){
                                            header('location: ./admin/index.php');
                                        }elseif($roles == 2){
                                            header('location: ./user1/index.php');
                                        }else{
                                            header('location: ./user2/index.php');
                                        }
                        } else {
                            $info = "It's look like you haven't still verify your email - $email";
                                        $_SESSION['info'] = $info;
                                        header('location: user-otp.php');
                        }
                    } else {
                        $errors['email'] = "Incorrect email or password!";
                    }
                } else {
                    $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
        
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        if (isset($_POST['check-email'])) {
            try {
    
    
                $email = $_POST['email'];
        
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    $code = rand(999999, 111111);
        
                    $stmt = $pdo->prepare("UPDATE users SET code = :code WHERE email = :email");
                    $stmt->bindParam(':code', $code);
                    $stmt->bindParam(':email', $email);
        
                    if ($stmt->execute()) {
                        $subject = "Password Reset Code";
                        $message = "Dear $email, <br>
                        OTP Code: <b style='color: orange;'> $code </b>
                        <br><br>
                        
                        Best regards,<br>
                        
                        -Admin<br>
                        Fayeed Electronics Customer Support";
                        
                        if(forgotpass($email, $subject, $message)){
                            $info = "We've sent a passwrod reset otp to your email - $email";
                            $_SESSION['info'] = $info;
                            $_SESSION['email'] = $email;
                            header('location: reset-code.php');
                            exit();
                        }else{
                            $errors['otp-error'] = "Failed while sending code!";
                        }
                    } else {
                        $errors['db-error'] = "Something went wrong!";
                    }
                } else {
                    $errors['email'] = "This email address does not exist!";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        if (isset($_POST['check-reset-otp'])) {
            $_SESSION['info'] = "";
        
            try {
        
                $otp_code = $_POST['otp'];
        
                $stmt = $pdo->prepare("SELECT * FROM users WHERE code = :otp_code");
                $stmt->bindParam(':otp_code', $otp_code);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    $fetch_data = $stmt->fetch(PDO::FETCH_ASSOC);
                    $email = $fetch_data['email'];
                    $_SESSION['email'] = $email;
                    $info = "Please create a new password that you don't use on any other site.";
        
                    $_SESSION['info'] = $info;
                    header('location: new-password.php');
                    exit();
                } else {
                    $errors['otp-error'] = "You've entered incorrect code!";
        
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }


        if (isset($_POST['change-password'])) {
            $_SESSION['info'] = "";
        
            try {
                // ... (your PDO connection code)
        
                $password = $_POST['password'];
                $cpassword = $_POST['cpassword'];
        
                if ($password !== $cpassword) {
                    $errors['password'] = "Confirm password not matched!";
                } else {
                    $code = 0;
                    $email = $_SESSION['email'];
                    $encpass = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("UPDATE users SET code = :code, password = :password WHERE email = :email");
                    $stmt->bindParam(':code', $code);
                    $stmt->bindParam(':password', $encpass);
                    $stmt->bindParam(':email', $email);
        
                    if ($stmt->execute()) {
                        $info = "Your password changed. Now you can login with your new password.";
                        $_SESSION['info'] = $info;
                        header('Location: password-changed.php');
                    } else {
                        $errors['db-error'] = "Failed to change your password!";
        
                    }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

                if(isset($_POST['login-now'])){
                    header('Location: login.php');
                }
                
        //</external control> ---------------------------------------------------------------------------------------
        //<Querriesss> ---------------------------------------------------------------------------------------------------





        //</Querriesss> ---------------------------------------------------------------------------------------------------




    
?>
<script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script>