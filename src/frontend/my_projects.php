<?php
// Tietokannan yhdistämistä varten
require  '../backend/database_add_projects_data.php';

    // jos sessiota ei ole = aloita sellaisen (tämä on bypass, koska tietääkseni session_start on pakko olla jokaisella sivulla missä käyttäjän tiedot tarvitaan?)
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

// Jos logged_in ei ole totta = siirrää login sivulle
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /login.html");
    exit;
}

//Email
$email = $_SESSION['email']; 

// Nämä yhdistävät tietokantaan ja hakevat tietokannasta projektit käyttäjän sähköpostin perusteella
$connection = connectToDatabase(); 
$stmt = $connection->prepare("SELECT * FROM PROJECT_DATA WHERE EMAIL = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$projects = $result -> fetch_all(MYSQLI_ASSOC);
?>


<!-- Tämä sivu näyttää käyttäjän omat projektit. 
Tämä sivu tulee näkyville, jos käyttäjä on sisäänkirjautunut ja omistaa projekteja. 
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projektitori</title>
</head>

<body>
    <link rel="stylesheet" href="../styles/styles_main.css">
    <header>
    <p class="projektitori"><a href="main.php">Projektitori</a></p>
    <nav>
        <a href="../index.html">Lomake</a>
        <a href="otayhteytta.html">Ota yhteyttä</a>            
        <a href="my_projects.php">My Projects</a>
        <a href="logout.php">Kirjaudu ulos</a>
    </nav>
</header>

<h1>Omat projektit</h1>


<!-- Tämä ei ole täydellinen, mutta tärkeintä että se hakee tiedot ja muodostaa niistä kortin. LLM:n tuottama. --> 
<div class="grid-projects">
    <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $project): ?>
            <div class="project_card">
                <div class="card_container">
                    <div class="title_company">
                        <p class="project_name"><?php echo htmlspecialchars($project['PROJECT_NAME']); ?></p>
                        <p class="company"><?php echo htmlspecialchars($project['COMPANY']); ?></p>
                    </div>
                    <div class="project_description_comprehensive">
                        <p id="project_description_title">Projektin kuvaus</p>
                        <p class="project_description">
                            <?php 
                                // Lyhyt kuvaus = 150 char, pitkä = 400
                                $short_desc = htmlspecialchars($project['SHORT_DESC']);
                                echo strlen($short_desc) > 150 ? substr($short_desc,0,150).'...' : $short_desc;
                                $long_desc = htmlspecialchars($project['LONG_DESC']);
                                echo strlen($long_desc) > 400 ? substr($long_desc,0,400).'...' : $long_desc;
                            ?>
                        </p>
                        <p id="posted">Julkaistu</p>
                        <p class="date_posted"><?php echo date('d.m.Y', strtotime($project['CONTACT_TIME'])); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Sinulla ei ole vielä projekteja.</p>
    <?php endif; ?>
</div>
</body>
</html>
