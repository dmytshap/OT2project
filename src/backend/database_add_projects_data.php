<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'connect_to_database.php';
//Database Configuration 
// This file will contain database connection details.
// Also database manipulation functions etc.
// Tämä yhdistää tietokantaan
// Sain dockerin pyörimään ajamalla komennot:
// docker build -t ot2project ./docker/
// docker-compose up -d


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
    $projektinnimi = $_POST['projektinnimi'] ?? '';
    $lyhytkuvaus = $_POST['lyhytkuvaus'] ?? '';
    $pitkakuvaus = $_POST['pitkakuvaus'] ?? '';
    //$valmistuspaiva =  $_POST['valmistuspaiva'] ?? '';
    $sahkoposti = $_POST['sahkoposti'] ?? '';
    $puhelinnumero = $_POST['puhelinnumero'] ?? '';
    $reserved = false;

    $timestamp = date("Y-m-d H:i:s");

    // What fields should be added
    $stmt = $connection->prepare(
        "INSERT INTO PROJECT_DATA (short_desc, project_name, phone, email, company, long_desc, CONTACT_TIME, PROJECT_RESERVED) VALUES(?,?,?,?,?,?,?,?)"
    );

    // Fill in database's fields with variables we got previously from user
    $stmt->bind_param(
        "sssssssi",
        $lyhytkuvaus,
        $projektinnimi,
        $puhelinnumero,
        $sahkoposti,
        $yritysnimi,
        $pitkakuvaus,
        $timestamp,
        $reserved
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


// If server requested POST, run addFormToDatabase()
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    addFormToDatabase();
    header("Location: ../frontend/success_add_to_database.php");
    exit;
}
