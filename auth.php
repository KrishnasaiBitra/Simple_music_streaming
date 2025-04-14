<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../includes/db_connect.php';
session_start();

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'register') {
        // Registration
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        
        // Check if email exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $response['message'] = 'Email already registered';
            echo json_encode($response);
            exit;
        }
        
        // Insert new user
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            $user_id = mysqli_insert_id($conn);
            $token = bin2hex(random_bytes(16));
            
            // Set session
            $_SESSION['user'] = [
                'id' => $user_id,
                'username' => $username,
                'email' => $email
            ];
            
            $response['success'] = true;
            $response['message'] = 'Registration successful';
            $response['token'] = $token;
            $response['user'] = $_SESSION['user'];
        } else {
            $response['message'] = 'Registration failed';
        }
    } 
    elseif ($action === 'login') {
        // Login
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];
        
        $result = mysqli_query($conn, "SELECT id, username, email, password FROM users WHERE email = '$email'");
        if (mysqli_num_rows($result) === 0) {
            $response['message'] = 'User not found';
            echo json_encode($response);
            exit;
        }
        
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $token = bin2hex(random_bytes(16));
            
            // Set session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email']
            ];
            
            $response['success'] = true;
            $response['message'] = 'Login successful';
            $response['token'] = $token;
            $response['user'] = $_SESSION['user'];
        } else {
            $response['message'] = 'Incorrect password';
        }
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Logout
    session_unset();
    session_destroy();
    $response['success'] = true;
    $response['message'] = 'Logged out successfully';
}

echo json_encode($response);
?>