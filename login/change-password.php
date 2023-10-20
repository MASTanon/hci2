<?php

$msg = "";

include 'config.php';

if (isset($_GET['reset'])) {
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['reset']}'")) > 0) {
        if (isset($_POST['submit'])) {
            $password = mysqli_real_escape_string($conn, md5($_POST['password']));
            $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));

            if ($password === $confirm_password) {
                $query = mysqli_query($conn, "UPDATE users SET password='{$password}', code='' WHERE code='{$_GET['reset']}'");

                if ($query) {
                    header("Location: index.php");
                }
            } else {
                $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match.</div>";
            }
        }
    } else {
        $msg = "<div class='alert alert-danger'>Reset Link do not match.</div>";
    }
} else {
    header("Location: forgot-password.php");
}

?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Login Form</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Login Form" />

    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />

    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>

    <style>
        .password-container {
            position: relative;
        }

        .password, .confirm-password {
            padding-right: 40px; /* Adjust this value as needed */
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px; /* Adjust this value as needed */
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>

<section class="w3l-mockup-form">
    <div class="container">
        <div class="workinghny-form-grid">
            <div class="main-mockup">
                <div class="alert-close">
                    <span class="fa fa-close"></span>
                </div>
                <div class="w3l_form align-self">
                    <div class="left_grid_info">
                        <img src="images/image3.svg" alt="">
                    </div>
                </div>
                <div class="content-wthree">
                    <h2>Change Password</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                    <?php echo $msg; ?>
                    <form action="" method="post">
                        <div class="password-container">
                            <input type="password" class="password" name="password" placeholder="Enter Your Password" required>
                            <i class="fa fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                        <div class="password-container">
                            <input type="password" class="confirm-password" name="confirm-password" placeholder="Enter Your Confirm Password" required>
                            <i class="fa fa-eye password-toggle" id="toggleConfirmPassword"></i>
                        </div>
                        <button name="submit" class="btn" type="submit">Change Password</button>
                    </form>
                    <div class="social-icons">
                        <p>Back to! <a href="index.php">Login</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const passwordInput = document.querySelector(".password");
    const confirmInput = document.querySelector(".confirm-password");
    const togglePasswordButton = document.getElementById("togglePassword");
    const toggleConfirmPasswordButton = document.getElementById("toggleConfirmPassword");

    togglePasswordButton.addEventListener("click", function () {
        togglePasswordVisibility(passwordInput, togglePasswordButton);
    });

    toggleConfirmPasswordButton.addEventListener("click", function () {
        togglePasswordVisibility(confirmInput, toggleConfirmPasswordButton);
    });

    function togglePasswordVisibility(input, button) {
        if (input.type === "password") {
            input.type = "text";
            button.classList.remove("fa-eye");
            button.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            button.classList.remove("fa-eye-slash");
            button.classList.add("fa-eye");
        }
    }
</script>

</body>

</html>