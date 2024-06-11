<?php
session_start();
if (isset($_SESSION['id']))
    $id = $_SESSION["id"];
$is_logged_in = isset($_SESSION['id']);

if ($is_logged_in) {
    $logout_text = "Logout";
    $logout_link = "logout.php";
    $register_or_profile_text = "Profile";
    $register_or_profile_link = "profile.php";
} else {
    $logout_text = "Login";
    $logout_link = "login.html";
    $register_or_profile_text = "Register";
    $register_or_profile_link = "register.html";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" media="screen and (max-width:480px)" href="mobile_review.css">
    <link rel="stylesheet" media="screen and (min-width:480px) and (max-width:1180px)" href="tablet_review.css">
    <title>Matzip for you</title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="<?php echo $logout_link; ?>"><?php echo $logout_text; ?></a></li>
                <li><a href="<?php echo $register_or_profile_link; ?>"><?php echo $register_or_profile_text; ?></a></li>
            </ul>
        </nav>
        <h1><a href="index.php" style="color:black;">Matzip</a></h1>
        <h2>The restaurant recommendation platform</h2>
    </header>
    <hr size="40px" color="white">
    <section>
        <div class="info-section">
            <?php
            $db = mysqli_connect('localhost', 'root', '') or die('Unable to connect. Check your connection parameters.');
            mysqli_select_db($db, 'matzip') or die(mysqli_error($db));

            $query = "SELECT RESTNAME, PRICE, LOC, PHONE, STARTTIME, ENDTIME, T.RESTTYPE, IMG
                FROM RESTAURANT R, RESTTYPE T
                WHERE R.RESTTYPENO=T.RESTTYPENO AND RESTNAME='" . $_GET['restname'] . "'";
            $result = mysqli_query($db, $query) or die(mysqli_error($db));

            while ($row = mysqli_fetch_assoc($result)) {
                extract($row);
                echo '
                    <img src="' . $IMG . '" alt="img not loaded">
                    <div class="restaurant-info">
                        <h3>Information of the restaurant</h3>
                        <div>';
                echo '<pre>Restaurant Name:     ' . $RESTNAME . '</pre>';
                echo '<pre>Average Price:   ' . $PRICE . 'KRW </pre>';
                echo '<pre>Location:    ' . $LOC . '</pre>';
                echo '<pre>Phone:  ' . $PHONE . '</pre>';
                echo '<pre>Start Time:  ' . $STARTTIME . '</pre>';
                echo '<pre>End Time:    ' . $ENDTIME . '</pre>';
                echo '<pre>Restaurant Type: ' . $RESTTYPE . '</pre>';
            }
            ?>
        </div>
        </div>
        </div>
        <div class="review-section">
            <h3>REVIEW</h3>
            <?php
            $query = "SELECT AVG(STAR) AS AVG
            FROM REVIEW, RESTAURANT
            WHERE RESTID=RESTNO AND RESTNAME='" . $_GET['restname'] . "'";
            $result = mysqli_query($db, $query) or die(mysqli_error($db));

            while ($row = mysqli_fetch_assoc($result)) {
                extract($row);
                echo '<p>Average Star = ' . $AVG . '</p>';
            }
            ?>

            <table>
                <tr>
                    <th>USERID</th>
                    <th class="content">content</th>
                    <th>STAR</th>
                </tr>
                <?php
                $query = "SELECT USERID, CONTENT, STAR 
                FROM REVIEW, RESTAURANT
                WHERE RESTID=RESTNO AND RESTNAME='" . $_GET['restname'] . "'";
                $result = mysqli_query($db, $query) or die(mysqli_error($db));

                while ($row = mysqli_fetch_assoc($result)) {
                    extract($row);
                    echo '<tr>';
                    echo '<td>' . $USERID . '</td>';
                    echo '<td>' . $CONTENT . '</td>';
                    echo '<td>' . $STAR . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>

            <div class="review-form">
                <form method="post" action="review_submit.php">
                    <input type="hidden" name="restname" value="<?php echo $_GET['restname']; ?>">
                    <label for="star">STAR</label>
                    <select id="star" name="star" required>
                        <option value="5">5.0</option>
                        <option value="4.5">4.5</option>
                        <option value="4">4.0</option>
                        <option value="3.5">3.5</option>
                        <option value="3">3.0</option>
                        <option value="2.5">2.5</option>
                        <option value="2">2.0</option>
                        <option value="1.5">1.5</option>
                        <option value="1">1.0</option>
                        <option value="0.5">0.5</option>
                    </select>
                    <label for="content">Review</label>
                    <textarea id="content" name="content" rows="4" placeholder="Write your review here..."
                        required></textarea>
                    <button type="submit">Confirm</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Matzip. All rights reserved.</p>
    </footer>
</body>

</html>