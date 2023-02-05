<header>
    <div id="leftHeader">
        <a href="index.php">
            <img src="images/logo.png" alt="logo"/>
        </a>
    </div>
    <div id="midHeader">
        <form id="searchBoxForm" action="index.php" method="post" enctype="multipart/form-data">
            <label for="searchBox"></label>
            <input id="searchBox" name="searchBox" type="text"  placeholder="Search"
                <?php if (isset($_POST["searchBox"])) echo " value = " . str_replace(' ','', $_POST['searchBox']); ?>>
            <input id="searchIcon" type="image" src="images/icon.png" alt="search icon">
        </form>
        <div class="dropdown">
            <p class="dropdownSelect">Select Category</p>
            <div class="dropdownContent">

                <?php

                $categories = $_SESSION['categories'];

                foreach ($categories as $category) {
                    /**
                     * @var number $categoryId
                     * @var string $categoryName
                     */
                    extract($category);
                    echo "<a class='dropdownLink' href='category.php?category=$categoryId'>$categoryName Category</a>";
                    echo "</br>";
                }
                ?>

            </div>
        </div>
    </div>
    <div id="rightHeader">
        <div id="cartIcon"><a href="cart.php"><img src="images/cart.png" alt="shopping cart icon"></a></div>

        <?php
        if (isset($_SESSION['cartCount'])) {
            $cartCount = $_SESSION['cartCount'];
        } else {
            $cartCount = 0;
            $_SESSION['cartCount'] = $cartCount;
        }
        ?>
        <div id="cartCount"> <?php echo $cartCount ?> items </div>

        <div id="loginButton"><a href="login.php">login</a></div>
    </div>
</header>