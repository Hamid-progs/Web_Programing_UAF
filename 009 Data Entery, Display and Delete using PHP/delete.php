<?php
$servername = "localhost";
$username   = "root";
$password   = "Hamid@1122";
$database   = "bscse_2023_27";

$con = mysqli_connect($servername, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $q = "DELETE FROM student WHERE id = $id";
        $r = mysqli_query($con, $q);

        if ($r) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($con);
        }
    } else {
        echo "Invalid record ID!";
    }
} else {
    echo "Invalid request!";
}
?>
