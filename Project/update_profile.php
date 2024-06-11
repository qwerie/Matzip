<?php
session_start();
$id = $_SESSION['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id) {
    $name = $_POST['name'];
    $birth = $_POST['birth'];
    $sex = $_POST['sex'];

    $db = mysqli_connect('localhost', 'root', '', 'matzip') or die('Unable to connect. Check your connection parameters.');

    $query = 'UPDATE users SET NAME = ?, BIRTH = ?, SEX = ? WHERE USERID = ?';
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $birth, $sex, $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo '<script>alert("Profile updated successfully."); window.location.href = "profile.php";</script>';
    } else {
        echo '<script>alert("Update failed. Please try again."); window.location.href = "profile.php";</script>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
} else {
    header('Location: profile.php');
}
?>