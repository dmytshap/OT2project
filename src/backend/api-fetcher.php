<?php
    #Välikappale, joka käyttää curlia http pyynnön lähettämiseen
    function fetchApiData($url) {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HEADER, 0);

    $output = curl_exec($ch);

    if($output === FALSE){
        echo "cURL Error: " . curl_error($ch);
    }

    curl_close($ch);
    echo $output;
    }

?>