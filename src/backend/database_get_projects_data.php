<?php

require 'connect_to_database.php';

//tämän voisi yhdistää database_projects_data.php tiedostoon

function getProjectsFromDatabase() {

    $connection = connectToDatabase();
    $sql = 'SELECT * FROM PROJECT_DATA';
    $result = mysqli_query($connection, $sql);

    $num_of_projects = mysqli_num_rows($result);
    if($num_of_projects > 0){
        while($row = $result->fetch_assoc()){
            //TODO 
            //Ääkköset eivät tulostu oikein
            //Missä kohtaa käsitellään, että ääkköset näkyvät oikein
            //Tieto mikä halutaan näyttää $row[Tietokannassa olevan sarakkeen nimi]
            echo "<div class='project_card'>";
            echo "<div class='card_container'>";
            echo "<div class=title_company'>";
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
            echo "</div>";
        }
    }else{
        echo 'Ei projekteja';
    }
    $connection->close();
}


if($_SERVER['REQUEST_METHOD'] === 'GET'){
    getProjectsFromDatabase();
    exit;
};

?>