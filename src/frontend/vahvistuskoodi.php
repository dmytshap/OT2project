<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vahvistuskoodi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles_kirjautuminen.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 fw-semibold sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="uusi_main.php">Projektitori</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="card-container">
        <div class="card shadow login-card">
            <div class="card-body">
                <h3 class="card-title">Vahvistuskoodi</h3>
                <p class="card-text">Syötä koodi, jonka lähetimme sähköpostiosoitteeseesi.</p>
                <form action="/backend/authentication_logic.php" method="post">  
                    <div class="form-group mb-3">
                        <label for="inputKoodi" class="form-label">Koodi</label>
                        <input type="text" class="form-control" id="inputKoodi" placeholder="Syötä koodi">
                    </div>
                    <button type="submit" name="action" value="login" class="btn-laheta px-5 py-2">Vahvista</button>
                </form>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
