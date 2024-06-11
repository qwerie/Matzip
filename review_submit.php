<?php
session_start();
if (!isset($_SESSION['id'])){
    echo "<script>alert('You need to login first!')
    window.location.href = 'login.html'</script>";
}
$id = $_SESSION["id"];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $star = $_POST['star'];
    $content = $_POST['content'];
    $restname=$_POST['restname'];
    $restid="";
    $reviewLastId="";

    $db = mysqli_connect('localhost', 'root', '', 'matzip') or die('Unable to connect. Check your connection parameters.');


    $query = "SELECT RESTNO 
    FROM RESTAURANT
    WHERE RESTNAME='".$restname."'";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    while($row=mysqli_fetch_assoc($result)){ //한줄 한줄씩 row에 저장
            extract($row);
            $restid=$RESTNO;
    }
    

   $query = "SELECT MAX(REVIEWID) as MAX
    FROM REVIEW
    WHERE RESTID=".$restid.
    " GROUP BY RESTID";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    while($row=mysqli_fetch_assoc($result)){ //한줄 한줄씩 row에 저장
            extract($row);
            $reviewLastId=$MAX;
        }
    $reviewLastId=(int)$reviewLastId+1;


    // Prepared statements to prevent SQL injection
    $query = 'INSERT INTO review (RESTID,REVIEWID, STAR, CONTENT, USERID) VALUES (?,?, ?, ?, ?)';
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 'sssss', $restid,$reviewLastId, $star, $content, $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $retval;
        echo "<script>alert('Review saved successful!');";
        echo "window.location.href = 'review.php?restname=".$restname."'</script>";
    } else {
        echo '<script>alert("Registration failed. Please try again.");';
        echo "window.location.href = 'review.php?restname=".$restname."'</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
} else {
    header("Location: review.php?".$restname);
}
?>