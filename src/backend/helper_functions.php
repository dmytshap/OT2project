<?php
//Hakee annetun kutsulinkin tietokannasta
//palauttaa sen
function fetchInviteLink($link)
{

    $conn = connectToDatabase();
    $stm = $conn->prepare("SELECT * FROM INVITE_LINKS WHERE TOKEN = ?");
    $stm->bind_param("s", $link);
    $stm->execute();

    $result = $stm->get_result();
    $row = $result->fetch_assoc();
    return json_encode($row);
}

//Tarkistaa, onko linkki vielä voimassa
//palauttaa true, jos linkki voimassa, false muutoin
function checkIfValid($link) {

    $data = json_decode($link, true);
    if($data === NULL){
        return false;
    } 
    $expDate = $data["TOKEN_EXPIRY"];
    $currDate = date("Y-m-d H:i:s");
    if($expDate > $currDate){
        return true;
    }else{
        return false;
    }
}  

//Käyttää apuna fetchInviteLink ja checkIfValid funktioita
//Hakee linkin, tarkistaa onko se voimassa vai ei
//palauttaa true, jos linkki löytyi ja se on voimassa ja false muutoin
function fetchAndCheckIfValid($link) {

    $res = fetchInviteLink($link);
    if ($res != FALSE) {
        $valid = checkIfValid($res);
        if (!$valid) {
            return false;
        }
        return true;
    } else {
        return false;
    }
}
