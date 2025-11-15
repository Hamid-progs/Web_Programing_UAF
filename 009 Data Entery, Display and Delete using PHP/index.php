<?php
$servername = "localhost";
$username   = "root";
$password   = "Hamid@1122";
$database   = "bscse_2023_27";

$con = mysqli_connect($servername, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reg'])) {
    $regno = mysqli_real_escape_string($con, $_POST['reg']);
    $nm    = mysqli_real_escape_string($con, $_POST['n']);
    $email = mysqli_real_escape_string($con, $_POST['em']);
    $dob   = mysqli_real_escape_string($con, $_POST['dob']);

    $q = "INSERT INTO student (regno, name, email, dob)
          VALUES ('$regno', '$nm', '$email', '$dob')";
    $r = mysqli_query($con, $q);

    if (!$r) {
        echo "Error inserting data: " . mysqli_error($con);
    } else {
        header('Location: index.php');
        exit();
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
        <h1 style="text-align: center;">Student Information</h1>
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

    <form action="delete_multiple.php" method="post">
        <table width="80%" border="1" align="center" cellspacing="0" cellpadding="8">
            <tr>
                <th>Select</th>
                <th>Sr#</th>
                <th>Registration No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date of Birth</th>
                <th>Action</th>
            </tr>
            <?php
            $q  = "SELECT * FROM student ORDER BY id DESC";
            $rs = mysqli_query($con, $q);
            $c  = 1;
            while ($row = mysqli_fetch_assoc($rs)) {
                $id = (int)$row['id'];
                echo "<tr align='center'>
                        <td><input type='checkbox' name='select[]' value='{$id}'></td>
                        <td>{$c}</td>
                        <td>" . htmlspecialchars($row['regno']) . "</td>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['dob']) . "</td>
                        <td><a href='delete.php?id={$id}' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a></td>
                      </tr>";
                $c++;
            }
            ?>
        </table>

        <div align="center" style="margin-top:20px;">
            <input type="submit" name="delete_multiple" value="Delete Selected">
        </div>
    </form>
</body>
</html>
