<!DOCTYPE html>
<html lang="en">
<head>
    <title>PawPaw - Admin</title>
    <link rel="icon" type="image/x-icon" href="/images/icon.png">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php
session_start();

include('databaseFunctions.php');
initdb();
$_SESSION['categories'] = getCategories($db);

extract($_POST, EXTR_PREFIX_ALL, 'post');

if (isset($post_bookType) and $post_bookType!=0) {
    $selectedBookType = $post_bookType;
}

$bookTypeError = $bookNameError = $bookAuthorError = $bookPriceError = $deleteBookError = $deleteBookMessage = $bookPriceNumError =  "";

if (isset($_POST['submitEnter'])) {
    $errors = false;
    if ($_POST["bookType"]==0) {
        $bookTypeError = "Uh-Oh! Book Type please";
        $errors = true;
    }

    if (empty($_POST["bookName"])) {
        $bookNameError = "Uh-Oh! Book Name please";
        $errors = true;
    }

    if (empty($_POST["bookAuthor"])) {
        $bookAuthorError = "Uh-Oh! Book Author please";
        $errors = true;
    }

    if (empty($_POST["bookPrice"])) {
        $bookPriceError = "Uh-Oh! Book Price please";
        $errors = true;
    } else {
        if (!is_numeric($_POST["bookPrice"])){
            $bookPriceNumError = "Uh-Oh! Number or float please";
            $errors = true;
        }
    }

    if (!$errors) {

        //insert data into book table
        $bookImage = strtolower(str_replace(' ', '_', $_POST['bookName']));
        $query = "INSERT INTO book VALUES (null, /* bookId */
                         ?, /* categoryId */
                         ?, /* title */
                         ?, /* author */
                         ?, /* price */
                         ?, /* image */
                         0 /* readNow */
                         )";
        $stmt = $db->prepare($query);
        $stmt -> bind_param('sssss', $_POST['bookType'], $_POST['bookName'], $_POST['bookAuthor'], $_POST['bookPrice'], $bookImage);
        $stmt->execute();

        $file = $bookImage . ".png";
        $path = "images/" . $file;
        move_uploaded_file($_FILES['bookImage']['tmp_name'], $path);

        header("Location: category.php?category=$selectedBookType");
    }
}

if (isset($_POST['deleteBookName'])) {
    $query = "SELECT COUNT(bookId) AS count FROM book WHERE title = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $_POST['deleteBookName']);
    $stmt->execute();
    $result = $stmt->get_result();
    $deleteBooks = $result->fetch_assoc();
    if ($deleteBooks['count'] == 0) {
        $deleteBookError = "Uh-Oh! Please enter a name, or the book doesn't exist.";
        $errors = true;
    } else {
        $query = "DELETE FROM book WHERE title = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $_POST['deleteBookName']);
        $stmt->execute();
        $deleteBookMessage = "Book Deleted";
        $errors = true;
    }
}

include('header.php');
?>

<main>
    <?php
    if (!isset($_SESSION['user'])) {
        echo "<section id='adminLogIn'>";
        echo "<h1> &#128084; This page is only for administration. Please return when you are logged in. &#128084;</h1>";
        echo "<div id ='loginButton'><a href='login.php'>Login</a></div>";
        echo "</section>";
        exit;
    }
    ?>
    <p id ='logoutButton'><a href="logout.php">Logout</a></p>

    <h1>This is where you can enter information for a new book</h1>
    <form id="bookForm" action="admin.php" method="post" enctype="multipart/form-data">
        <label for="bookType">Enter Book Type</label>
        <select id="bookType" name="bookType">
            <option value="0">Select</option>
            <?php
            $categories = $_SESSION['categories'];
            foreach ($categories as $category) {
                extract($category);
                echo "<option value=$categoryId";
                if (isset($_POST['bookType']) && $_POST['bookType'] == $categoryId) echo " selected";
                echo "> $categoryName </br></option>";
            }
            ?>
        </select>
        <p class="errMessage"><?php echo $bookTypeError ?></p>

        <label for="bookName">Enter Book Name</label>
        <input type="text" id="bookName" name="bookName" placeholder="Enter Name Of The Book"
            <?php if (isset($_POST["bookName"])) echo " value = " . $_POST["bookName"]; ?>>
        <p class="errMessage"><?php echo $bookNameError ?></p>

        <label for="bookAuthor">Enter Book Author</label>
        <input type="text" id="bookAuthor" name="bookAuthor" placeholder="Enter Author Of The Book"
            <?php if (isset($_POST["bookAuthor"])) echo " value = " . $_POST["bookAuthor"]; ?>>
        <p class="errMessage"><?php echo $bookAuthorError ?></p>

        <label for="bookPrice">Enter Book Price</label>
        <input type="text" id="bookPrice" name="bookPrice" placeholder="Enter Price Of The Book">
        <p class="errMessage"><?php echo $bookPriceError . $bookPriceNumError ?></p>

        <label for="bookImage">Upload book image (png file)</label>
        <input type="file" id="bookImage" name="bookImage">

        <br>

        <input type="submit" class="submitButton" name="submitEnter" value="submit">
    </form>

    <h1>This is where you can delete an existed book</h1>
    <form id="bookForm" action="admin.php" method="post" enctype="multipart/form-data">
        <label for="deleteBookName">Enter Book Name To Delete</label>
        <input type="text" id="deleteBookName" name="deleteBookName" placeholder="Enter Name Of The Book">
        <p class="errMessage"><?php echo $deleteBookError . $deleteBookMessage ?></p>
        <input type="submit" class="submitButton" name="submitDelete" value="submit">
    </form>

</main>

<?php
include('footer.php');
?>
</body>
</html>
