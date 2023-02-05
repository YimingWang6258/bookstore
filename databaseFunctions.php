<?php

// This function connects to a mySql database
// It assumes the userid is root 
// You should replace the ???? with your MySQL root password and the name of your database
// It creates an object - $db - that can be used in all subsequent database calls


function initdb()
{
    global $db;
    $db = new mysqli('localhost', 'root','SPS!23', 'bookstore');
    if (mysqli_connect_errno()) {
        echo '<p>Error: Could not connect to database.<br/>
    Please try again later.</p>';
        exit;
    }
}



// This function will retrieve all the categories in the database
// It should be called using the $db object
// It will return an array of arrays, containing fields from the category table
// The field list should be modified if necessary to reflect your database field names 
function getCategories($db)
{
    $query = "SELECT categoryId, categoryName FROM category";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $categories = $result->fetch_all(MYSQLI_ASSOC);
    return $categories;
}



// This function will retrieve all the books for one categoryId
// It should be called using the $db object and one categoryId
// It will return an array of arrays, containing fields from the book table
// The field list should be modified, if necessary, to reflect your database field names
function getBooks($db, $selectedCategory) {
    $query = "SELECT bookId, title, author, price, readNow
                        FROM book
                        WHERE book.categoryId = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $selectedCategory);

    $stmt->execute();
    $result = $stmt->get_result();
    $bookArray = $result->fetch_all( MYSQLI_ASSOC);
    return $bookArray;
}

// This function will retrieve data relating to a specific book
// It should be called using the $db object and one bookId
// It will return one array containing fields for the specified book
// The field list should be modified as necessary to reflect your database field names
function getBookDetails($db, $bookId) {
    $query = "SELECT bookId, title, price
                        FROM book
                        WHERE book.bookId = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $bookId);

    $stmt->execute();
    $result = $stmt->get_result();
    $bookDetails = $result->fetch_assoc();
    return $bookDetails;
}


?>
