<?php
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
    
    /** Make connection to database. */
    $connection = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    
    /** If connection to database failed, show error */
    if ($connection->connect_error) {
        die("Ei onnistunut yhdistää tietokantaan..." . $connection->connect_error);
    }
    return $connection;
}


?>