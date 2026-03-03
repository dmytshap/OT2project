<?php

require 'connect_to_database.php';

function getProjectFromDatabase($id)
{
    $connection = connectToDatabase();

    $stm = $connection->prepare("SELECT * FROM PROJECT_DATA WHERE PROJECT_ID = ?");
    $stm->bind_param("s", $id);
    $stm->execute();
    $result = $stm->get_result();

    $num_of_projects = $result->num_rows;

    if ($num_of_projects > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        //TODO better error handling?
    };
    $connection->close();
}


function getProjectsFromDatabase()
{

    $connection = connectToDatabase();

    $stm = $connection->prepare('SELECT * FROM PROJECT_DATA');
    $stm->execute();
    $result = $stm->get_result();

    $num_of_projects = $result->num_rows;

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
