<?php

require_once '../backend/connect_to_database.php';
include '../backend/export.php';

$connection = connectToDatabase();
$sql = 'SELECT * FROM PROJECT_DATA';
$result = mysqli_query($connection, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projektien hallinta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles_hallinta.css">
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
                        <a class="nav-link active" aria-current="page" href="hallinta.php">Projektien hallinta</a>
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
                <li class="breadcrumb-item active" aria-current="page">Projektien hallinta</li>
            </ol>
        </nav>
        <h2 class="main_text mb-4">Projektien hallinta</h2>
        <div class="table-responsive">
            <div class="dropdown mb-3">
                <button class="btn-projektit dropdown-toggle bg-light shadow border rounded" type="button" data-bs-toggle="dropdown">
                    <?= ($_GET['filter'] ?? 'all') === 'own' ? 'Omat projektit' : 'Kaikki projektit' ?>
                </button>

                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="?filter=all">Kaikki projektit</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="?filter=own">Omat projektit</a>
                    </li>
                </ul>
            </div>
            <table class="table table-hover shadow">
                <thead>
                    <tr>
                        <th scope="col"><input class='form-check-input' type="checkbox" id="select-all" /></th>
                        <th scope="col">ID</th>
                        <th scope="col">Projektin nimi</th>
                        <th scope="col">Yritys</th>
                        <th scope="col">Puhelin</th>
                        <th scope="col">Sähköposti</th>
                        <th scope="col">Deadline</th>
                        <th scope="col">Lyhyt kuvaus</th>
                        <th scope="col">Varattu</th>
                        <th scope="col">Kenelle varattu?</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $reserved = $row['PROJECT_RESERVED'] == 1;
                        $reservedTo = htmlspecialchars($row['RESERVED_TO'] ?? '');
                        $id = $row['PROJECT_ID'];

                        $reservedCell = $reserved ? "$reservedTo <button class='btn btn-sm btn-danger ms-2 btn-unreserve' data-id='$id'>x</button>" : "Ei varattu";
                        echo "<tr data-id='$id' data-reserved='{$row['PROJECT_RESERVED']}'>
                                    <td><input class='form-check-input row-checkbox' type='checkbox' value='$id' /></td>
                            <th scope='row'> $row[PROJECT_ID] </th>
                            <td>$row[PROJECT_NAME]</td>
                            <td>$row[COMPANY]</td>
                            <td>$row[PHONE]</td>
                            <td>$row[EMAIL]</td>
                            <td>$row[DEADLINE]</td>
                            <td>$row[SHORT_DESC]</td>
                            <td>$row[PROJECT_RESERVED]</td>
                            <td>$reservedCell</td>
                        </tr> ";
                    }

                    ?>
                </tbody>
            </table>
        </div>
        <!-- Tekoälyn avulla luotu toolbar taulukolle -->
        <div id="bulk-actions" class="d-none d-flex shadow justify-content-between align-items-center p-3 bg-light border rounded mt-2">
            <span id="selected-count">1 valittu</span>
            <div class="bulk-scroll">
                <button id="btn-julkaise" class="btn btn-julkaise me-4">Julkaise</button>
                <button id="btn-varaa" name="varaa_button" class="btn btn-varaa me-4" data-bs-toggle="modal" data-bs-target="#varaaModal">Varaa</button>
                <button id="btn-vie" class="btn btn-vie me-4">Vie CSV</button>
                <button id="btn-poista" class="btn btn-poista">Poista</button>
                <form action="../backend/export.php" method="post">
                    <button class="btn btn-vie me-4" type="submit"> Export all to CSV </button> <!--- Väliaikaisesti tänne? Ainakin tämä toimii. "Vie CSV" näppäimen logiikanp pitäisi miettiä loppuun (esim. että voi viedä jotkut tietyt projektit, eikä kaikki) -->
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- Pop up varaamista varten -->
    <div class="modal fade" id="varaaModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Varaa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="varaaInput" class="form-label">Kenelle varaat?</label>
                    <input type="text" class="form-control" id="varaaInput" placeholder="Varaan projektin henkilölle/ryhmälle...">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Peruuta</button>
                    <button type="button" class="btn btn-primary" id="varaaConfirm">Vahvista</button>
                </div>
            </div>
        </div>
    </div>
    <!--sends POST request to backend-->
    <!-- Tekoälyn luoma koodi, toolbar tulee näkyville, kun valitaan 1 tai useampi checkbox -->
    <script>
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');

        function updateBulkActions() {
            // montako checkboxia on valittu
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const count = checkedBoxes.length;

            if (count > 0) {
                // asetetaan toolbar näkyväksi ja päivitetään teksti sen mukaan, montako on valittu
                bulkActions.classList.remove('d-none');
                selectedCount.textContent = `${count} valittu`;
            } else {
                bulkActions.classList.add('d-none'); // piilotetaan toolbar
                selectedCount.textContent = '0 valittu';
            }

            //update select-all checkbox
            selectAll.checked = count === rowCheckboxes.length;


            const anyReserved = Array.from(checkedBoxes).some(cb => {
                return cb.closest('tr').dataset.reserved == '1';
            });

            document.getElementById('btn-varaa').disabled = anyReserved;
            if (anyReserved) {
                document.getElementById('btn-varaa').title = "Et voi varata jo varattua projektia.";
            } else {
                document.getElementById('btn-varaa').title = '';
            }
        }
        // Add event listeners to all checkboxes
        rowCheckboxes.forEach(cb => cb.addEventListener('change', updateBulkActions));
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkActions();
        });

        document.getElementById('varaaConfirm').addEventListener('click', function() {
            const reservedTo = document.getElementById('varaaInput').value.trim();
            if (!reservedTo) {
                alert('Syötä henkilön/ryhmän nimi.');
                return;
            }

            const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
            fetch('/backend/hallinta_features.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'reserve',
                    ids: ids,
                    reserved_to: reservedTo
                })
            }).then(res => res.json()).then(data => {
                if (data.success) location.reload();
                else alert('Virhe: ' + (data.error ?? 'Tuntematon virhe'));
            });
            bootstrap.Modal.getInstance(document.getElementById('varaaModal')).hide();
        });
        document.querySelectorAll('.btn-unreserve').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                if (!confirm('Haluatko varmasti poistaa varauksen?')) return;

                fetch('/backend/hallinta_features.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'unreserve',
                        ids: [id]
                    })
                }).then(res => res.json()).then(data => {
                    if (data.success) location.reload();
                    else alert('Virhe: ' + (data.error ?? 'Tuntematon virhe'));
                });
            });
        });
    </script>
</body>

</html>