<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
                        <li class="nav-item">
                        <a class="nav-link" href="hallinta.php">Hallinta</a>
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="breadcrumb-ptori" href="uusi_main.php">Projektitori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lomake</li>
            </ol>
        </nav>
        <!---Notification--->
        <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
        <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        ?>
    </div>
<?php endif; ?>
     <form action = "/backend/admin_features.php" method="post" class="row g-4">
                <div class="col-md-6">
                    <label for="inputProjektiNimi" class="form-label">Opettajan sähköposti (vain @uef.fi)</label>
                    <input type="text" class="form-control" name="teacher_email" id="teacherEmail" placeholder="Syötä opettajan sähköposti">
                    <button class="btn-laheta px-5 py-2"  id="addTeacher" name="add_teacher"> Lisää opettaja </button>
                    <button class="btn-laheta px-5 py-2"  id="deleteTeacher" name="delete_teacher" > Poista opettaja </button>

                </div>
            </form>


<body>
    
</body>
</html>