<?php

require 'connect_to_database.php';

function getProjectsFromDatabase() {

    $connection = connectToDatabase();
     
    $sql = 'SELECT * FROM PROJECT_DATA';
    $result = mysqli_query($connection, $sql);

    $num_of_projects = mysqli_num_rows($result);
    
    $response = array();
    if($num_of_projects > 0){
        while($row = $result->fetch_assoc()){
        $response[] = $row;
        }
        echo json_encode($response);
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
