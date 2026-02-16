<?php
    include '../backend/api-fetcher.php';
?>

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
        <p class="projektitori"> <a href="main.html">  Projektitori</p>
        <nav>
            <a href="lomake.html"> Lomake</a>
            <a href="otayhteytta.html"> Ota yhteyttä</a>
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
                //testattu, että toimii myös
                //$response = file_get_contents($url);
                //tai välikappaleen kautta, joka käyttää curlia 
                $response = fetchApiData($url);
                echo $response;
            ?>
        </div>
    </div>

</body>

</html>