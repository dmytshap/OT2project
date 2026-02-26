<?php
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
    <link rel="stylesheet" href="../styles/uusi_styles_main.css">
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
            <h2 class="main_text mb-4">Kaikki projektit</h2>
            <div class="row">
                <?php
                $url = 'http://localhost/backend/database_get_projects_data.php';

                $response = file_get_contents($url);

                $data = json_decode($response, true);
                foreach ($data as $row) {
                    echo "<div class='col-md-3 mb-4'>";
                    echo "<a class='card-link' href='project.php?id=$row[PROJECT_ID]'>";
                    echo "<div class='card h-100 shadow'>";
                    echo "<div class='card-body d-flex flex-column'>";
                    echo "<h5 class='card-title'> $row[PROJECT_NAME] </h5>";
                    echo "<h6 class='card-subtitle mb-2 text-muted'> $row[COMPANY] </h6>";
                    echo "<p class='card-text'> $row[SHORT_DESC] </p>";
                    echo "<p class='mb-1'><strong>Aikaväli:</strong> 01.01.2026 - 31.12.2026</p>";
                    echo "<p class='mb-3'><strong>Julkaistu:</strong> $row[CONTACT_TIME] </p>";
                    echo "<div class='mt-auto d-flex justify-content-center'>";
                    echo "<a class='btn-tiedot px-5 py-2' href='project.php?id=$row[PROJECT_ID]'>Näytä tiedot</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</a>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>