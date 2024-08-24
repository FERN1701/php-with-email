<link rel="stylesheet" href="vendor/sweetalert2/dist/sweetalert2.min.css">
<?php

// Creating of Account
if (isset($_SESSION['STATUS']) && $_SESSION['STATUS'] ===  "ACC_SUCCESS") {
    echo "<script>";
    echo "Swal.fire({
              title: 'Create Account Succesfully',
              text: 'You have succesfully created your account! Please check your gmail address for OTP codes',
              icon: 'success',
              confirmButtonText: 'OK'
            });";
    echo "</script>";
    $_SESSION['STATUS'] = "";
}
?>
<script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script>