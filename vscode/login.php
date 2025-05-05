<?php
session_start();
include 'database.php'; 
$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

   
    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and Password are required!'); window.location.href='login.html';</script>";
        exit();
    }

    $query = "SELECT * FROM users WHERE email = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
        
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                $user_id = $user['id'];
                $login_time = date('H:i:s');
                $login_date = date('Y-m-d');

                $insert_session = "INSERT INTO user_session (user_id, login_time, login_date) VALUES (?, ?, ?)";
                if ($stmt_insert = mysqli_prepare($conn, $insert_session)) {
                    mysqli_stmt_bind_param($stmt_insert, "iss", $user_id, $login_time, $login_date);
                    if (!mysqli_stmt_execute($stmt_insert)) {
                        echo "<script>alert('Error logging your session. Please try again later.'); window.location.href='login.html';</script>";
                        exit();
                    }
                    mysqli_stmt_close($stmt_insert);
                } else {
                    echo "<script>alert('Error preparing session log. Please try again later.'); window.location.href='login.html';</script>";
                    exit();
                }

                header("Location: courses.php");
                exit();
            } else {
                echo "<script>alert('Incorrect password! Please try again.'); window.location.href='login.html';</script>";
                exit();
            }
        } else {
            echo "<script>alert('No user found with this email! Please sign up.'); window.location.href='signup.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error preparing login query. Please try again later.'); window.location.href='login.html';</script>";
        exit();
    }
}
?>
