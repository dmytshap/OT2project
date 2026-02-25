<?php

require 'connect_to_database.php';

function getProjectFromDatabase($id){
    $connection = connectToDatabase();

    $sql = "SELECT * FROM PROJECT_DATA WHERE PROJECT_ID = $id";
    $result = mysqli_query($connection, $sql);

    $num_of_projects = mysqli_num_rows($result);

    if ($num_of_projects > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        //TODO better error handling?
    };
    $connection->close();
}


function getProjectsFromDatabase(){

    $connection = connectToDatabase();

    $sql = 'SELECT * FROM PROJECT_DATA';
    $result = mysqli_query($connection, $sql);

    $num_of_projects = mysqli_num_rows($result);

    $response = array();
    if ($num_of_projects > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        //return json
        echo json_encode($response);
    } else {
        //TODO better error handling?
        echo 'Ei projekteja';
    }
    $connection->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        getProjectFromDatabase($_GET['id']);
    } else {
        getProjectsFromDatabase();
    }
    exit;
};
