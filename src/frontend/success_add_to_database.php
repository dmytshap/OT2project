<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: kirjautuminen.php');
    exit();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'TEACHER') {
    $message = 'Ei käyttöoikeutta.';
    header('Location: no_access.php?msg=' . $message);
    exit();
}
?>
<!-- Tämä sivu on testausta varten. 

Tämä sivu tulee näkyville, jos käyttäjän tiedot tallennettiin tietokantaan onnistuneesti. 

Tulevaisuudessa voisi käyttäjää siirtää toiselle sivulle, jossa olisi sisältöä (esim. "Minun projektit") ? -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles_lomake.css">
    <title>Lomake</title>
</head>

<body>
    <header>
        <p class="projektitori"> <a href="main.php">  Projektitori</p>
        <nav>
            <a href="../index.php"> Lomake</a>
            <a href="otayhteytta.html"> Ota yhteyttä</a>
            <a href="my_projects.php">My Projects</a>
            <a href="login.html"> Kirjaudu sisään</a>
        </nav>

    </header>
            <h1 class="title" style="font-size: 80px;">Lomake lisättiin tietokantaan onnistuneesti.</h1>
    
</body>
</html>