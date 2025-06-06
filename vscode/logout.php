<?php
session_start();
include 'database.php'; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $logout_time = date("H:i:s");
    $logout_date = date("Y-m-d");

    $query = "UPDATE user_session SET logout_time = ?, logout_date = ? WHERE user_id = ? ORDER BY id DESC LIMIT 1";

    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssi", $logout_time, $logout_date, $user_id);
        $stmt->execute();
        
        if ($stmt->affected_rows === 0) {
            $insert_query = "INSERT INTO user_session (user_id, logout_time, logout_date) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            if ($insert_stmt) {
                $insert_stmt->bind_param("iss", $user_id, $logout_time, $logout_date);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
        }
        $stmt->close();
    }
}

session_unset();
session_destroy();
header("Location: homepage.html");
exit();
?>
