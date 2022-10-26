<?php
session_start();
if (!isset($_SESSION['zalogowany'])) {
    header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Panel uzytkownika</title>
</head>

<body>
    <div class="wrap-panel">
        <a href="wyloguj.php"><i class="fas fa-long-arrow-alt-left"></i></a>
        <i class="fas fa-cog"></i>
        <i class="fas fa-user"></i>
        <h2><?php echo $_SESSION['user'] ?></h2>
        <h3><?php echo $_SESSION['email'] ?></h3>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#5056AB" fill-opacity="1" d="M0,224L288,96L576,64L864,192L1152,32L1440,224L1440,320L1152,320L864,320L576,320L288,320L0,320Z"></path>
    </svg>
</body>

</html>