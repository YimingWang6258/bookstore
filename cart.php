<!doctype html>
<html lang="en">
<head>
    <title>PawPaw - Cart</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="/images/icon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/cart.css">
</head>

<body>

<?php
session_start();
include('databaseFunctions.php');

// add to cart
extract($_GET, EXTR_PREFIX_ALL, 'get');
if (isset($get_bookId)) {
    $_SESSION['cartCount']++;

    if (!isset($_SESSION['cartItems'])) {
        $cartItems = array();
    } else {
        $cartItems = $_SESSION['cartItems'];
    }

    if (!isset($cartItems[$get_bookId])) {
        $cartItem = array('bookId' => $get_bookId, 'title' => $get_title, 'price' => $get_price, 'quantity' => 1);
        $cartItems[$get_bookId] = $cartItem;
    } else {
        $cartItems[$get_bookId]['quantity']++;
    }

    $_SESSION['cartItems'] = $cartItems;
}

//clear cart
if (isset($_GET['action'])) {
    if ($_GET['action'] == "clear") {
        $cartCount = 0;
        $_SESSION['cartCount'] = $cartCount;
        unset($_SESSION['cartItems']);
        $cartItems = array();
    }
}

// update quantity
extract($_POST, EXTR_PREFIX_ALL, 'post');
$postQuantityError = "";

if (isset($post_quantity)) {
    $errors = false;
    $cartItems = $_SESSION['cartItems'];

    if ($post_quantity < 0) {
        $postQuantityError = "Uh-Oh! Valid number must be entered";
        $errors = true;
        $post_quantity = 1;
    } else {
        $cartItems[$post_bookId]['postQuantityError'] = '';
    }

    $difference = $post_quantity - $cartItems[$post_bookId]['quantity'];
    $cartItems[$post_bookId]['quantity'] = $post_quantity;
    $_SESSION['cartCount'] += $difference;

    if ($post_quantity == 0) {
        unset ($cartItems[$post_bookId]);
    }

    $_SESSION['cartItems'] = $cartItems;

}


if (isset($_SESSION['selectedCategory'])) {
    $selectedCategory = $_SESSION['selectedCategory'];
} else {
    $selectedCategory = '';
}

include('header.php')
?>

<main>

    <h1>Your Shopping Cart</h1>
    <section id="topSection">

        <a href="cart.php?action=clear" class="commandButton">Clear Cart</a>

        <a href="checkout.php" class="commandButton">Proceed to Checkout</a>

        <?php if (!empty($selectedCategory)) {
            $selectedCategory = $_SESSION['selectedCategory'];
            echo " <a href='category.php?category=$selectedCategory' class='commandButton'>Continue Shopping</a>";
        } else {
            echo " <a href='index.php' class='commandButton'>Continue Shopping</a>";
        }

        ?>
    </section>

    <section id="midSection">

        <?php
        $cartCount = $_SESSION['cartCount'];
        if ($cartCount == 0) {
            echo "<h1 class='emptyCart'> &#128148; Uh-Oh! Your cart is empty &#128148; </h1>";
        } else {
            echo "
        <div id='cartGrid'>
            <div class='gridHeader gridTitle'>Title</div>
            <div class='gridHeader gridQuantity'>Quantity</div>
            <div class='gridHeader gridPrice'>Price</div>
            <div class='gridHeader gridTotal'>Total Price</div>";

            $cartItems = $_SESSION['cartItems'];
            $cartTotal = 0;

            foreach ($cartItems as $cartItem) {

                extract($cartItem, EXTR_PREFIX_ALL, "item");

                echo "      
            <div class='gridTitle'> $item_title </div>
            <div class='gridQuantity'>              
                <form method='post' action='cart.php'>
                <label for='quantity'></label>
                <input type='number' id='quantity' name='quantity' value=$item_quantity>
                <input type='submit' value='update' class='gridButton'>
                <input type='hidden' name='bookId' value= $item_bookId>
                </form>               
            </div>";

                $totalPrice = $item_price * $item_quantity;
                $cartTotal += $totalPrice;

                echo "
            <div class='gridPrice'>$item_price</div>
            <div class='gridTotal'>$totalPrice</div>";
            }

            echo "
        </div>
         <p class='errMessage'> $postQuantityError </p>   
    </section>
          
    <section id='bottomSection'>
    <!-- Only show totals if something is in the cart-->
    <h2>You have $cartCount item in the cart</h2>
    <h2>Cart Total: $cartTotal </h2>
    </section>";

            $_SESSION['cartTotal'] = $cartTotal;

        }
        ?>

</main>

<?php
include('footer.php');
?>

</body>
</html>



