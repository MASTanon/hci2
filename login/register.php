<?php
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: welcome.php");
        die();
    }

    
    require 'vendor/autoload.php';

    include 'config.php';
    $msg = "";

    if (isset($_POST['submit'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));
        $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));
        $code = mysqli_real_escape_string($conn, md5(rand()));

        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
            $msg = "<div class='alert alert-danger'>{$email} - This email address has been already exists.</div>";
        } else {
            if ($password === $confirm_password) {
                $sql = "INSERT INTO users (name, email, password, code) VALUES ('{$name}', '{$email}', '{$password}', '{$code}')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "<div style='display: none;'>";
                    
                    $mail = new PHPMailer(true);

                    try {
                        
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
                        $mail->isSMTP();                                            
                        $mail->Host       = 'smtp.gmail.com';                     
                        $mail->SMTPAuth   = true;                                   
                        $mail->Username   = 'markanthonystanon17@gmail.com';                     
                        $mail->Password   = 'xfun rkiq qizv daxf';                               
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
                        $mail->Port       = 465;                                    

                        
                        $mail->setFrom('markanthonystanon17@gmail.com');
                        $mail->addAddress($email);

                        
                        $mail->isHTML(true);                                  
                        $mail->Subject = 'no reply';
                        $mail->Body    = 'Here is the verification link <b><a href="http://localhost/login/?verification='.$code.'">http://localhost/login/?verification='.$code.'</a></b>';

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                    echo "</div>";
                    $msg = "<div class='alert alert-info'>We've send a verification link on your email address.</div>";
                } else {
                    $msg = "<div class='alert alert-danger'>Something wrong went.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match</div>";
            }
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
                        <img src="images/image2.svg" alt="">
                    </div>
                </div>
                <div class="content-wthree">
                    <h2>Register Now</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
                    <?php echo $msg; ?>
                    <form action="" method="post">
                        <input type="text" class="name" name="name" placeholder="Enter Your Name" value="<?php if (isset($_POST['submit'])) { echo $name; } ?>" required>
                        <input type="email" class="email" name="email" placeholder="Enter Your Email" value="<?php if (isset($_POST['submit'])) { echo $email; } ?>" required>
                        
                        <!-- Password Input with Eye Icon Toggle -->
                        <div class="password-container">
                            <input type="password" class="password" name="password" placeholder="Enter Your Password" required>
                            <i class="fa fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                        
                        <!-- Confirm Password Input with Eye Icon Toggle -->
                        <div class="password-container">
                            <input type="password" class="confirm-password" name="confirm-password" placeholder="Enter Your Confirm Password" required>
                            <i class="fa fa-eye password-toggle" id="toggleConfirmPassword"></i>
                        </div>
                        
                        <button name="submit" class="btn" type="submit">Register</button>
                    </form>
                    <div class="social-icons">
                        <p>Have an account! <a href="index.php">Login</a>.</p>
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