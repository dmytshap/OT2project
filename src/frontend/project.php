<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: kirjautuminen.php');
    exit();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'TEACHER') {
    $message = 'Ei käyttöoikeutta.';
    header('Location: no_access.php?msg=' . $message);
    exit();
}
include '../backend/api-fetcher.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projektitori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles_project.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 fw-semibold sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="uusi_main.php">Projektitori</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-normal">
                <li class="nav-item">
                <a class="nav-link" href="uusi_lomake.php">Lomake</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="hallinta.php">Projektien hallinta</a>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Opettajan nimi
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="logout.php">Kirjaudu ulos</a></li>
                    
                </ul>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        //get the project id from url
        $id = $_GET['id'];
        $url = "http://localhost/backend/database_get_projects_data.php?id=$id";
        //returns json
        $response = file_get_contents($url);
        $projectName = "Projektia ei löytynyt";
        //decodes json into array
        if($response != FALSE){
            $data = json_decode($response, true);
            $projectName = $data['PROJECT_NAME'];
            
        }else{
            $data = null;
        };
        ?>    
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="breadcrumb-ptori" href="uusi_main.php">Projektitori</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $projectName; ?></li>
            </ol>
        </nav>
        
        <?php
        if($data != null){
            echo "<div class='project_card'>";
            echo "<h2> $data[PROJECT_NAME]</h2>";
            echo "<h5 class= 'yritysnimi text-muted'> $data[COMPANY]</h5>";
            echo "<p> $data[SHORT_DESC]";
            echo "<p> $data[LONG_DESC]</p>";
            echo "<h5 class='valiotsikko'> Yhteystiedot </h5>";
            echo "<p>$data[PHONE]</p>";
            echo "<p>$data[EMAIL]</p>";
            echo "<h5 class='valiotsikko'> Lisätiedot </h5>";
            echo "<p>Julkaisuaika: $data[CONTACT_TIME]</p>";
            echo "<p>Deadline: $data[DEADLINE]</p>";
            echo "<p>Kurssi: $data[TAG]</p>";
            echo "<p>Onko varattu: $data[PROJECT_RESERVED]</p>";
            echo "</div>";
        }else{
            echo 'Projektia ei löytynyt';
        };
        ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>