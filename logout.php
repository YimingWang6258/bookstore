<!DOCTYPE html>
<html lang="en">
<head>
    <title>PawPaw - Logout</title>
    <link rel="icon" type="image/x-icon" href="/images/icon.png">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/logout.css">
</head>
<body>
<?php
include('header.php');
?>
<main>
    <h1>&#128077; Log Out &#128077;</h1>
    <?php
    session_start();

    if (isset($_SESSION['user'])) {
        unset($_SESSION['user']);
        session_destroy();
        echo "<p>You have been logged out. </p>";
        echo "<p><a href='login.php'>Back to Login Page</a></p>";
    } else {
        echo "<p><a href='login.php'>Back to Login Page</a></p>";
    }

   ?>

</main>
<?php
include('footer.php');
?>
</body>
</html>