<!DOCTYPE html>
<html lang="en">
<head>
    <title>PawPaw - Login</title>
    <link rel="icon" type="image/x-icon" href="/images/icon.png">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<?php
session_start();
include('header.php');
?>

<main>
    <section id = "loginBox">
        <h1 id = titleText>Login Page</h1>
        <?php

        $db = new mysqli('localhost', 'root', 'SPS!23', 'bookstore');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $userId = $_POST['userid'];
            $password = $_POST['password'];

            $query = "SELECT COUNT(adminId) AS count FROM admin WHERE adminName = ? AND adminPassword = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ss', $_POST['userid'], $_POST['password']);
            $stmt->execute();
            $result = $stmt->get_result();
            $adminCount = $result->fetch_assoc();
            if ($adminCount['count'] !== 0) {
                $_SESSION['user'] = $userId;
            }
        }

        if (isset($_SESSION['user'])) {
            header('location: admin.php');
        } else {
            if (isset($userId)) {
                echo "<p id ='errMessage'>User Name or Password are not found. Please try again</p>";
            } else {
                echo "<p>Please enter username and password to log in.</p>";
            }
        }

        echo "<div id='formContainer' >";
        echo "<form id='loginForm' action='login.php' method='post'>";
        echo "<p><label for='userid'>Username: </label>";
        echo "<input type='text' id='userid' name='userid' size='30'/></p>";
        echo "<p><label for='password'>Password :  </label>";
        echo "<input type='password' id='password' name='password' size='30'/></p>";
        echo "<button type='submit' name='login'>Login</button>";
        echo "</form>";
        echo "</div>";

        ?>

    </section>


</main>

<?php
include('footer.php');
?>
</body>
</html>
