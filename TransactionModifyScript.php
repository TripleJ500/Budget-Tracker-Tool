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
$transactionID = isset($_POST['transactionID']) ? $_POST['transactionID'] : NULL;
$amount = isset($_POST['amount']) ? $_POST['amount'] : NULL;
$company = isset($_POST['company']) ? $_POST['company'] : NULL;
$purchaseDate = isset($_POST['purchaseDate']) ? $_POST['purchaseDate'] : NULL;
$frequency = isset($_POST['frequency']) ? $_POST['frequency'] : NULL;
$transactionType = isset($_POST['transactionType']) ? $_POST['transactionType'] : NULL;

// Initialize and prepare SQL statement to protect against possible SQL injection
$stmt = $db -> stmt_init();
$sql = "UPDATE transactions SET amount = ?, company = ?, 
        purchaseDate = ?, frequency = ?, transactionType = ?
        WHERE transactionID = '$transactionID'";

// If prepared statement fails, prompt user to return to form and try again
if(! $stmt -> prepare($sql))
{
    ?>
    <form action = "TransactionModifyForm.html" method = "post">
        <label>Faulty Statement, Please Try Again.</label>
            <input type = "submit" name = "Return" value = "Return To Form"/>
    </form>
    <?php
    die("Connection error: " . $db -> connect_errno);
}

// Bind parameters passed from HTML form to placeholders in SQL statement.
// Then execute the statement.
$stmt -> bind_param("dsiss",
                     $amount,
                     $company,
                     $purchaseDate,
                     $frequency,
                     $transactionType);

$stmt -> execute();

// Notify user if statement is able to be executed successfully. If not,
// display error number.
if ($stmt -> execute())
{
    echo "Record successfully updated.";
} else
{
    echo "Error: " . $sql . "<br>" . $db -> connect_errno;
}

$db -> close();

// Ask user if they would like to modify another record or return to main
// menu.
?>      
<form>
    <br/>
    <label>Modify Another Record??</label>
</form>
<form action = "TransactionModifyForm.html" method = "post">
    <input type = "submit" name = "Modify Another Record" value = "Return To Modify Form"/>
    <br/>
</form>
<form action = "MainMenu.html" method = "post">
    <input type = "submit" name = "Return to Menu" value = "Return to Main Menu"/>
</form>