<?php
    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: welcome.php");
        die();
    }

    include 'config.php';
    $msg = "";

    if (isset($_GET['verification'])) {
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['verification']}'")) > 0) {
            $query = mysqli_query($conn, "UPDATE users SET code='' WHERE code='{$_GET['verification']}'");
            
            if ($query) {
                $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
            }
        } else {
            header("Location: index.php");
        }
    }

    if (isset($_POST['submit'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));

        $sql = "SELECT * FROM users WHERE email='{$email}' AND password='{$password}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            if (empty($row['code'])) {
                $_SESSION['SESSION_EMAIL'] = $email;
                header("Location: welcome.php");
            } else {
                $msg = "<div class='alert alert-info'>First verify your account and try again.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
        }
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

        .password {
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
                        <img src="images/image.svg" alt="">
                    </div>
                </div>
                <div class="content-wthree">
                    <h2>Login Now</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                    <?php echo $msg; ?>
                    <form action="" method="post">
                        <input type="email" class="email" name="email" placeholder="Enter Your Email" required>
                        <div class="password-container">
                            <input type="password" class="password" name="password" id="password" placeholder="Enter Your Password" required>
                            <i class="fa fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                        <p><a href="forgot-password.php" style="margin-bottom: 15px; display: block; text-align: right;">Forgot Password?</a></p>
                        <button name="submit" name="submit" class="btn" type="submit">Login</button>
                    </form>
                    <div class="social-icons">
                        <p>Create Account! <a href="register.php">Register</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const passwordInput = document.getElementById("password");
    const togglePasswordButton = document.getElementById("togglePassword");

    togglePasswordButton.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            togglePasswordButton.classList.remove("fa-eye");
            togglePasswordButton.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            togglePasswordButton.classList.remove("fa-eye-slash");
            togglePasswordButton.classList.add("fa-eye");
        }
    });
</script>

</body>

</html>