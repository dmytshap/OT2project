<?php
require_once 'connect_to_database.php';

// poistaa valitut projektit
function deleteProject(array $chosen_projects)
{
    if (empty($chosen_projects)) {
        return false;
    }
    #Prepared Statement varten, laskee montako projektia on valittu ja laittaa ne $stmt:iin.
    $placeholders = implode(',', array_fill(0, count($chosen_projects), '?'));

    $connection = connectToDatabase();
    $stmt = $connection->prepare("DELETE FROM PROJECT_DATA WHERE PROJECT_ID IN ($placeholders)");

    #integerejen määrä #stmt varten.
    $integer_amount = str_repeat("i", count($chosen_projects));
    $stmt->bind_param($integer_amount, ...$chosen_projects);

    $stmt->execute();
    $deleted = $stmt->affected_rows;
    $stmt->close();
    $connection->close();

    return $deleted;
}

// vie valitut projektit csv-muotoon
function export(array $chosen_projects)
{
    if (empty($chosen_projects)) {
        return false;
    }
    #Prepared Statement varten, laskee montako projektia on valittu ja laittaa ne $stmt:iin.
    $placeholders = implode(',', array_fill(0, count($chosen_projects), '?'));

    $connection = connectToDatabase();
    $stmt = $connection->prepare("SELECT * FROM PROJECT_DATA WHERE PROJECT_ID IN ($placeholders)");

    #integerejen määrä #stmt varten.
    $integer_amount = str_repeat("i", count($chosen_projects));
    $stmt->bind_param($integer_amount, ...$chosen_projects);

    $stmt->execute();
    $result = $stmt->get_result();

    $num_of_projects = mysqli_num_rows($result);

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
    exit();
}
