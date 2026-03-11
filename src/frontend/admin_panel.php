<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../backend/connect_to_database.php';
$connection = connectToDatabase();
$sql = 'SELECT * FROM INVITE_LINKS ORDER BY TOKEN_EXPIRY DESC';
$result = mysqli_query($connection, $sql);

if (isset($_POST['delete_invite_links']) && isset($_POST['selected_tokens'])) {
    $deleted = deleteInviteLink($_POST['selected_tokens']);

    $_SESSION['messages'] = "$deleted kutsulinki(t) on poistettu / ovat poistettu";
    $_SESSION['message_type'] = "success";

    header("Location: ../frontend/admin_panel.php");
    exit();
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
    <link rel="stylesheet" href="../styles/styles_admin_panel.css">
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="breadcrumb-ptori" href="uusi_main.php">Projektitori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Admin</li>
            </ol>
        </nav>
        <h2 class="main_text mb-4">Admin</h2>
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
        <h4 class="valiotsikko">Opettajien hallinta</h4>
        <form action="../backend/admin_features.php" method="post" class="row g-4">
            <div class="col-md-6">
                <label for="teacherEmail" class="form-label">Opettajan sähköposti (vain @uef.fi)</label>
                <input type="text" class="form-control" name="teacher_email" id="teacherEmail" placeholder="Syötä opettajan sähköposti">
                <button class="btn-lisaa-opettaja px-5 py-2" id="addTeacher" name="add_teacher"> Lisää opettaja </button>
                <button class="btn-poista-opettaja px-5 py-2" id="deleteTeacher" name="delete_teacher"> Poista opettaja </button>

            </div>
        </form>

        <form action="../backend/admin_features.php" method="post"> <!-- vois lisätä class="row g-4" tänne jos halua -->
            <h4 class="valiotsikko">Kutsulinkit</h4>

            <div class="button-div d-flex align-items-center justify-content-between w-100 mb-2">
                <h6 class="aktiiviset-kutsulinkit mb-0">Aktiiviset kutsulinkit</h6>
                <button class="btn-kutsulinkki px-5 py-2" id="luoKutsulinkki" name="luo_kutsulinkki" type="submit" onClick="alertUser()" value="Show alert Box">Luo uusi kutsulinkki</button>
            </div>
        </form>
        <form action="../backend/admin_features.php" method="post">
            <div class="table-responsive">
                <table class="table table-hover shadow">
                    <thead>
                        <tr>
                            <th scope="col"><input class='form-check-input' type="checkbox" id="select-all" /></th>
                            <th scope="col">Token</th>
                            <th scope="col">Vanhenemisaika</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>
                                    <input class='form-check-input row-checkbox'
                                        type='checkbox'
                                        name='selected_tokens[]'
                                        value='<?= htmlspecialchars($row['TOKEN']) ?>' />
                                </td>
                                <td><?= htmlspecialchars($row['TOKEN']) ?></td>
                                <td><?= htmlspecialchars($row['TOKEN_EXPIRY']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div id="toolbar" class="toolbar d-none d-flex shadow justify-content-between align-items-center p-3 bg-light border rounded mt-2">
                <span id="selected-count">1 valittu</span>
                <div class="scroll">
                    <button id="btn-kopioi" type="button" name="copy_invite_links" class="btn btn-kopioi"> Kopioi linkki</button>
                    <button id="btn-poista" type="submit" name="delete_invite_links" class="btn btn-poista">Poista käytöstä</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- LLM:n tuottama, katsoin tutorialit netissä niin siellä tehtiin samalla tavalla -->
    <script>
        document.getElementById('btn-kopioi').addEventListener('click', function(e) {
            e.preventDefault(); 
            const checked = document.querySelectorAll('.row-checkbox:checked');

            const links = Array.from(checked).map(cb => {
                return window.location.origin + '/frontend/uusi_lomake.php?token=' + cb.value;   // Tätä pitää sitten muokkaa, kun tehdään mod_rewrite jutun, että olisi custom linkkejä tiedostoille. Eli esim backend/admin_panel.php sijaan olisi ihan vaa localhost/adminPanel
            }).join('\n');

            navigator.clipboard.writeText(links).then(() => {
                alert(checked.length + ' linkki(ä) kopioitu leikepöydälle!');
            }).catch(() => {
                alert('Kopiointi epäonnistui.');
            });
        });
    </script>
    <!-- Tekoälyn luoma koodi, toolbar tulee näkyville, kun valitaan 1 tai useampi checkbox -->
    <script>
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const toolbar = document.getElementById('toolbar');
        const selectedCount = document.getElementById('selected-count');

        function updateToolbar() {
            // montako checkboxia on valittu
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const count = checkedBoxes.length;

            if (count > 0) {
                // asetetaan toolbar näkyväksi ja päivitetään teksti sen mukaan, montako on valittu
                toolbar.classList.remove('d-none');
                selectedCount.textContent = `${count} valittu`;
            } else {
                toolbar.classList.add('d-none'); // piilotetaan toolbar
                selectedCount.textContent = '0 valittu';
            }

            //update select-all checkbox
            selectAll.checked = count === rowCheckboxes.length;
        }

        // Add event listeners to all checkboxes
        rowCheckboxes.forEach(cb => cb.addEventListener('change', updateToolbar));
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
            updateToolbar();
        });
    </script>

    <?php if (isset($_SESSION['invite_link'])): ?>
        <script>
            window.onload = function() {
                alert("Tässä on kutsulinkki:\n<?= $_SESSION['invite_link'] ?>");
            }
        </script>
        <?php unset($_SESSION['invite_link']); ?>
    <?php endif; ?>
</body>

</html>