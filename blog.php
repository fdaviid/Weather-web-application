<?php
// Adatbázis kapcsolat beállítása
$servername = "localhost";
$username = "root"; // Az adatbázis felhasználóneve
$password = ""; // Az adatbázis jelszava
$dbname = "weatherblog"; // Az adatbázis neve

// Kapcsolat létrehozása és ellenőrzése
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Az összes bejegyzés lekérdezése az adatbázisból
$sql = "SELECT bejegyzes.*, admin.Nev AS Szerzo FROM bejegyzes INNER JOIN admin ON bejegyzes.AdminID = admin.AdminID";
$result = $conn->query($sql);
$bejegyzesek = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bejegyzesek[] = $row;
    }
} else {
    // Ha nincs bejegyzés az adatbázisban, üres tömböt állítunk be
    $bejegyzesek = [];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="blog.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Belleza&family=Freeman&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lexend:wght@100..900&family=Ovo&family=Yanone+Kaffeesatz:wght@200..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary rounded-bottom">
            <div class="container-fluid">
                <div class="nav-img"><img src="clud2.png" alt="nav-img" width="100" height="94"></div>
                <a class="navbar-brand" href="#">Időjárás alkalmazás</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link" href="index.html">Kezdőoldal</a>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Térképek</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="https://openweathermap.org/weathermap?basemap=map&cities=true&layer=temperature&lat=30&lon=-20&zoom=5" target="_blank">Hőtérkép</a></li>
                                <li><a class="dropdown-item" href="#">Csapadék térkép</a></li>
                                <li><a class="dropdown-item" href="#">Páratartalom</a></li>
                        </li>
                        </ul>
                        </li>
                        <a class="nav-link" href="#about-section">Rólunk</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <div class="container mt-5 welcome">
        <h1 class="text-center mb-5">Blog bejegyzés és hírek az időjárással kapcsolatba!</h1>
        <div class="row">
            <?php foreach ($bejegyzesek as $bejegyzes) : ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img src="<?php echo $bejegyzes['Kep']; ?>" class="card-img-top" alt="<?php echo $bejegyzes['Cim']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $bejegyzes['Cim']; ?></h5>
                            <p class="card-text"><?php echo substr($bejegyzes['BejegyzesSzoveg'], 0, 100) . '...'; ?>
                            <p class="card-text">Kulcsszavak: <?php echo $bejegyzes['Kulcsszavak']; ?></p>
                            </p>
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#bejegyzes-<?php echo $bejegyzes['BejegyzesID']; ?>" aria-expanded="false" aria-controls="bejegyzes-<?php echo $bejegyzes['BejegyzesID']; ?>">Tovább</button>

                            <div class="collapse" id="bejegyzes-<?php echo $bejegyzes['BejegyzesID']; ?>">
                                <div class="card card-body">
                                    <p><?php echo $bejegyzes['BejegyzesSzoveg']; ?></p>
                                    <p class="card-text">Szerző: <?php echo $bejegyzes['Szerzo']; ?> | Dátum: <?php echo $bejegyzes['Datum']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <section id="about-section" class="py-3 py-md-5 about-section">
        <div class="container">
            <div class="row justify-content-center gy-3 gy-md-4 gy-lg-0 align-items-lg-center">
                <div class="col-12 col-lg-6 col-xl-7">
                    <div class="row justify-content-xl-center about-text">
                        <div class="col-12 col-xl-11 text-white">
                            <h2 class="mb-3">Mit szolgáltunk?</h2>
                            <p class="lead fs-4 text-secondary mb-3 text-white">Csak egy egyszerű keresésre van szükség, és máris tájékozódhat az aktuális és előrejelzett időjárásról bármelyik helyszínen. Legyen szó egy városról, egy kis faluról vagy egy távoli üdülőhelyről, szolgáltatásunk segítségével mindig naprakész lehet az időjárási viszonyokkal kapcsolatban.</p>
                            <p class="mb-5">Adatokat az OpenWeatherMap szolgáltatástól kapjuk API-n keresztül. Látogáss el az ő oldalukra is.</p>
                            <div class="about-logo" style="text-align: center;">
                                <a href="index2.php"><img src="clud2.png" style="margin-right: 40px" alt="logo" width="150" height="140"></a>
                                <a href="https://openweathermap.org/" target="_blank"><img src="openweather.png" style="margin-right: 20px" alt="logo" width="200" height="100"></a>
                                <a href="https://getbootstrap.com/" target="_blank"><img src="bootstrap.png" style="margin-right: 20px" alt="logo" width="140" height="100"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>