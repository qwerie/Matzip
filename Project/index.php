<?php
session_start();
$is_logged_in = isset($_SESSION['id']); // 로그인 상태 여부를 저장할 변수, 여기서는 간단히 false로 초기화합니다.
if (isset($_GET['recommend_mode']))
    $recommend_mode=false;
else
    $recommend_mode=true;

if ($is_logged_in) {
    $logout_text = "Logout";
    $logout_link = "logout.php";
    $register_or_profile_text = "Profile";
    $register_or_profile_link = "profile.php";
} else {
    // 로그인하지 않은 상태라면 "login" 글자와 "login.html"로의 링크를 표시합니다.
    $logout_text = "Login";
    $logout_link = "login.html";
    $register_or_profile_text = "Register";
    $register_or_profile_link = "register.html";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Matzip for you
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Noto+Serif' rel='stylesheet'>
    <link rel="stylesheet" media="screen and (max-width:480px)" href="mobile_index.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            border: 0;
            font-family: 'Noto Serif';
        }

        /*header {
            background-color: #D9D9D9;
            width: device-width;
            padding-bottom: 20px;
        }*/
        header,
        footer {
            background-color: #D9D9D9;
            width: 100%;
            padding: 20px 0;
            text-align: center;
        }

        /*nav{
            background-color: white;
        }*/
        body {
            background-color: #8E8E8E;
        }

        h1,
        h2 {
            text-align: center;
        }

        h3 {
            padding: 20px;
        }

        h1 {
            font-size: 64px;
        }

        h2 {
            font-size: 24px;
            color: #8C8C8C;
        }

        nav {
            display: block;
            padding: 10px;
        }

        .logins ul {
            display: flex;
            justify-content: right;
        }

        .logins li {
            list-style: none;
            margin-right: 20px;
        }

        a {
            text-decoration: none;
        }

        section {
            display: grid;
            grid-template-columns: auto auto auto;
        }

        .img-container {
            width: 100%;
            padding-top: 100%;
            /* 이미지 1:1로 만들기*/
            position: relative;
            overflow: hidden;
        }

        .img-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /*display: block;
            margin-left: auto;
            margin-right: auto;*/
        }

        /*img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            height: auto;
        }*/

        .text {
            position: absolute;
            color: black;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1rem;
            font-weight: bold;
            /*-webkit-text-stroke: 1px black;*/
            background-color: #D9D9D9;
        }

        .highlight {
            display: inline;
            box-shadow: inset 0 -10px 0 #D9FCDB;
            /*-10px은 highlight의 두께*/
        }

        a {
            text-decoration: none;
        }

        #menu-button {
            font-size: 24px;
            background: none;
            border: none;
            color: black;
            cursor: pointer;
            position: absolute;
            left: 10px;
            top: 10px;
        }

        #side-menu {
            z-index: 1000;
            position: fixed;
            top: 0;
            left: -300px; /* Hidden by default */
            width: 250px;
            height: 100%;
            color: white;
            background-color: #333;
            padding-top: 60px;
            transition: left 0.3s ease;
        }

        #side-menu ul {
            list-style: none;
        }

        #side-menu ul li {
            padding: 10px 20px;
        }

        #side-menu ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /*background: rgba(0, 0, 0, 0.5);*/
            display: none;
            z-index: 999;
        }
    </style>
</head>

<body>
    <header>
    <button id="menu-button">☰</button>
        <nav class="logins">
            <ul>
                <li><a href="<?php echo $logout_link; ?>"><?php echo $logout_text; ?></a></li>
                <li><a href="<?php echo $register_or_profile_link; ?>"><?php echo $register_or_profile_text; ?></a></li>
            </ul>
        </nav>
        <h1 id="Home"><a href="index.php" style="color:black;">Matzip</a></h1>
        <h2>The restaurant recommendation platform</h2>
        <br>
    </header>
    <div id="overlay"></div>
    <nav id="side-menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="<?php echo $logout_link; ?>"><?php echo $logout_text; ?></a></li>
            <li style="color:red !important;"><a href="index.php?recommend_mode=false">Show me all restaurants</a></li>
            <li style="color:pink !important;"><a href="index.php">Recommend me a good restaurant!</a></li>
            <?php if($is_logged_in) echo "<li><a href=\"profile.php\">Profile</a></li>"?>
        </ul>
    </nav>
    <hr size="40px" color="white">
    <?php if ($recommend_mode) echo "<h3 style='text-align: center;'>These are recommendations for you!</h2>"; ?>
        <section>
            <?php
            $db = mysqli_connect('localhost', 'root', '') or die('Unable to connect. Check your connection parameters.');
            mysqli_select_db($db, 'matzip') or die(mysqli_error($db));
            if ($recommend_mode){
                $numbers = range(1, 15);
                shuffle($numbers);
                $random_numbers = array_slice($numbers, 0, 3);
                $temp = "(" . $random_numbers[0] . "," . $random_numbers[1] . "," . $random_numbers[2] . ")";
                $query = "SELECT RESTNAME, IMG
                FROM RESTAURANT WHERE RESTNO IN" . $temp;
            }
            else{
                $query = "SELECT RESTNAME, IMG FROM RESTAURANT";
            }
            $result = mysqli_query($db, $query) or die(mysqli_error($db));

            while ($row = mysqli_fetch_assoc($result)) {
                extract($row);
                echo '<div class="img-container">
                    <a href="./review.php?restname=' . $RESTNAME . '">
                        <picture>
                            <source srcset="img/' . $RESTNAME . '.jpg">
                            <img src="' . $IMG . '" alt="img not loaded">
                        </picture>       
                    </a>
                    <div class="text">' . $RESTNAME . '</div>
                    </div>';
            }
            ?>
        </section>

        <footer>
            <p>&copy; 2024 Matzip. All rights reserved.</p>
        </footer>
        <script>
        document.getElementById('menu-button').addEventListener('click', function() {
            document.getElementById('side-menu').style.left = '0';
            document.getElementById('overlay').style.display = 'block';
        });

        document.getElementById('overlay').addEventListener('click', function() {
            document.getElementById('side-menu').style.left = '-300px';
            document.getElementById('overlay').style.display = 'none';
        });

        document.getElementById('Home').addEventListener('click', function(){
            <?php
                $recommend_mode=true;
                ?>
        })
    </script>
</body>

</html>