<?php

require_once 'connect_to_database.php';


function export()
{
    $connection = connectToDatabase();

    $stm = $connection->prepare('SELECT * FROM PROJECT_DATA');
    $stm->execute();

    $result = $stm->get_result();

    $num_of_projects = $result->num_rows;

    $response = array();
    if ($num_of_projects > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        $connection->close();

        //delimiter for csv file
        $delimiter = ",";

        //Headers for the csv file
        $headers = array(
            'Projekti ID',
            'Projektin nimi',
            'Lyhytkuvaus',
            'Puhelin',
            'Sähköposti',
            'Yritys',
            'Ota yhteyttä',
            'Deadline',
            'Pitkäkuvaus',
            'Tag',
            'Projekti varattu'
        );

        $filename = "projects_data_" . date('d-m-Y') . ".csv";

        //opens file in php memory
        $f = fopen('php://memory', 'w');

        //insert headers into csv
        fputcsv($f, $headers, $delimiter);

        //insert data into csv
        foreach ($response as $row) {
            $line = array(
                $row['PROJECT_ID'],
                $row['PROJECT_NAME'],
                $row['SHORT_DESC'],
                //adds ' to the beginning, otherwise excel removes 0's from the beginning
                "'" . $row['PHONE'],
                $row['EMAIL'],
                $row['COMPANY'],
                $row['CONTACT_TIME'],
                $row['DEADLINE'],
                $row['LONG_DESC'],
                $row['TAG'],
                $row['PROJECT_RESERVED']
            );
            fputcsv($f, $line, $delimiter);
        }

        //move back to the beginning of the file
        fseek($f, 0);

        header('Content-Type: text/csv;charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        //makes ä, ö etc. appear properly in csv
        echo "\xEF\xBB\xBF";


        fpassthru($f);
    } else {
        echo 'Ei projekteja';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    export();
    exit;
};