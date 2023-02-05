<!doctype html>
<html lang="en">
<head>
    <title>PawPaw - Confirmation</title>
    <link rel="icon" type="image/x-icon" href="/images/icon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <link rel="stylesheet" type="text/css" href="css/confirmation.css"/>
</head>
<body>

<?php
session_start();

include('header.php');
?>

<main>
    <?php
    if (!isset($_SESSION['cartCount']) || $_SESSION['cartCount']== 0) {
        echo "<h1> &#128176; Nothing to Check Out  &#128176; </h1>
              <p><a href='index.php' class='commandButton'>Return to Home</a></p>";
    } else {
        echo "<h1> &#129346; Thank you for your order. Have a great day! &#129346; </h1>
        <p><a href='index.php' class='commandButton'>Return to Home</a></p>";

        session_destroy();
    }

    ?>
</main>

<?php
include('footer.php');
?>

</body>
</html>
