<?php
session_start();
$id = $_SESSION['id'] ?? null;

if (!$id) {
    echo "<script>alert('No Login information!');</script>";
    echo "<script> window.location.href = 'index.php'</script>";
    exit;
}

$db = mysqli_connect('localhost', 'root', '', 'matzip') or die('Unable to connect. Check your connection parameters.');

$query = 'SELECT USERID, NAME, BIRTH, SEX FROM users WHERE USERID = ?';
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, 's', $id);
mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $id, $name, $birth, $sex);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            

            margin: 0;
        }
        nav, section{
            display: block;
        }
        section{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }
        ul {
            display: flex;
            justify-content: right;
        }

        li {
            list-style: none;
            margin-right: 20px;
        }
        a{
            text-decoration: none; 
        }
        .profile-container {
            background-color: #ffffff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .profile-container h2 {
            margin-bottom: 20px;
            color: #333333;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .update-button {
            width: 100%;
            padding: 10px;
            background-color: #8E8E8E;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .update-button:hover {
            background-color: #8E8E8E;
        }
    </style>
</head>
<body>
    <nav>
            <ul>
                <li><a href="index.php">Matzip</a></li>
            </ul>
        </nav>
    <section>
    <div class="profile-container">
        <h2>Profile</h2>
        <form method="post" action="update_profile.php">
            <label>ID:</label>
            <input type="text" class="input-field" name="id" value="<?php echo htmlspecialchars($id); ?>" readonly>
            <label>Name:</label>
            <input type="text" class="input-field" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            <label>Birth Date:</label>
            <input type="date" class="input-field" name="birth" value="<?php echo htmlspecialchars($birth); ?>" required>
            <label>Gender:</label>
            <select class="input-field" name="sex">
                <option value='0' <?php if ($sex == '0') echo 'selected'; ?>>Male</option>
                <option value='1' <?php if ($sex == '1') echo 'selected'; ?>>Female</option>
            </select>
            <button type="submit" class="update-button">Update</button>
        </form>
    </div></section>
</body>
</html>