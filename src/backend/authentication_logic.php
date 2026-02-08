<?php

require 'database_user_session.php';


// Tuleeko add vai login actioni
$action = $_POST['action'] ?? '';


// If server requested POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Jos action on Generate, kutsu generateOTPAddUserToDatabase()
    if ($action == 'generate') {
        $otp = generateOTPAddUserToDatabase();
        
        // Rivi alhaalla on testausta varten, tarkistin, että kaikki toimii ja OTP oikeasta generoidaan. Tämän pitäisi sit lähettää SMTP:n kautta sähköpostille. 
        // Pitäskö koodin lähettäminen toteuttaa tässä if lausekkeessa vai generateOTPAddUserToDatabase() metodissa? 
        echo "Sinun OTP koodi on: $otp";  
        exit;
    }
    //Jos action on login, kutsu login_check_user()
    if ($action === 'login') {
        login_check_user();
        header("Location: /virhe.html");
    }
}
