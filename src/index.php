<?php
session_start();
require_once 'backend/database_user_session.php';

// Ajatus on tämä: Lomakkeelle pääse ainoastaan jos on TEACHER tai COMPANY rooli.
// Opiskelijat eivät näe lomaketta.
// Lomakkeen pääse myös linkin kautta. Linkille luodaan token, jonka tallennettaan INVITE_TOKEN taulukkoon (tietokannassa).
// Eli siis tätä varten tein vielä yhden taulukon, toista ratkaisua en ole keksinyt. Taulukossa on TOKEN ja TOKEN_EXPIRY

$authenticated = false;

// Jos on kirjauttunut sisään ja on opettaja = on pääsy
if (isset($_SESSION['logged_in']) && $_SESSION['role'] === 'TEACHER') {
    $authenticated = true;
}
// Jos on kirjauttunut sisään ja on yritys = on pääsy
if (isset($_SESSION['logged_in']) && $_SESSION['role'] === "COMPANY") {
    $authenticated = true;
}

// Painat linkkiä jossa on valid token = on pääsy.
if (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token']);

    $connection = connectToDatabase();
    $stmt = $connection->prepare("SELECT TOKEN FROM INVITES WHERE TOKEN = ? AND TOKEN_EXPIRY > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    // Jos tietokannassa löytyi tokeni: authenticated = true.
    $result = $stmt->get_result()->fetch_assoc();
    if ($result) {
        $authenticated = true;
    }
}
// Jos ei ole kirjautunut sisään = ei pääsyä
if (!$authenticated) {
    die("EI PÄÄSYÄ. TARVITSET KUTSULINKIN TAI SOPIVAN ROOLIN!");
}

$invite_link = '';
// Jos painettiin "Kutsu" näppäintä...
if (isset($_POST['inviteButton'])) {
    include 'backend/generate_invite_link.php';
    $invite_link = generateInviteLink();
}

// Jos painettiin "Lähetä" näppäintä...
if (isset($_POST['acceptbutton'])) {
    include 'backend/database_add_projects_data.php';
    exit();
}
?>
<!-- Sen jälkeen kun painettiin Kutsu näppäintä, tämä näytetään opettajalle: -->
<script>
    function alertUser() {
        alert("Tässä on kutsulinkki: " + $invite_link)
    }
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles_lomake.css">
    <title>Lomake</title>
</head>

<body>
    <header>
        <p class="projektitori"> <a href="frontend/main.php"> Projektitori</a></p>
        <nav>
            <a href="index.php"> Lomake</a>
            <a href="frontend/otayhteytta.html"> Ota yhteyttä</a>
            <a href="frontend/login.html"> Kirjaudu sisään</a>

        </nav>
    </header>
    <div class="container">
        <h1 class="title">Tervetuloa projektilomakkeen</h1>
        <div class="code_explanation_text_div">
            <p class="code_explanation_text"> Lähettääkseen lomakkeen tarvitset koodin. </p>
        </div>
        <form action="index.php" method="post">
            <div class="form-grid">
                <div class="input-group">
                    <input type="text" name="yritysnimi" class="input">
                    <label class="user-label">Yrityksen Nimi</label>
                </div>

                <div class="input-group">
                    <input type="text" name="projektinnimi" class="input">
                    <label class="user-label">Projektin Nimi</label>
                </div>

                <div class="input-group">
                    <textarea name="lyhytkuvaus" class="input" rows="5"
                        cols="40"></textarea>
                    <label class="user-label">Lyhyt kuvaus (näkyy opiskelijalle)</label>
                </div>

                <div class="input-group">
                    <textarea name="pitkakuvaus" class="input" rows="20"
                        cols="40"></textarea>
                    <label class="user-label">Pitkä kuvaus (näkyy opettajalle) / ohjeet </label>
                </div>

                <div class="input-group label-float">
                    <textarea name="datetime" class="input" rows="20" cols="40"></textarea>
                    <label class="user-label">Milloin pitää olla valmis?</label>
                </div>


            </div>


            <div class="container2">
                <h2> Yhteystiedot</h2>
                <div class="contact-grid">
                    <div class="input-group">
                        <input type="text" name="sahkoposti" class="input" required autocomplete="off">
                        <label class="user-label">Sähköposti</label>
                    </div>

                    <div class="input-group">
                        <input type="text" name="puhelinnumero" class="input">
                        <label class="user-label">Puhelinnumero</label>
                    </div>
                </div>
            </div>
            <div class="button_container">
                <button class="invite_button" name="inviteButton" type="submit" onClick="alertUser()" value=" Show alert Box">
                    Kutsu </button>
                <!--Kun tämä painetaan, ilmoitus menisi main.html korttina ja lisättäisiin gridiin. Gridiin menisi nimi, aika, lyhyt kuvaus ja timestamp (aika, milloin lähetettiin kortin).-->
                <button class="accept_button" name="acceptbutton" type="submit">
                    Lähetä
                </button>
            </div>
            <?php if ($invite_link): ?>
                <script>
                    window.onload = function() {
                        alert("Tässä on kutsulinkki:\n<?php echo $invite_link; ?>");
                    }
                </script>

            <?php endif; ?>
        </form>
    </div>
</body>

</html>