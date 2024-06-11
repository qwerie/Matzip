<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $name = $_POST['name'];
    $birth = $_POST['birth'];
    $sex = $_POST['sex'];

    if ($password !== $confirm_password) {
        die('Passwords do not match.');
    }

    $db = mysqli_connect('localhost', 'root', '', 'matzip') or die('Unable to connect. Check your connection parameters.');

    $query = 'SELECT * FROM users WHERE USERID=?';
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) == 1) {
        echo "<script>alert('ID already exists, please try again.');
        window.location.href = 'register.html';</script>";
    }
    // Prepared statements to prevent SQL injection
    $query = 'INSERT INTO users (USERID, PASSWORD, NAME, BIRTH, SEX) VALUES (?, ?, ?, ?, ?)';
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 'sssss', $id, $password, $name, $birth, $sex);
    $retCode=mysqli_stmt_execute($stmt);
    /*if (!$retCode){
        echo "<script>alert('ID already exists, please try again.');
        window.location.href = 'index.html';</script>";
    }*/

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $retval;
        echo "<script>retVal=confirm('Registration successful.Do you want to go to Login page?');
        if(retVal) window.location.href = 'login.html'; else window.location.href = 'index.php'; </script>";
    } else {
        echo '<script>alert("Registration failed. Please try again.");
        window.location.href = "register.html";</script>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
} else {
    header('Location: register.html');
}
?>