<?php

$servername = "localhost";
$username   = "root";
$password   = "Hamid@1122";
$database   = "bscse_2023_27";


$con = mysqli_connect($servername, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $regno = $_POST['reg'];
    $nm    = $_POST['n'];
    $email = $_POST['em'];
    $dob   = $_POST['dob'];

    $q = "INSERT INTO student (regno, name, email, dob)
          VALUES ('$regno', '$nm', '$email', '$dob')";
    $r = mysqli_query($con, $q);

    if (!$r) {
        echo "Error inserting data: " . mysqli_error($con);
    } else {
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
</head>
<body>
    <form action="" method="post">
        <h1>Student Information</h1>
        <div style="width:80%; margin:50px">
            <div>
                <label>Registration No</label>
                <input type="text" name="reg" required>
            </div>
            <div>
                <label>Name</label>
                <input type="text" name="n" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="em" required>
            </div>
            <div>
                <label>Date of Birth</label>
                <input type="date" name="dob" required>
            </div>
            <div>
                <input type="submit" value="Save">
            </div>
        </div>
    </form>

    <table width="80%" border="1" align="center">
        <tr>
            <th>Sr#</th>
            <th>Registration No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Date of Birth</th>
        </tr>
        <?php
        
        $q  = "SELECT * FROM student";
        $rs = mysqli_query($con, $q);
        $c  = 1;
        while ($row = mysqli_fetch_assoc($rs)) {
            echo "<tr align='center'>
                    <td>{$c}</td>
                    <td>{$row['regno']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['dob']}</td>
                  </tr>";
            $c++;
        }
        ?>
    </table>
</body>
</html>
