<?php
include '../backend/api-fetcher.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles_main.css">
    <title>Projektitori</title>
</head>

<body>
    <header>
        <p class="projektitori"> <a href="main.php"> Projektitori</p>
        <nav>
            <a href="../index.php"> Lomake</a>
            <a href="otayhteytta.html"> Ota yhteyttä</a>
            <a href="my_projects.php">My Projects</a>
            <a href="login.html"> Kirjaudu sisään</a>
        </nav>
    </header>

    <div class="main_page">
        <div class="main_text_div">
            <p class="main_text"> Täällä näet kaikki tarjolla olevat projektit. </p>
            <a class="main_text" href="projects_table.php" target="_blank"> Näytä kaikki projektit taulukossa (avautuu uudessa välilehdessä)</a>
        </div>
        <div class="grid-projects">
            <?php
            $url = 'http://localhost/backend/database_get_projects_data.php';

            $response = file_get_contents($url);

            $data = json_decode($response, true);
            foreach ($data as $row) {
                echo "<div class='project_card'>";
                echo "<div class='card_container'>";
                echo "<div class='title_company'>";
                echo "<p class='project_name'> -Projektin nimi-</p>";
                echo "<p class='company'> $row[COMPANY] </p>";
                echo "</div>";
                echo "<div class='project_description_comprehensive'>";
                echo "<p id='project_description_title'> Projektin kuvaus</p>";
                echo "<p class='project_description'> $row[SHORT_DESC]</p>";
                echo "<p id='aikavali_text'>Aikaväli</p>";
                echo "<p class='aikavali'> 01.01.2026-31.12.2026</p>";
                echo "<p id='posted'> Julkaistu</p>";
                echo "<p class='date_posted'> $row[CONTACT_TIME]</p>";
                echo "<div class='card_button'>";
                echo "<button class='otayhteytta'> Ota yhteyttä </button>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "<a style='color: blue;' href='project.php?id=$row[PROJECT_ID]'>Katso projektin kaikki tiedot</a>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

</body>

</html>