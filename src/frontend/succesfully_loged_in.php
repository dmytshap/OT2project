<?php 
require __DIR__ . '/../backend/database_add_projects_data.php';

// jos sessiota ei ole = aloita sellaisen (tämä on bypass, koska tietääkseni session_start on pakko olla jokaisella sivulla missä käyttäjän tiedot tarvitaan?)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Jos logged_in ei ole totta = siirrää login sivulle
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /login.html"); 
    exit;
}

// Tarkistin tällä että se palauttaa oikeat tiedot.
$email = $_SESSION['email'];
$role = $_SESSION['role'];

?>

<!-- Tämä sivu on testausta varten. 

Tämä sivu tulee näkyville, jos käyttäjän sisäänkirjautuminen onnistui. 

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
        <p class="projektitori"> <a href="main.php"> Projektitori</a></p>
        <nav>
            <a href="../index.php"> Lomake</a>
            <a href="otayhteytta.html"> Ota yhteyttä</a>
            <a href="my_projects.php">My Projects</a>
            <a href="login.html"> Kirjaudu sisään</a>
        </nav>

    </header>
            <h1 class="title" style="font-size: 80px;">LOGGED IN SUCCESFULLY</h1>
    <h1>Welcome, <?php echo htmlspecialchars($email); ?>!</h1>
    <p>Your role: <?php echo htmlspecialchars($role); ?></p>

    <p><a href="my_projects.php">Go to My Projects</a></p>
</body>
</html>