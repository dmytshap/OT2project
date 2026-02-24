<?php
/**
 * Database Connection
 * @dbservername database's hostname
 * @dbusername what username used to log in in database
 * @dbpassword username's password
 * @dbname name of the database [table]
 */

use Dotenv\Dotenv;

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require $autoloadPath;
    $dotenv = Dotenv::createImmutable(__DIR__ , '/../.env');
    $dotenv->safeLoad();
}

function connectToDatabase()
{
    //$dbservername = "mariadb"; 
    //$dbusername = "root"; 
    //$dbpassword = "projekti"; 
    //$dbname = "PROJECTS"; 

    $dbservername = $_ENV['DBSERVERNAME'] ?? ''; 
    $dbusername = $_ENV['DBUSERNAME'] ?? ''; 
    $dbpassword = $_ENV['DBPASSWORD'] ?? ''; 
    $dbname = $_ENV['DBNAME'] ?? ''; 

    /** Make connection to database. */
    $connection = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    
    /** If connection to database failed, show error */
    if ($connection->connect_error) {
        die("Ei onnistunut yhdistää tietokantaan..." . $connection->connect_error);
    }
    return $connection;
}


?>