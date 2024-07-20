<?php
session_start();

//bejelentkezés ellenőrzése   jhonyadmin, 123123
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login_page.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weatherblog";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["title"]) && isset($_POST["content"]) && isset($_POST["keywords"]) && isset($_FILES["image"])) {
        $title = $_POST["title"];
        $content = $_POST["content"];
        $keywords = $_POST["keywords"];
        $image = $_FILES["image"];


        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image["name"]);
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            if (isset($_SESSION["admin_id"])) {
                $admin_id = $_SESSION["admin_id"];

                //SQL lekérdezés a bejegyzés beszúrására
                $sql = "INSERT INTO bejegyzes (Cim, Datum, Kulcsszavak, BejegyzesSzoveg, Kep, AdminID)
                        VALUES (?, NOW(), ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $title, $keywords, $content, $target_file, $admin_id);

                if ($stmt->execute()) {
                    echo "A bejegyzés sikeresen létrehozva.";
                } else {
                    echo "Hiba történt a bejegyzés létrehozása során: " . $conn->error;
                }

                $stmt->close();
            } else {
                echo "Admin ID is not set in session.";
            }
        } else {
            echo "Hiba történt a képfájl feltöltése során.";
        }
    } else {
        echo "Hiányzó adatok.";
    }
}

$conn->close();
