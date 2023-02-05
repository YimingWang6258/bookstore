<!DOCTYPE html>
<html lang="en">
<head>
    <title>PawPaw - Welcome</title>
    <link rel="icon" type="image/x-icon" href="/images/icon.png">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<?php

session_start();

$_SESSION['selectedCategory'] = '';

include('databaseFunctions.php');
initdb();
$_SESSION['categories'] = getCategories($db);

include('header.php');


if (count($_POST) != 0) {
    $errors = false;

    if (empty($_POST['searchBox'])) {
        echo "<h3 id='errMessage'> Uh-Oh! Please enter a name</h3>";
        $errors = true;
    } else {
        $query = "SELECT COUNT(bookId) as count
                  FROM book WHERE title LIKE ?
                  OR author LIKE ?";
        $stmt = $db->prepare($query);

        $titleSearch = "%" . $_POST['searchBox'] . "%";
        $authorSearch = "%" . $_POST['searchBox'] . "%";

        $stmt->bind_param('ss', $titleSearch, $authorSearch);
        $stmt->execute();
        $result = $stmt->get_result();
        $searchBooksCount = $result->fetch_assoc();

        if (($searchBooksCount['count']) == 0) {
            echo "<h3 id='errMessage'> Uh-Oh! The book doesn't exist. </h3>";
            $errors = true;
        }
    }

    if (!$errors) {

        $query = "SELECT categoryId, bookId,
              title, author, price, image, readNow
              FROM book WHERE title LIKE ?
              OR author LIKE ?";
        $stmt = $db->prepare($query);

        $titleSearch = "%" . $_POST['searchBox'] . "%";
        $authorSearch = "%" . $_POST['searchBox'] . "%";

        $stmt->bind_param('ss', $titleSearch, $authorSearch);
        $stmt->execute();
        $result = $stmt->get_result();
        $searchBooks = $result->fetch_all(MYSQLI_ASSOC);
    }
}

?>

<main>
    <section id="mainLeft">
        <h1>Welcome to the PawPaw Bookstore, Animal Lovers!</h1>
        <p class="welcomeText">We have four categories of books: Cat, Dog, Lion, and Pig. And we hope you can find the
            book you like!</p>
        <p class="welcomeText">Our price is unbeatable!</p>
        <p class="welcomeText">Enjoy your book-shopping trip! If you have some questions, please email us anytime you
            like. We will respond within 24 hours.&#128151;</p>
    </section>
    <section id="mainRight">

        <?php
        /**
         * @var array $categories
         * @var number $categoryId
         * @var string $categoryName
         */

        if (isset($searchBooks)) {

            echo "<h1>Shop by Results:</h1>
                  <div class='category'>";

            foreach ($searchBooks as $searchBook) {
                extract($searchBook);
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
        } else {
            echo "<h1>Shop by Category:</h1>
                  <div class='category'>";

            foreach ($categories as $category) {

                extract($category);
                $image = strtolower($categoryName) . "_category";

                echo "<div class='categoryDetail'>\n
                      <h1><a href='category.php?category=$categoryId'>$categoryName Category </a></h1>\n
                      <p><a href='category.php?category=$categoryId'><img src='images/$image.png' alt='$image'></a></p>\n
                      </div>";
            }
        }
        ?>

        </div> <!--matches with the <div> line 110 or line 91-->
    </section>
</main>

<?php
include('footer.php');
?>

</body>
</html>
