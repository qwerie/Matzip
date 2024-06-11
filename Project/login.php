<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $pw = $_POST['password'];

    $db = mysqli_connect('localhost', 'root', '', 'matzip') or die('Unable to connect. Check your connection parameters.');

    // SQL 쿼리를 준비된 문으로 작성
    $query = 'SELECT USERID, PASSWORD FROM users WHERE USERID = ? AND PASSWORD = ?';
    $stmt = mysqli_prepare($db, $query);
    
    // 변수 바인딩
    mysqli_stmt_bind_param($stmt, 'ss', $id, $pw);
    
    // 쿼리 실행
    mysqli_stmt_execute($stmt);
    
    // 결과 저장
    mysqli_stmt_store_result($stmt);
    
    // 결과 확인
    if (mysqli_stmt_num_rows($stmt) == 1) {
        // 로그인 성공
        $_SESSION['id'] = $id;
        header('Location: index.php'); // 로그인 후 이동할 페이지
        exit();
    } else {
        // 로그인 실패
        echo 'Invalid ID or password.';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
} else {
    header('Location: login.html');
}
?>