<?php
    include '../backend/export.php';
?>

<!DOCTYPE html>
<!--This version is with export to csv possibility-->
<html>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles_main.css">
    <title>Projektitori</title>
</head>

<body>
    <form action="../backend/export.php" method="post">
        <input type="submit" value="Export to csv">
    </form>

<table>
    <th>Valitse</th>
    <th>Projekti ID</th>
    <th>Projektin nimi</th>
    <th>Lyhytkuvaus</th>
    <th>Puhelin</th>
    <th>Sähköposti</th>
    <th>Yritys</th>
    <th>Ota yhteyttä</th>
    <th>Deadline</th>
    <th>Pitkäkuvaus</th>
    <th>Tag</th>
    <th>Projekti varattu</th>

    <?php
        $url = 'http://localhost/backend/database_get_projects_data.php';
        //returns json
        $response = file_get_contents($url);
        //decodes json into array
        $data = json_decode($response, true);
        
        foreach($data as $row) {
            echo "<tr>";
            //select if you want to select specific rows to export
            //TO DO how to do this?
            echo "<td><input type='checkbox' name='select'></td></a>";
            echo "<td> $row[PROJECT_ID]</td>";
            echo "<td> $row[PROJECT_NAME]</td>";
            echo "<td> $row[SHORT_DESC]</td>";
            //' added to beginning to prevent excel from removing 0's from the beginning
            echo "<td> '$row[PHONE]</td>";
            echo "<td> $row[EMAIL]</td>";
            echo "<td> $row[COMPANY]</td>";
            echo "<td> $row[CONTACT_TIME]</td>";
            echo "<td> $row[DEADLINE]</td>";
            echo "<td> $row[LONG_DESC]</td>";
            echo "<td> $row[TAG]</td>";
            echo "<td> $row[PROJECT_RESERVED]</td>";
            echo "</tr>";
        }      
    ?>
</table>
</body>
</html>