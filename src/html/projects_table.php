<!DOCTYPE html>

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
    <button>Export to csv</button>
<table>
    <th>Valitse</th>
    <th>Yritys</th>
    <th>Lyhytkuvaus</th>
    <th>Puhelin</th>
    <th>Sähköposti</th>
    <th>Ota yhteyttä</th>
    <th>Deadline</th>
    <th>Pitkäkuvaus</th>
    <?php
        $url = 'http://localhost/backend/database_get_projects_table.php';
        $response = file_get_contents($url);
        echo $response;
    ?>
</table>
</body>
</html>