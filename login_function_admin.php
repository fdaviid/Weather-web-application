<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weatherblog";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted_username = $_POST["uname"];
    $submitted_password = $_POST["psw"];
    //admin adatainak ellenőrzése bejelentkezés során
    $query = "SELECT AdminID, Jelszo FROM admin WHERE Felhasznalonev=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $submitted_username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($admin_id, $hashed_password_from_db);
        $stmt->fetch();

        if (password_verify($submitted_password, $hashed_password_from_db)) {
            $_SESSION["loggedin"] = true;
            $_SESSION["admin_id"] = $admin_id;
            header("Location: admin_panel.php");
            exit;
        } else {
            header("Location: index2.php");
            exit;
        }
    } else {
        header("Location: index2.php");
        exit;
    }
}

$conn->close();
