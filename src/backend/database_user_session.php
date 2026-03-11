<?php
require_once 'connect_to_database.php';
/**
 * Generöi OTP ja syöttää sen + sähköpostin tietokantaan
 * @connection
 * @email
 * @expiry 
 * @otp = palauttaa 6 pituinen koodi
 */
function generateOTPAddUserToDatabase($length = 6)
{


    //Yhteys tietokantaan + sähköposti
    $connection = connectToDatabase();
    $email = $_POST['input-email'] ?? '';
    $email = strtolower($email);
    $role = "user";
    
    // Tämä osoittii toimivan, kun kokeilin käyttää koodia 10 min päästä.
    // Aika mikä näkyy tietokannassa on se, joka on asetettu palvelimella. 
    // Se ei ole käyttöjärjestelmän aika, jolta lomake lähetetään.
    $expiry = date("Y-m-d H:i:s", strtotime("+140 minutes"));

    //Tarvikkeet OTP varten
    $otp = '';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $maxIndex = strLen($characters) - 1;

    //Random OTP with length of 6
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[random_int(0, $maxIndex)];
    }

    // Statement for checking if PERMANENT_USERS has a specific row with $email.
    $stmt_check_teacher = $connection -> prepare("SELECT * FROM PERMANENT_USERS WHERE EMAIL = ? LIMIT 1");
    $stmt_check_teacher -> bind_param("s", $email);
    $stmt_check_teacher->execute();
    $stmt_check_teacher->store_result();

    // stmt_check_teacher palauttaa True = löytyy taulukossa Permanent_Users = opettaja
    if (str_ends_with($email, 'uef.fi') && $stmt_check_teacher->num_rows > 0) {
        $role = "TEACHER";
    } elseif (str_ends_with($email, 'uef.fi') && !$stmt_check_teacher->num_rows > 0) {
        $_SESSION['error'] = 'Jos olet opiskelija, käytä @student.uef.fi sähköpostia.';
        header("Location: ../frontend/login.html");
        exit; // Tämä estää että OTP luodaan
    } elseif (str_ends_with($email, '@student.uef.fi')) { //Opiskelija
        $role = "STUDENT";
    } elseif (!str_ends_with($email, '@uef.fi') && !str_ends_with($email, '@student.uef.fi')) { // Yritys
        $role = "COMPANY";
    }


    // Add email and OTP to database
    $stmt = $connection->prepare("INSERT INTO USER_SESSION (EMAIL, OTP_CODE, EXPIRY, ROOLI) VALUE (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $otp, $expiry, $role);

    // Something on the way -> throw exception
    if (!$stmt->execute()) {
        die("Insert ei onnistunut" . $stmt->error);
    }

    return $otp;
}

/**
 * Tarkistaa, onko sähköposti ja OTP oikein, sekä onko OTP voimassa (EXPITY >= NOW())
 */
function login_check_user()
{
    // Yhteys tietokantaan + sähköposti + OTP
    $connection = connectToDatabase();
    $email = $_POST['input-email'] ?? '';
    $otp = $_POST['input-OTP'] ?? '';

    // Jos jotain puuttuu, heitä errori
    // HTML:n puolella ne ovat kuitenkin required.
    if (!$email || !$otp) {
        die("Email tai OTP puuttuu!");
    }

    // Hakee tiedot (sähköposti + OTP + onko OTP voimassa)
    $stmt = $connection->prepare("SELECT * 
        FROM USER_SESSION 
        WHERE EMAIL = ? AND OTP_CODE = ? AND EXPIRY >= NOW()
        ORDER BY EXPIRY DESC
        LIMIT 1
    ");


    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
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
        header("Location: ../frontend/succesfully_loged_in.php");
        exit;
    } else {
        header("Location: ../frontend/virhe.html");
    }
    exit;
}


/**TODO:
Turvallisuutta SQL-injectoneita vasta ja muuta... (myöhemmin tulevaisuudessa sit)
 */

// Token Generation
function generateToken()
{
    $token = bin2hex(random_bytes(20));
    return $token;
}

// Adds Token To database
function addTokenToDatabase($connection, $token)
{
    $token_expiry = date("Y-m-d H:i:s", strtotime("+1 month"));

    $stmt = $connection->prepare("INSERT INTO INVITE_LINKS (TOKEN, TOKEN_EXPIRY) VALUES (?, ?)");
    $stmt->bind_param("ss", $token, $token_expiry);
    $stmt->execute();
}
