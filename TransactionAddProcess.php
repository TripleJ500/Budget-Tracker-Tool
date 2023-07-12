<!DOCTYPE html>
<html>
    <head>
        <title>Add Transaction</title>
        <meta charset = "UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
    </head>
</html>


<?php
//Include the MySQLi object and verify the connection to the database
include("inc_db_transactions.php");

//Retieve all the data that was entered from the HTML form
$amount = isset($_POST['amount']) ? $_POST['amount'] : NULL;
$company = isset($_POST['company']) ? $_POST['company'] : NULL;
$purchaseDate = isset($_POST['purchaseDate']) ? $_POST['purchaseDate'] : NULL;
$frequency = isset($_POST['frequency']) ? $_POST['frequency'] : NULL;
$transactionType = isset($_POST['transactionType']) ? $_POST['transactionType'] : NULL;

// Verify that the necessary information was provided, if not, redirect user
// To HTML form to try again.
if($amount == '' ||
   $purchaseDate == '' ||
   $purchaseDate == 'YYYYMMDD' ||
   $frequency == '')
{
    ?>
    <form action = "TransactionAddForm.html" method = "post">
        <label>You are missing information. Please fill out every field:</label> 
            <input type = "submit" name = "return" value="Return To Last Page" />
    </form>
    <?php
    exit();
}

// Initialize and prepare SQL statement to protect against possible SQL injection
$stmt = $db -> stmt_init();
$sql = "INSERT INTO transactions (amount,
                                  company,
                                  purchaseDate,
                                  frequency,
                                  transactionType)
        VALUES (?, ?, ?, ?, ?)";

// If prepared statement fails, prompt user to return to form and try again
if(! $stmt -> prepare($sql))
{
    ?>
    <form action = "TransactionAddForm.html" method = "post">
        <label>Faulty Statement, Please Try Again.</label>
            <input type = "submit" name = "Return" value = "Return To Form" />
    </form>
    <?php
    $db -> close();    
}

// Bind parameters passed from HTML form to placeholders in SQL statement
$stmt -> bind_param("dsiss",
                     $amount,
                     $company,
                     $purchaseDate,
                     $frequency,
                     $transactionType);

// Execute the SQL statement with all variables passed through
$stmt -> execute();

// Tell user that data has been saved and give them a return to main menu button
echo "The record has been saved.";
?>
<form action = "MainMenu.html" method = "post">
        <input type = "submit" name = "Return to Menu" value = "Return Home"/>
</form>