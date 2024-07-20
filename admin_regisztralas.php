<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>FSEGA Social HUB</title>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center">
        <form action="admin_regisztral_function.php" method="POST" enctype="multipart/form-data" class="w-50">
        <div class="form-group">
            <label for="fullname" style="color:white;">Név</label>
            <input type="text" class="form-control" id="fullname" name="fullname" aria-describedby="emailHelp" placeholder="Név">
        </div>
        <div class="form-group">
          <label for="username" style="color:white;">Felhasználónév</label>
          <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" placeholder="Felhasználónév">
      </div>
        <div class="form-group">
            <label for="password" style="color:white;">Jelszó</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Jelszó">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>



</body>
</html>