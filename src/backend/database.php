<?php

//Database Configuration 
// This file will contain database connection details.
// Also database manipulation functions etc.
// Tämä yhdistää tietokantaan
// Sain dockerin pyörimään ajamalla komennot:
// docker build -t ot2project ./docker/
// docker-compose up -d


/**
 * Database Connection
 * @dbservername database's hostname
 * @dbusername what username used to log in in database
 * @dbpassword username's password
 * @dbname name of the database [table]
 */
function connectToDatabase()
{
    $dbservername = "mariadb"; 
    $dbusername = "root"; 
    $dbpassword = "projekti"; 
    $dbname = "PROJECTS"; 
    
    try{
        $connection = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    }catch(Exception $e){
        $error = $e->getMessage();
        var_dump($error);
    }
    /** Make connection to database. */
    
    /** If connection to database failed, show error */
    //if ($connection->connect_error) {
      //  die("Ei onnistunut yhdistää tietokantaan..." . $connection->connect_error);
    //}
    //return $connection;
}

/** Adds form's information to database // not all fields are currently in use.
 *  @yritysnimi name on company
 *  @lyhytkuvaus short description
 *  @pitkakuvaus long description
 *  @sahkoposti email
 *  @puhelinnumero phone number
 *  @timestamp time of form's submission
 *  @stmt statement to add information to database
 *  @newId is a new ID for new information in database
 *  */
function addFormToDatabase()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    $connection = connectToDatabase();

    $yritysnimi = $_POST['yritysnimi'] ?? '';
    // $projektinnimi = $_POST['projektinnimi'] ?? '';
    $lyhytkuvaus = $_POST['lyhytkuvaus'] ?? '';
    $pitkakuvaus = $_POST['pitkakuvaus'] ?? '';
    //$valmistuspaiva =  $_POST['valmistuspaiva'] ?? '';
    $sahkoposti = $_POST['sahkoposti'] ?? '';
    $puhelinnumero = $_POST['puhelinnumero'] ?? '';

    $timestamp = date("d-m-y h:i:s");

    // What fields should be added
    $stmt = $connection->prepare(
        "INSERT INTO PROJECT_DATA (short_desc, phone, email, company, long_desc, CONTACT_TIME) VALUES(?,?,?,?,?,?)"
    );

    // Fill in database's fields with variables we got previously from user
    $stmt->bind_param(
        "ssssss",
        $lyhytkuvaus,
        $puhelinnumero,
        $sahkoposti,
        $yritysnimi,
        $pitkakuvaus,
        $timestamp
    );

    // If something was on way, throw exception
    if (!$stmt->execute()) {
        die("Insert ei onnistunut" . $stmt->error);
    }

    $newId = $stmt->insert_id;

    $stmt->close();
    $connection->close();

    return $newId;
}

function getProjectsFromDatabase() {

    $connection = connectToDatabase();
    //testing with just email
    $sql = 'SELECT EMAIL FROM PROJECT_DATA';
    $result = mysqli_query($connection, $sql);

    $num_of_projects = mysqli_num_rows($result);

    if($num_of_projects > 0){
      $projects_arr = array();
        $projects_arr['data'] = array();
        while($row = $statement->fetch_assoc()){
            $project_item = array(
                'EMAIL' => $email,
            );
            array_push($projects_arr['data'], $project_item_item);
        }
        return json_encode($projects_arr);
    }else{
        echo 'Ei projekteja';
    }
}


// If server requested POST, run addFormToDatabase()
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    addFormToDatabase();
    exit;
}else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    getProjectsFromDatabase();
    exit;
};



