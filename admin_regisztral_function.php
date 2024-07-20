<?php
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "weatherblog";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['fullname']) && isset($_POST['username']) && isset($_POST['password'])) {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO admin (Felhasznalonev, Jelszo, Nev) VALUES ('$username', '$hashed_password', '$fullname')";

        if ($conn->query($sql) === TRUE) {
            header("Location: login_adminweatherblog.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
