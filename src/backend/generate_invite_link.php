<?php

function generateInviteLink()
{
    require_once 'database_user_session.php';

    $connection = connectToDatabase();
    $token = generateToken();

    addTokenToDatabase($connection, $token);

    $invite_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?token=" . urlencode($token);
    return $invite_link;
}
