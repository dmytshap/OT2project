<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'connect_to_database.php';

$email = $_POST['teacher_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_teacher'])) {
        addTeacherGiveRole($email);
    }
    if (isset($_POST['delete_teacher'])) {
        deleteTeacher($email);
    }

    header("Location: /frontend/admin_panel.php");
    exit();
}


// Adds teacher to database and gives role
function addTeacherGiveRole($email) {
    
    $connection = connectToDatabase();
    $email = $_POST['teacher_email'];
    $role = "TEACHER";
    $created = date("Y-m-d H:i:s");
    $expiry = date("Y-m-d H:i:s", strtotime("+99 year"));

    $stmtCheckTeacherInDB = $connection -> prepare("SELECT 1 FROM PERMANENT_USERS WHERE EMAIL = ? LIMIT 1");
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
    $stmt -> bind_param("ssss",$email,$role,$created, $expiry);

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
function deleteTeacher($email) {
    $connection = connectToDatabase();
    $email = $_POST['teacher_email'];

    $stmt = $connection-> prepare("DELETE FROM PERMANENT_USERS WHERE EMAIL = ?");
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


?>