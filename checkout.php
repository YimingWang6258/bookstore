<!doctype html>
<html lang="en">
<head>
    <title>PawPaw - Checkout</title>
    <link rel="icon" type="image/x-icon" href="/images/icon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <link rel="stylesheet" type="text/css" href="css/checkout.css"/>

</head>
<body>

<?php
session_start();

if (!isset($_SESSION['cartCount']) || $_SESSION['cartCount']== 0) {
    header("Location: confirmation.php");
} else {
    $cartCount = $_SESSION['cartCount'];
}

include('header.php');
?>

<main>

    <section id="topSection">
        <h1>Checkout</h1>
    </section>

    <section id="bottomSection">
        <section id="dataForm">
            <p id="formTitleText">In order to purchase the items in your shopping cart, please provide the
                following information:</p>
            <!-- TODO Create a form for customer information -->

            <?php
            $nameError = $addressError = $phoneError = $emailError
                = $cardError = $monthError = $yearError = "";

            if (count($_POST) != 0) {

                $errors = false;

                if (empty($_POST["customerName"])) {
                    $nameError = "Uh-Oh! Your name please";
                    $errors = true;
                }

                if (empty($_POST["customerAddress"])) {
                    $addressError = "Uh-Oh! Your address please";
                    $errors = true;
                }

                if (empty($_POST["customerPhone"])) {
                    $phoneError = "Uh-Oh! Your phone number please";
                    $errors = true;
                } else {
                    $regex = "/^[0-9]{10}+$/";
                    if (!preg_match($regex, $_POST["customerPhone"])) {
                        $phoneError = "Uh-Oh! Your phone number is invalid";
                        $errors = true;
                    }
                }

                if (empty($_POST["customerEmail"])) {
                    $emailError = "Uh-Oh! Your email please";
                    $errors = true;
                } else {
                    $regex = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
                    if (!preg_match($regex, $_POST["customerEmail"])) {
                        $emailError = "Uh-Oh! Your email is invalid";
                        $errors = true;
                    }
                }


                if (empty($_POST["customerCardNumber"])) {
                    $cardError = "Uh-Oh! Your card number please";
                    $errors = true;
                } else {
                    $regex = "/^4[0-9]{12}(?:[0-9]{3})?$/";
                    if (!preg_match($regex, $_POST["customerCardNumber"])) {
                        $cardError = "Uh-Oh! Your card number is invalid";
                        $errors = true;
                    }
                }

                if ($_POST["month"] == "0") {
                    $monthError = "Uh-Oh! Please select month";
                    $errors = true;
                }

                if ($_POST["year"] == "0") {
                    $yearError = "Uh-Oh! Please select year";
                    $errors = true;
                }

                if (!$errors) {
                    header("Location: confirmation.php");
                }
            }

            ?>

            <form id="checkoutForm" method="post" action="checkout.php">

                <label for="customerName">Customer Name</label>
                <input type="text" id="customerName" name="customerName"
                    <?php if (isset($_POST["customerName"])) echo " value = " . $_POST["customerName"]; ?>>
                <p class="errMessage"><?php echo $nameError ?></p>

                <label for="customerAddress">Address</label>
                <input type="text" id="customerAddress" name="customerAddress"
                    <?php if (isset($_POST["customerAddress"])) echo " value = " . $_POST["customerAddress"]; ?>>
                <p class="errMessage"><?php echo $addressError ?></p>

                <label for="customerPhone">Phone</label>
                <input type="text" id="customerPhone" name="customerPhone" placeholder="1234567890"
                    <?php if (isset($_POST["customerPhone"])) echo " value = " . $_POST["customerPhone"]; ?>>
                <p class="errMessage"><?php echo $phoneError ?></p>

                <label for="customerEmail">Email</label>
                <input type="email" id="customerEmail" name="customerEmail"
                    <?php if (isset($_POST["customerEmail"])) echo " value = " . $_POST["customerEmail"]; ?>>
                <p class="errMessage"><?php echo $emailError ?></p>

                <label for="customerCardNumber">Credit Card Number</label>
                <input type="text" id="customerCardNumber" name="customerCardNumber" placeholder="VISA CARD ONLY"
                    <?php if (isset($_POST["customerCardNumber"])) echo " value = " . $_POST["customerCardNumber"]; ?>>
                <p class="errMessage"><?php echo $cardError ?></p>

                <label for="dateMonth">Exp. Date</label>
                <select id="dateMonth" name="month">
                    <option value="0">Select</option>
                    <?php for ($i = 1; $i <= 12; $i++) {
                        $month = date('F', strtotime("2022-$i-01"));
                        echo "<option value=$i";
                        if (isset($_POST['month']) && $_POST['month'] == $i) echo " selected";
                        echo "> $month </br></option>";
                    }
                    ?>
                </select>
                <p class="errMessage"><?php echo $monthError ?></p>

                <label for="dateYear"></label>
                <select id="dateYear" name="year">
                    <option value="0">Select</option>
                    <?php
                    $currentYear = date("Y");
                    for ($i = $currentYear; $i <= $currentYear + 10; $i++) {
                        echo "<option value=$i";
                        if (isset($_POST['year']) && $_POST['year'] == $i) echo " selected";
                        echo "> $i </option>";
                    }
                    ?>
                </select>
                <p class="errMessage"><?php echo $yearError ?></p>

                <br>
                <input type="submit" value="Submit" class="submitButton">

            </form>
        </section>
        <section id="checkoutSummary">
            <ul>
                <li>Next day delivery is guaranteed.</li>
                <li>A $5.00 shipping fee is applied to all orders</li>
            </ul>
            <div id="checkoutTotals">
                <?php

                $cartTotal = $_SESSION['cartTotal'];
                $shippingFee = 5;
                $checkoutTotal = $cartTotal + $shippingFee;

                echo "
                <div>Cart Subtotal</div>
                <div> $$cartTotal </div>

                <div>Shipping Fee</div>
                <div>$$shippingFee</div>

                <div class='total'>Total</div>
                <div class='total'> $$checkoutTotal</div>";
            ?>
            </div>
        </section>
    </section>
</main>
<?php
include('footer.php')
?>
</body>
</html>

