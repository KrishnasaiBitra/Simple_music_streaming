<!DOCTYPE html>
<html>
<head>
    <title>Feedback Form</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: 600;
            color: #444;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            color: #28a745;
            font-weight: bold;
        }

        .error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <h2>Submit Your Feedback</h2>
    <form method="POST" action="">
        <label>Name:</label><br>
        <input type="text" name="name" required><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Feedback:</label><br>
        <textarea name="feedback" rows="4" cols="40" required></textarea><br>

        <input type="submit" name="submit" value="Submit Feedback">
    </form>

    <?php
    // Handle the form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $conn = new mysqli("localhost", "root", "", "webproject_2");

        if ($conn->connect_error) {
            die("<p class='error'>Connection failed: " . $conn->connect_error . "</p>");
        }

        $name     = $conn->real_escape_string($_POST['name']);
        $email    = $conn->real_escape_string($_POST['email']);
        $feedback = $conn->real_escape_string($_POST['feedback']);

        $sql = "INSERT INTO feedbacks (name, email, feedback) VALUES ('$name', '$email', '$feedback')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Feedback submitted successfully!</p>";
        } else {
            echo "<p class='error'>Error: " . $conn->error . "</p>";
        }

        $conn->close();
    }
    ?>
</body>
</html>
