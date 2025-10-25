<?php
$servername = "localhost";
$username = "root"; 
$password = "Hamid@1122";     
$database = "user_portal";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// -------------------- REGISTRATION --------------------
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password']; // <-- plain password (no hashing)

    // handle picture upload
    $picName = $_FILES['pic']['name'];
    $picTmp = $_FILES['pic']['tmp_name'];
    $picPath = "uploads/" . basename($picName);
    move_uploaded_file($picTmp, $picPath);

    // insert into database
    $sql = "INSERT INTO users (name, email, password, picture)
            VALUES ('$name', '$email', '$pass', '$picPath')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        echo "<script>alert('🎉 Registration successful! Your ID to login is: $last_id');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// -------------------- LOGIN --------------------
if (isset($_POST['login'])) {
    $id = $_POST['login_id'];
    $password = $_POST['login_pass'];

    $sql = "SELECT * FROM users WHERE id='$id' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<script>alert('👋 Welcome, " . $row['name'] . "!');</script>";
    } else {
        echo "<script>alert('❌ Invalid ID or Password!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connections | Get Connected</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header Section -->
    <div id="header">
        <img src="./assets/logo.png" alt="logo" height="100px" width="100px">
        <div id="h-div">
            <h1 id="h-font">University of Agriculture Faisalabad</h1>
        </div>
    </div>

    <!-- User Login Section -->
    <div class="form-container">
        <div class="form-heading">User Login</div>
        <form action="#" method="post">
            <div class="form-row">
                <label for="login-id">Your ID:</label>
                <input type="text" id="login-id" name="login_id" required>
            </div>
            <div class="form-row">
                <label for="login-pass">Your Password:</label>
                <input type="password" id="login-pass" name="login_pass" required>
            </div>
            <div class="form-row button">
                <input type="submit" name="login" value="Login" class="btn">
            </div>
        </form>
    </div>

    <!-- User Registration Section -->
    <div class="form-container">
        <div class="form-heading">User Registration</div>
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <label for="name">Your Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-row">
                <label for="email">Your Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-row">
                <label for="password">Your Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-row">
                <label for="pic">Your Picture:</label>
                <input type="file" id="pic" name="pic" required>
            </div>
            <div class="form-row button">
                <input type="submit" name="register" value="Register" class="btn">
            </div>
        </form>
    </div>

    <!-- Footer -->
    <div id="footer">
        &copy; All rights reserved
    </div>
</body>
</html>
