<?php
session_start();
include 'database.php';  // Ensure $conn is your active DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Step 1: Check if user exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Login successful
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];

            // Optional: Insert login record into students table
            $insert = $conn->prepare("INSERT INTO students (email, password) VALUES (?, ?)");
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Only if storing!
            $insert->bind_param("ss", $email, $hashedPassword);
            $insert->execute(); // Optional, remove if not needed

            header('Location: index.html');
            exit;
        } else {
            echo 'Invalid credentials';
        }
    } else {
        echo 'No user found with this email';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login_CSS.css">
</head>
<body class="bg-light">

<!-- Model for login -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h4>Login to MeloStream</h4>
                </div>
                <div class="card-body">
                    <form id="loginForm" method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <div class="mt-3 text-center">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
