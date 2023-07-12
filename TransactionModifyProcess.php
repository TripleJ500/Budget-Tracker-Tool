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

// Verify that the necessary information was provided, if not, redirect user
// To HTML form to try again. In this case, ensuring that transactionID isn't 
// empty.
if($transactionID == '')
{
    ?>
    <form action = "TransactionModifyForm.html" method = "post">
        <label>You are missing information. Please fill out every field:</label> 
            <input type = "submit" name = "return" value="Return To Last Page" />
    </form>
    <?php
    exit();
}

// Initialize and prepare SQL statement to protect against possible SQL injection
$stmt = $db -> stmt_init();
$sql = "SELECT * FROM transactions WHERE transactionID = ?";

// If prepared statement fails, prompt user to return to form and try again
if(! $stmt -> prepare($sql))
{
    ?>
    <form action = "TransactionModifyForm.html" method = "post">
        <label>Faulty Statement, Please Try Again.</label>
            <input type = "submit" name = "Return" value = "Return To Form"/>
    </form>
    <?php
    $db -> close();    
}

// Bind parameters passed from HTML form to placeholders in SQL statement.
// Then execute the statement and store the resturned array inside the 
// result variable.
$stmt -> bind_param("i", $transactionID);
$stmt -> execute();
$result = $stmt -> get_result();

if ($result -> num_rows > 0)
{
    // Assign the values of the array to the corresponding variable in PHP
    $result = $result -> fetch_assoc();
    $amount = $result['amount'];
    $company = $result['company'];
    $purchaseDate = $result['purchaseDate'];
    $frequency = $result['frequency'];
    $transactionType = $result['transactionType'];

    // HTML script that will show the exact same text fields and setup
    // as the add transaction form. The only difference here is that the 
    // text fields are pre-populated with the data from the data from the
    // queried transaction ID
    echo 
    "
    <html>
    <body>
    <form action = 'TransactionModifyScript.php' method = 'post'>
        Advisor ID: <br>$transactionID</br>
            <input type = 'hidden' name = 'transactionID' value = $transactionID>
        <br>
        Amount:
            <input type = 'text' name = amount value = '$amount'>
        Company:
            <input type = 'text' name = company value = '$company'>
        Purchase Date:
            <input type = 'text' name = purchaseDate value = '$purchaseDate'>
            *Remember, enter date as: YYYYMMDD (i.e. 20230509)
            <br><br>
        Frequency:
            <select id = 'frequency' name = 'frequency'> 
                <option value = '$frequency' selected>$frequency</option>
                <option value = 'One-Time' >One-Time</option>
                <option value = 'Weekly'   >Weekly</option>
                <option value = 'Monthly'  >Monthly</option>
                <option value = 'Quarterly'>Quarterly</option>
                <option value = 'Annually' >Annually</option>
            </select>
        Transaction Type:
            <select id = 'transactionType' name = 'transactionType'> 
                <option value = '$transactionType' selected>$transactionType</option>
                <option value = 'Gas'>Gas</option>
                <option value = 'Bill'>Bill</option>
                <option value = 'Food'>Food</option>
                <option value = 'Other'>Other</option>
                <option value = 'Shopping'>Shopping</option>
                <option value = 'Groceries'>Groceries</option>
                <option value = 'Entertainment'>Entertainment</option>
            </select>
        <input type = 'submit' name = 'submit' value = 'Submit Information'>
        </form>
        </body>
        </html>
        ";
    } 
    // If transaction ID provided is not in the table, alert user and 
    // prompt them to return to HTML form and enter a different ID.
    else 
    {
        ?> 
        <form action = "TransactionModifyForm.html" method = "post">
            <label>Transaction record not found; please try again:</label> 
                <input type = "submit" name = "return" value = "Return To Last Page" />
        </form>
        <?php
    }
    $db -> close();

    //Ask User if they would like to modify a different record
    ?>
    <form>
        <br/>
        <label>Modify A Different Record?</label>
    </form>
    <form action = "TransactionModifyForm.html" method = "post">
        <input type = "submit" name = "Modify Different Record" value = "Return To Modify Form"/>
        <br/>
    </form>