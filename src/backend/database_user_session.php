<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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


/**
 * Generöi OTP ja syöttää sen + sähköpostin tietokantaan
 * @connection
 * @email
 * @expiry 
 * @otp = palauttaa 6 pituinen koodi
 */
function generateOTPAddUserToDatabase($length = 6) {
    
    //Yhteys tietokantaan + sähköposti
    $connection = connectToDatabase(); 
    $email = $_POST['sahkoposti_login'] ?? '';
    $email = strtolower($email);
    $role = "user";
    $opettajat_tiedosto = file("../opettaja.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $opettajat_tiedosto = array_map('strtolower', array_map('trim', $opettajat_tiedosto)); //Pitää olla array map että voisimme tarkistaa .txt tiedoston sisältöä in_array() avulla (rivi 64.)

    // Tämä osoittii toimivan, kun kokeilin käyttää koodia 10 min päästä.
    // Aika mikä näkyy tietokannassa on se, joka on asetettu palvelimella. 
    // Se ei ole käyttöjärjestelmän aika, jolta lomake lähetetään.
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));
    
    //Tarvikkeet OTP varten
    $otp = '';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $maxIndex = strLen($characters) - 1;
    
    //Random OTP with length of 6
    for ($i = 0; $i < $length; $i++){
        $otp .= $characters[random_int(0, $maxIndex)];
    }
    
    // Sähköposti tekstitiedostossa = opettaja
    if (in_array($email, $opettajat_tiedosto)) {
        $role = "TEACHER";
    }
    elseif (!in_array($email, $opettajat_tiedosto) && str_ends_with($email, '@uef.fi')) {  // Sähköposti päättyy uef.fi:lla, mutta ei ole tiedostossa = ei hyväksytään. Mitä jos joku muu opettaja haluaa kirjautua sisään uef.fi sähköpostilla? Tämä tosiaan ei hyväksy, jos on uef.fi säpö ja se ei olle hardkoodattu tiedostoon.
        echo 'Jos olet opiskelija, käytä @student.uef.fi sähköpostia.';
        return false; // Tämä estää että OTP luomisen
    }
    elseif (str_ends_with($email, '@student.uef.fi')) { //Opiskelija
        $role = "STUDENT";
    }
    elseif(!str_ends_with($email, '@uef.fi') && !str_ends_with($email, '@student.uef.fi')) { // Yritys
        $role = "COMPANY";
    }


    // Add email and OTP to database
    $stmt = $connection -> prepare("INSERT INTO USER_SESSION (EMAIL, OTP_CODE, EXPIRY, ROOLI) VALUE (?, ?, ?, ?)");
    $stmt -> bind_param("ssss", $email, $otp, $expiry, $role);
    
    // Something on the way -> throw exception
    if (!$stmt->execute()) {
        die("Insert ei onnistunut" . $stmt->error);
    }
    
    return $otp;
}

/**
 * Tarkistaa, onko sähköposti ja OTP oikein, sekä onko OTP voimassa (EXPITY >= NOW())
 */
function login_check_user() {
    // Yhteys tietokantaan + sähköposti + OTP
    $connection = connectToDatabase();
    $email = $_POST['sahkoposti_login'] ?? '';
    $otp = $_POST['salasana_login'] ?? '';
    
    // Jos jotain puuttuu, heitä errori
    // HTML:n puolella ne ovat kuitenkin required.
    if (!$email || !$otp) {
        die("Email tai OTP puuttuu!");
    }

    // Hakee tiedot (sähköposti + OTP + onko OTP voimassa)
    $stmt = $connection-> prepare("SELECT * 
        FROM USER_SESSION 
        WHERE EMAIL = ? AND OTP_CODE = ? AND EXPIRY >= NOW()
        ORDER BY EXPIRY DESC
        LIMIT 1
    ");


    $stmt -> bind_param("ss", $email, $otp);
    $stmt -> execute();
    $result = $stmt->get_result();

    // Jos $stmt palauttaa rivin, joka pätee, siirrä käyttäjän sivulle, jossa näytetään että kirjautuminen onnistui (tulevaisuudessa voisi siirtää vaikka "minun projektit" sivulle tai vastaavalle). 
    // Jos ei palauttanut, siirrä vaa main sivulle (en jaksanut tehdä erillistä sivua virhettä varten...)
    if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc(); 

    // Tallenna session tiedot 
    $_SESSION['email'] = $row['EMAIL'];
    $_SESSION['role']  = $row['ROOLI'];
    $_SESSION['logged_in'] = true;

    // Onnistunut -> siirrä onnistumissivulle
    header("Location: /succesfully_loged_in.php");
    exit;
        }
    else {
        header("Location: /virhe.html");
    }
    exit;
}


/**TODO:
Turvallisuutta SQL-injectoneita vasta ja muuta... (myöhemmin tulevaisuudessa sit)
 */
?>