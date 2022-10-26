<?php

session_start();
if (isset($_POST['wyslij'])) {
    // ZMIENNE
    $spr = true;
    $login = $_POST['login'];
    $email = $_POST['email'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
    // SPRAWDZENIE LOGIN-U
    if ((strlen($login) < 3) || (strlen($login) > 20)) {
        $spr = false;
        $_SESSION['e_login'] = "Login musi zawierać od 3 do 20 znaków";
    }
    if (ctype_alnum($login) == false) {
        $spr = false;
        $_SESSION['e_login'] = "Login może zawierać tylko litery i cyfry";
    }
    // SPRAWDZENIE EMAIL-U
    $bezpieczny_email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (filter_var($bezpieczny_email, FILTER_VALIDATE_EMAIL) == false || $email != $bezpieczny_email) {
        $spr = false;
        $_SESSION['e_email'] = "Podaj prawidłowy adres email";
    }
    // SPRAWDZENIE HASEŁ
    if ((strlen($pass1) < 8) || (strlen($pass1) > 20)) {
        $spr = false;
        $_SESSION['e_haslo'] = "Hasło musi zawierać od 8 do 20 znaków";
    }
    if ($pass1 != $pass2) {
        $spr = false;
        $_SESSION['e_haslo'] = "Hasła są różne";
    }
    // SPRAWDZENIE REGULAMINU
    if (!isset($_POST['regulamin'])) {
        $spr = false;
        $_SESSION['e_regulamin'] = "Zaakceptuj regulamin";
    }

    require_once('polaczenie.php');
    try {
        $polaczenie = new mysqli($host, $user, $password, $name);
        mysqli_report(MYSQLI_REPORT_STRICT);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception($polaczenie->error);
        } else {
            $rezultat = $polaczenie->query("SELECT * FROM `uzytkownicy` WHERE email = '$email'");
            if (!$rezultat) {
                throw new Exception($polaczenie->error);
            }
            $spr_email = $rezultat->num_rows;
            if ($spr_email > 0) {
                $spr = false;
                $_SESSION['e_email'] = "Istnieje już taki adres email";
            }
            $rezultat = $polaczenie->query("SELECT * FROM `uzytkownicy` WHERE user = '$login'");
            if (!$rezultat) {
                throw new Exception($polaczenie->error);
            }
            $spr_login = $rezultat->num_rows;
            if ($spr_login > 0) {
                $spr = false;
                $_SESSION['e_login'] = "Istnieje już taki login";
            }
            // ZAKOŃCZENIE REJESTRACJI
            if ($spr == true) {
                $rezultat = $polaczenie->query("INSERT INTO `uzytkownicy`(`id`, `user`, `password`, `email`) VALUES (NULL,'$login','$pass_hash','$email')");
                $_SESSION['login'] = $login;
                header('location:dziekuje.php');
            } else {
                throw new Exception($polaczenie->error);
            }
            $polaczenie->close();
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
    <title>Rejestracja</title>
</head>

<body>
    <div class="wrap-form-rejestracja">
        <form action="rejestracja.php" method="POST">
            <h1>Rejestracja</h1>
            <input type="text" name="login" placeholder="Login">
            <br>
            <div class="error">
                <?php
                if (isset($_SESSION['e_login'])) {
                    echo $_SESSION['e_login'];
                    unset($_SESSION['e_login']);
                }
                ?>
            </div>
            <input type="text" name="email" placeholder="Adres email">
            <br>
            <div class="error">
                <?php
                if (isset($_SESSION['e_email'])) {
                    echo $_SESSION['e_email'];
                    unset($_SESSION['e_email']);
                }
                ?>
            </div>
            <input type="password" name="pass1" placeholder="Hasło">
            <br>
            <div class="error">
                <?php
                if (isset($_SESSION['e_haslo'])) {
                    echo $_SESSION['e_haslo'];
                    unset($_SESSION['e_haslo']);
                }
                ?>
            </div>
            <input type="password" name="pass2" placeholder="Powtórz hasło">
            <br>
            <label>
                <input type="checkbox" name="regulamin">
                <p><a href="regulamin.php">Regulamin</a></p>
            </label>
            <div class="error">
                <?php
                if (isset($_SESSION['e_regulamin'])) {
                    echo $_SESSION['e_regulamin'];
                    unset($_SESSION['e_regulamin']);
                }
                ?>
            </div>
            <br>
            <input type="submit" value="Utwórz konto" name="wyslij">
            <span>lub</span>
            <button><a href="index.php">Zaloguj się</a></button>
        </form>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#5056AB" fill-opacity="1" d="M0,224L288,96L576,64L864,192L1152,32L1440,224L1440,320L1152,320L864,320L576,320L288,320L0,320Z"></path>
    </svg>
</body>

</html>