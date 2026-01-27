<?php

require_once 'database.php';

//this would be for the 'form' where companies can submit projects
//and the project info would be stored into the database

$yritys = $_POST['yritysnimi'];
$projektinnimi = $_POST['projektinimi'];
$lyhytkuvaus = $_POST['lyhytkuvaus'];
$pitkakuvaus = $_POST['pitkakuvaus'];
$aikavali = $_POST['aikavali'];
//$koodi = $_POST['koodi'];
$sahkoposti = $_POST['sahkoposti'];
$puhelinnumero = $_POST['puhelinnumero'];


#replase single ? with database table columns
#replace double ?? with above variables
$sql = 'INSERT INTO messages(?, ?, ?) VALUES(:??, :??, :??);';

$statement = $conn->prepare($sql);
$statement->execute([
    'yritys' => $yritys,
    ':projektinnimi' => $projektinnimi,
    ':lyhytkuvuas' => $lyhytkuvaus,
    ':pitkakuvaus' => $pitkakuvaus,
    ':aikavali' => $aikavali,
    ':koodi' => $koodi,
    ':sahkoposti' => $sahkoposti,
    ':puhelinnumero' => $puhelinnumero
]);

?>