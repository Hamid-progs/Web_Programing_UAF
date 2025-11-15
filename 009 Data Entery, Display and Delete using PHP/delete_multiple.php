<?php
$servername = "localhost";
$username   = "root";
$password   = "Hamid@1122";
$database   = "bscse_2023_27";

$con = mysqli_connect($servername, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['delete_multiple']) && !empty($_POST['select'])) {
    $all = array_map('intval', $_POST['select']);
    $ids = implode(",", $all);

    $q = "DELETE FROM student WHERE id IN ($ids)";
    $r = mysqli_query($con, $q);

    if ($r) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting records: " . mysqli_error($con);
    }
} else {
    echo "<script>alert('No rows selected!'); window.location.href='index.php';</script>";
    exit();
}
?>
