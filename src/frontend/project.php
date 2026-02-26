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
    <link rel="stylesheet" href="../styles/styles_project.css">
    <header>
        <p class="projektitori"> <a href="main.php"> Projektitori</p>
        <nav>
            <a href="../index.html"> Lomake</a>
            <a href="otayhteytta.html"> Ota yhteyttä</a>
            <a href="my_projects.php">My Projects</a>
            <a href="login.html"> Kirjaudu sisään</a>
        </nav>
    </header>

    <div class="main_page">
        <div class="main_text_div">
            <p class="main_text"> Täällä näet projektin kaikki tiedot. </p>
        </div>
        <?php
        //get the project id from url
        $id = $_GET['id'];
        $url = "http://localhost/backend/database_get_projects_data.php?id=$id";
        //returns json
        $response = file_get_contents($url);
        //decodes json into array
        echo "<a href='main.php'>Takaisin</a>";
        if($response != FALSE){
            $data = json_decode($response, true);
            echo "<div class='project_card'>";
            echo "<p><span></span> $data[PROJECT_NAME]</p>";
            echo "<p><span>Lyhytykuvaus: </span> $data[SHORT_DESC]</p>";
            echo "<p><span>Puhelin: </span> $data[PHONE]</p>";
            echo "<p><span>Sähköposti: </span> $data[EMAIL]</p>";
            echo "<p><span>Yritys: </span> $data[COMPANY]</p>";
            echo "<p><span>Ota yhteyttä </span> $data[CONTACT_TIME]<span> mennessä</span></p>";
            echo "<p><span>Deadline: </span> $data[DEADLINE]</p>";
            echo "<p><span>Pitkäkuvaus: </span> $data[LONG_DESC]</p>";
            echo "<p><span>Kurssi: </span> $data[TAG]</p>";
            echo "<p><span>Onko varattu: </span> $data[PROJECT_RESERVED]</p>";
            echo "</div>";
        }else{
            echo 'Projektia ei löytynyt';
        };
        ?>

    </div>

</body>

</html>