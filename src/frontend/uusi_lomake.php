<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: kirjautuminen.php');
    exit();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'TEACHER') {
    if ($_SESSION['role'] === 'COMPANY' && isset($_GET['id'])) {
        include '../backend/helper_functions.php';
        $id = $_GET['id'];
        $res = fetchAndCheckIfValid($id);
        if (!$res) {
            $message = "Kutsulinkki ei ole enää voimassa tai väärä";
            header('Location: no_access.php?msg=' . $message);
            exit();
        }
    }else{
        $message = "Ei käyttöoikeutta.";
        header('Location: no_access.php?msg=' . $message);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lomake</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/uusi_styles_lomake.css">
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
                        <a class="nav-link active" aria-current="page" href="uusi_lomake.php">Lomake</a>
                    </li>
                    <?php
                    if($_SESSION['role'] === 'TEACHER'){
                        echo "<li class='nav-item'>";
                        echo "<a class='nav-link' href='hallinta.php'>Projektien hallinta</a>";
                        echo "</li>";
                    }
                    ?>
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="breadcrumb-ptori" href="uusi_main.php">Projektitori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lomake</li>
            </ol>
        </nav>
        <div class="col-lg-8 col-xl-7">
            <h2 class="lomake_teksti mb-4"> Projektilomake </h2>
            <p class="alateksti mb-4">Lähetä projekti-ideasi projektitorille tällä lomakkeella. </p>
            <form class="row g-4" action="/backend/database_add_projects_data.php" method="post">
                <div class="col-md-6">
                    <label for="inputProjektiNimi" class="form-label">Projektin nimi</label>
                    <input type="text" class="form-control" name="projektinnimi" id="inputProjektiNimi" placeholder="Syötä projektin nimi">
                </div>
                <div class="col-md-6">
                    <label for="inputYritysNimi" class="form-label">Yrityksen nimi</label>
                    <input type="text" class="form-control" name="yritysnimi" id="inputYritysNimi" placeholder="Syötä yrityksen nimi">
                </div>
                <div class="col-12">
                    <label for="textareaLyhytKuvaus" class="form-label">Lyhyt kuvaus</label>
                    <textarea class="form-control" name="lyhytkuvaus" id="textareaLyhytKuvaus" rows="2" placeholder="Kuvaile projektia yhdellä virkkeellä"></textarea>
                </div>
                <div class="col-12">
                    <label for="textareaPitkaKuvaus" class="form-label">Pitkä kuvaus</label>
                    <textarea class="form-control" name="pitkakuvaus" id="textareaPitkaKuvaus" rows="7" placeholder="Kerro projektista tarkemmin"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="textareaAikataulu" class="form-label">Aikataulu</label>
                    <textarea class="form-control" name="aikataulu" id="aikataulu" rows="1" placeholder="Milloin projektin tulee olla valmis? Esim. Kesäkuussa"></textarea>
                </div>

                <div class="col-md-6">
                    <label for="inputPuh" class="form-label">Puhelinnumero</label>
                    <input type="tel" class="form-control" name="puhelinnumero" id="inputPuh" placeholder="Syötä puhelinnumero">
                </div>

                <div class="col-md-6">
                    <label for="inputEmail" class="form-label">Sähköposti</label>
                    <input type="email" class="form-control" name="sahkoposti" id="inputEmail" placeholder="name@example.com">
                </div>
                <div class="col-12 text-end mt-5 mb-5">
                    <button type="submit" class="btn-laheta px-5 py-2" name="lahetaLomake" >Lähetä</button>
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>