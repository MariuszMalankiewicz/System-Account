<?php
session_start();
if (isset($_POST['login']) && isset($_POST['pass'])) {
    require_once('polaczenie.php');
    try {
        $polaczenie = new mysqli($host, $user, $password, $name);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception($polaczenie->error);
        } else {
            $login = $_POST['login'];
            $pass = $_POST['pass'];
            $login = htmlentities($login, ENT_QUOTES, "UTF-8");
            if ($rezultat = $polaczenie->query(sprintf("SELECT * FROM uzytkownicy WHERE user = '%s'", mysqli_real_escape_string($polaczenie, $login))));
            if (!$rezultat) {
                throw new Exception($polaczenie->error);
            }
            $spr_login = $rezultat->num_rows;
            if ($spr_login > 0) {
                $wiersz = $rezultat->fetch_assoc();
                if (password_verify($pass, $wiersz['password'])) {
                    $_SESSION['zalogowany'] = true;
                    $_SESSION['user'] = $wiersz['user'];
                    $_SESSION['email'] = $wiersz['email'];
                    header('location:panel.php');
                    $polaczenie->close();
                }
            } else {
                $_SESSION['blad'] = "Login lub hasło jest niepoprawne";
                header('location:index.php');
                exit();
            }
        }
    } catch (Exception $e) {
        // echo $e;
    }
}

?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>System logowania</title>
</head>

<body>
    <div class="wrap-form-login">
        <form action="index.php" method="POST">
            <h1>Logowanie</h1>
            <input type="text" name="login" placeholder="Login">
            <input type="password" name="pass" placeholder="Hasło">
            <div class="error">
                <?php
                if (isset($_SESSION['blad'])) {
                    echo $_SESSION['blad'];
                    unset($_SESSION['blad']);
                }
                ?>
            </div>
            <input type="submit" value="zaloguj">
            <span>lub</span>
            <button><a href="rejestracja.php">Utwórz konto</a></button>
        </form>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#5056AB" fill-opacity="1" d="M0,224L288,96L576,64L864,192L1152,32L1440,224L1440,320L1152,320L864,320L576,320L288,320L0,320Z"></path>
    </svg>
</body>
</body>

</html>