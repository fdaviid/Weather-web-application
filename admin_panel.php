<?php
session_start();

//admin ellenorzése
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.html");
    exit;
}

//kijelentkezés
if (isset($_GET["logout"]) && $_GET["logout"] == true) {
    unset($_SESSION["loggedin"]);
    unset($_SESSION["admin_id"]);
    session_destroy();
    header("Location: index.html");
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

//admin adatainak lekérdezése
$admin_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : null;
if ($admin_id) {
    $stmt = $conn->prepare("SELECT Felhasznalonev FROM admin WHERE AdminID = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        //admin felhasználónév
        $row = $result->fetch_assoc();
        $loggedInUser = $row["Felhasznalonev"];
    } else {
        $loggedInUser = "Felhasználó";
    }
    $stmt->close();
} else {
    header("Location: index.html");
    exit;
}

//bejegyzés törlése
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"]) && isset($_POST["bejegyzes_id"])) {
    $bejegyzes_id = $_POST["bejegyzes_id"];
    $delete_sql = "DELETE FROM bejegyzes WHERE BejegyzesID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $bejegyzes_id);
    if ($stmt->execute()) {
        header("Location: admin_panel.php");
        exit;
    } else {
        echo "Hiba történt a törlés során: " . $conn->error;
    }
}

//bejegyzés módosítása
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"]) && isset($_POST["bejegyzes_id"])) {
    $bejegyzes_id = $_POST["bejegyzes_id"];
    $cim = $_POST["title"];
    $szoveg = $_POST["content"];
    $kulcsszavak = $_POST["keywords"];

    $update_sql = "UPDATE bejegyzes SET Cim=?, BejegyzesSzoveg=?, Kulcsszavak=? WHERE BejegyzesID=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $cim, $szoveg, $kulcsszavak, $bejegyzes_id);
    if ($stmt->execute()) {
        header("Location: admin_panel.php");
        exit;
    } else {
        echo "Hiba történt a frissítés során: " . $conn->error;
    }
}

//összes bejegyzés lekérdezése az adatbázisból
$sql = "SELECT BejegyzesID, Cim FROM bejegyzes";
$result = $conn->query($sql);
$bejegyzesek = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bejegyzesek[] = $row;
    }
}

//ha az URL-ben van edit érték, akkor szerkesztésre van szükség
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_sql = "SELECT * FROM bejegyzes WHERE BejegyzesID = ?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_result = $stmt->get_result();
    $edit_bejegyzes = $edit_result->fetch_assoc();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Blog - Új bejegyzés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url(cloudbg.jpg);
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Üdvözöllek, <?php echo $loggedInUser; ?>!</h2>
        <h3>Meglévő bejegyzések:</h3>
        <ul>
            <?php foreach ($bejegyzesek as $bejegyzes) : ?>
                <li>
                    <a href="admin_panel.php?edit=<?php echo $bejegyzes['BejegyzesID']; ?>"><?php echo $bejegyzes['Cim']; ?></a>
                    <form action="" method="post" style="display:inline-block;">
                        <input type="hidden" name="bejegyzes_id" value="<?php echo $bejegyzes['BejegyzesID']; ?>">
                        <button type="submit" name="delete" onclick="return confirm('Biztosan törölni szeretnéd ezt a bejegyzést?')">Törlés</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (isset($edit_bejegyzes)) : ?>
            <h3>Bejegyzés szerkesztése:</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="bejegyzes_id" value="<?php echo $edit_bejegyzes['BejegyzesID']; ?>">
                <div class="mb-3">
                    <label for="title" class="form-label">Cím:</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $edit_bejegyzes['Cim']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Bejegyzés szövege:</label>
                    <textarea class="form-control" id="content" name="content" rows="4" required><?php echo $edit_bejegyzes['BejegyzesSzoveg']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="keywords" class="form-label">Kulcsszavak (vesszővel elválasztva):</label>
                    <input type="text" class="form-control" id="keywords" name="keywords" value="<?php echo $edit_bejegyzes['Kulcsszavak']; ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Frissítés</button>
                <a href="admin_panel.php" class="btn btn-secondary">Vissza</a>
            </form>
        <?php else : ?>
            <h3>Új bejegyzés létrehozása:</h3>
            <form action="bejegyzes_function.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Cím:</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Bejegyzés szövege:</label>
                    <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="keywords" class="form-label">Kulcsszavak (vesszővel elválasztva):</label>
                    <input type="text" class="form-control" id="keywords" name="keywords" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Kép:</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Bejegyzés létrehozása</button>
            </form>
        <?php endif; ?>


        <a href="?logout=true" class="btn btn-danger">Kijelentkezés</a>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>