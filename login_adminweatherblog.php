<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif; background: url(bkground.jpg);}

input[type=text], input[type=password] {
  width: 50%;
  padding: 12px 20px;
  margin: 8px auto;
  display: block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 50%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
  background: url(earth.jfif);
  border-radius: 15px;
}

img.avatar {
  width: 20%;
  border-radius: 50%;
}

.container {
  padding: 16px;
  justify-content: center;
  text-align: center;
}

span.psw {
  float: right;
  padding-top: 16px;
}

h2 {
    text-align: center;
}

@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>

<h2>Bejelentkezés admin felület</h2>

<form action="login_function_admin.php" method="post">
  <div class="imgcontainer">
    <img src="admin_weather.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="uname"><b>Felhasználónév</b></label>
    <input type="text" placeholder="Add meg a felhasználónevet" name="uname" required>

    <label for="psw"><b>Jelszó</b></label>
    <input type="password" placeholder="írd be a jelszót" name="psw" required>
        
    <button type="submit">Bejelentkezés</button>
  </div>
</form>

</body>
</html>
