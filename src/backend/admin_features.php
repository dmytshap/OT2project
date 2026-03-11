<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'connect_to_database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_teacher']) || isset($_POST['delete_teacher'])) {
        $email = $_POST['teacher_email'];

        if (isset($_POST['add_teacher'])) {
            addTeacherGiveRole($email);
        }

        if (isset($_POST['delete_teacher'])) {
            deleteTeacher($email);
        }

        header("Location: ../frontend/admin_panel.php");
        exit();
    }
    if (isset($_POST['delete_invite_links'])) {
        $tokens = $_POST['selected_tokens'] ?? [];

        if (!empty($tokens)) {
            $deleted = deleteInviteLink($tokens);
            $_SESSION['message'] = "$deleted kutsulinki(t) poistettu";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Valitse linkit poistaaksesi.";
            $_SESSION['message_type'] = "warning";
        }
    }
    if (isset($_POST['luo_kutsulinkki'])) {
        include 'generate_invite_link.php';
        $invite_link = generateInviteLink();
        // tätä tarvitaan että tulisi ilmoitus alertUser() admin_panel.php:ssä.
        $_SESSION['invite_link'] = $invite_link;
        header("Location: ../frontend/admin_panel.php");
        exit();
    }

    header("Location: ../frontend/admin_panel.php");
} // if post


// Adds teacher to database and gives role
function addTeacherGiveRole($email)
{

    $connection = connectToDatabase();
    $email = $_POST['teacher_email'];
    $role = "TEACHER";
    $created = date("Y-m-d H:i:s");
    $expiry = date("Y-m-d H:i:s", strtotime("+99 year"));

    $stmtCheckTeacherInDB = $connection->prepare("SELECT 1 FROM PERMANENT_USERS WHERE EMAIL = ? LIMIT 1");
    $stmtCheckTeacherInDB->bind_param("s", $email);
    $stmtCheckTeacherInDB->execute();
    $stmtCheckTeacherInDB->store_result();

    //Notification
    if ($stmtCheckTeacherInDB->num_rows > 0) {
        $_SESSION['message'] = "Email is already a teacher.";
        $_SESSION['message_type'] = "danger";
        $stmtCheckTeacherInDB->close();
        $connection->close();
        return;
    }

    if (!str_ends_with(strtolower($email), 'uef.fi')) {
        die("Vain @uef.fi sähköpostit ovat sallitut opettajille.");
    }
    $stmt = $connection->prepare("INSERT INTO PERMANENT_USERS (EMAIL,USER_ROLE,CREATED,EXPIRY) VALUES(?,?,?,?)");
    $stmt->bind_param("ssss", $email, $role, $created, $expiry);

    //Notification
    if ($stmt->execute()) {
        $_SESSION['message'] = "Teacher added successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error adding teacher.";
        $_SESSION['message_type'] = "danger";
    }
    $stmt->close();
    $connection->close();
}
// Deletes teacher from PERMANENT_USERS table
function deleteTeacher($email)
{
    $connection = connectToDatabase();
    $email = $_POST['teacher_email'];

    $stmt = $connection->prepare("DELETE FROM PERMANENT_USERS WHERE EMAIL = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Teacher deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "No teacher found with that email.";
        $_SESSION['message_type'] = "warning";
    }

    $stmt->close();
    $connection->close();
}


function deleteInviteLink(array $chosen_tokens)
{
    if (empty($chosen_tokens)) {
        return false;
    }
    #Prepared Statement varten, laskee montako tokenejä on valittu ja laittaa niittää $stmt:iin.
    $placeholders = implode(',', array_fill(0, count($chosen_tokens), '?'));

    $connection = connectToDatabase();
    $stmt = $connection->prepare("DELETE FROM INVITE_LINKS WHERE TOKEN IN ($placeholders)");

    #s:ien määrä #stmt varten.
    $strings_amount = str_repeat("s", count($chosen_tokens));
    $stmt->bind_param($strings_amount, ...$chosen_tokens);

    $stmt->execute();
    $deleted = $stmt->affected_rows;
    $stmt->close();
    $connection->close();

    return $deleted;
}
