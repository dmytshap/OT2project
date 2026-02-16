<?php

require 'database.php';

function getProjectsFromDatabaseAsTable() {

    $connection = connectToDatabase();
    $sql = 'SELECT * FROM PROJECT_DATA';
    $result = mysqli_query($connection, $sql);

    $num_of_projects = mysqli_num_rows($result);
    if($num_of_projects > 0){
        while($row = $result->fetch_assoc()){
            //echos each project as a html table row
            echo "<tr>";
            echo "<td><input type='checkbox' name='select'></td>";
            echo "<td> $row[COMPANY]</td>";
            echo "<td> $row[SHORT_DESC]</td>";
            echo "<td> $row[PHONE]</td>";
            echo "<td> $row[EMAIL]</td>";
            echo "<td> $row[CONTACT_TIME]</td>";
            echo "<td> $row[DEADLINE]</td>";
            echo "<td> $row[LONG_DESC]</td>";
            echo "</tr>";
        }
    }else{
        echo 'Ei projekteja';
    }
    $connection->close();
}


if($_SERVER['REQUEST_METHOD'] === 'GET'){
    getProjectsFromDatabaseAsTable();
    exit;
};

?>