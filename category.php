<!doctype html>
<html lang="en">
<head>
    <title>PawPaw - Category</title>
    <link rel="icon" type="image/x-icon" href="/images/icon.png">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/category.css">
</head>
<body>
<?php
session_start();

include('databaseFunctions.php');

if (isset($_GET['category'])) {
    $selectedCategory = $_GET['category'];
} else {
    $selectedCategory = 1;
}

$db = new mysqli('localhost', 'root','SPS!23', 'bookstore');

$query = "SELECT bookId, title, author, price, readNow, categoryId, image
                        FROM book
                        WHERE book.categoryId = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $selectedCategory);
$stmt->execute();
$result = $stmt->get_result();
$bookArray = $result->fetch_all( MYSQLI_ASSOC);

$_SESSION['selectedCategory'] = $selectedCategory;

include('header.php');

?>
<main>
    <section id="mainLeft">
        <?php

        /**
         * @var array $categories
         * @var number $categoryId
         * @var string $categoryName
         */

        $categories = $_SESSION['categories'];

        foreach ($categories as $category) {

            extract($category);

            if ($categoryId == $selectedCategory) {
                echo "<p class='selectedCategoryButton'><a href='category.php?category=$categoryId'>$categoryName Category</a></p>";
            } else {
                echo "<p class='notSelectedCategoryButton'><a href='category.php?category=$categoryId'>$categoryName Category</a></p>";
            }

        }
        ?>

    </section>
    <section id="mainRight">

        <?php

        foreach ($bookArray as $book) {

            extract($book);

            if ($categoryId == $selectedCategory) {
                echo "<div class='product'>\n
                  <div><img src='images/$image.png' alt='$image'></div>\n
                  <div class='productDetail'>\n
                  <p class='bookTitle'>$title</p>\n
                  <p class='bookAuthor'>by $author</p>\n
                  <p class='bookPrice'>$$price</p>\n
                  <p class='button'><a href='cart.php?bookId=$bookId&title=$title&price=$price'>Add To Cart</a></p>";
                if ($readNow == 1) {
                    echo "<p class='button'><a href='#'>Read Now</a></p>";
                }
                echo "</div>\n
                  </div>";
            }
        }

        ?>

    </section>
</main>
<?php
include('footer.php');
?>
</body>
</html>