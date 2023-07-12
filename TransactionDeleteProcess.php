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
$transactionID = isset($_POST['transactionID']) ? $_POST['transactionID'] : NULL;

// First check if transaction ID is NULL. If it is, that means the user asked to
// delete all records in the table. If not, we search for the particular record 
// delete it
if ($transactionID == NULL)
{
    $sql = "DELETE FROM transactions";
    $query = $db -> query($sql);

    if ($query)
    {
        echo "Record successfully deleted.";
    } else 
    {
        echo "Error: " . $sql . "<br>" . $db -> connect_errno();
    }
    $db -> close();
} else
{
    $sql = "DELETE FROM transactions WHERE transactionID = '$transactionID'";
    $query = $db -> query($sql);

    if ($query)
    {
        echo "Records successfully deleted.";
    } else 
    {
        echo "Error: " . $sql . "<br>" . $db -> connect_errno;
    }
    $db -> close();
}
// Ask user if they would like to delete another record or return to the 
// main menu.
?>      
<form>
    <br/>
    <label>Delete Another Record??</label>
</form>
<form action = "TransactionDeleteForm.html" method = "post">
    <input type = "submit" name = "Delete Another Record" value = "Return To Delete Form"/>
    <br/>
</form>
<form action = "MainMenu.html" method = "post">
    <input type = "submit" name = "Return to Menu" value = "Return to Main Menu"/>
</form>