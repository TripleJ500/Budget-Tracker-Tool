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
$response = isset($_POST['response']) ? $_POST['response'] : NULL;

// Verify that the necessary information was provided, if not, redirect user
// To HTML form to try again. In this case, ensuring that transactionID isn't 
// empty at the same time that response to "Delete All Records?" is no.
if ($transactionID == '' && $response == 0) 
{ 
?> 
    <form action = "TransactionDeleteForm.html" method = "post">
        <label>You are missing information. Please fill out every field:</label> 
            <input type = "submit" name = "return" value = "Return To Last Page"/>
    </form>
<?php
exit();
}

// Redirect user to try again if they try to input a transaction ID AND also 
// select 'yes' to "Delete All Records?"
if ($transactionID != '' && $response == 1) 
{ 
?> 
    <form action = "TransactionDeleteForm.html" method = "post">
        <label>Please enter an Transaction ID OR delete all records.:</label> 
            <input type = "submit" name = "return" value = "Return To Last Page"/>
    </form>
<?php
exit();
}

// If transaction ID is null, that means user asked to delete all records.
if ($transactionID == NULL)
{
    $i = 0;
    // Query SQL statement. No need to prepare it as no data was passed to the 
    // program; meaning no possibility for SQL injection.
    $sql = "SELECT * FROM transactions";
    $query = $db -> query($sql);
    // Create column name row for the table.
    $headers = array("Transaction ID", "Amount", "Company", "Purchase Date",
                     "Frequency", "Transaction Type");
    echo '<table style = "width:120%">';

    while($i < count($headers))
    {
        $columnInfo = current($headers);
        echo '<th>' .  $columnInfo . '</th>';
        next($headers);
        $i = $i + 1;
    }
    // Retrieve data from table and loop through each row and insert data into
    // table
    while($row = $query -> fetch_row())
    {
        echo '<tr align = "center">';
        $count = count($row);
        $y = 0;
        while($y < $count)
        {
            $c_row = current($row);
            if ($c_row == NULL)
            {
                echo '<td> N/A </td>';
            }
            else
                echo '<td>' . $c_row . '</td>';
            next($row);
            $y = $y + 1;
        }
        echo '</tr>';
    }
    echo "</table>";

    // Ask user to verify the information that is about to be deleted and 
    // and ask if they're sure they want to delete it.
    echo
    "
    <form action = 'TransactionDeleteProcess.php' method = 'post'>
        <label for = 'yes'> Are you SURE you want to delete all records?: </label>
            <input type = 'submit' id = 'yes' name = 'yes' value = 'Yes'>
    </form>
    <form action = 'TransactionDeleteForm.html' method = 'post'>
            <input type = 'submit' id = 'no' name = 'no' value = 'No'> 
    </form>
    ";
    exit();
} 
// If Transaction ID is not NULL, then user asked to delete a specific record
else
{
    $i = 0;
    // Initialize and prepare SQL statement to protect against possible SQL injection
    $stmt = $db -> stmt_init();
    $sql = "SELECT * FROM transactions WHERE transactionID = ?";
    $headers = array("Transaction ID", "Amount", "Company", "Purchase Date",
                     "Frequency", "Transaction Type");

    // If prepared statement fails, prompt user to return to form and try again                 
    if (! $stmt -> prepare($sql))
    {
    ?>
        <form action = "TransactionDeleteForm.html" method = post>
            <label> Faulty Statement, Please Try Again</label>
                <input type = "submit" name = "Return" value = "Return To Form"/>
        </form>
    <?php
        die($db -> connect_errno());
    }

    // Create column name row for the table.
    echo '<table style = "width:120%">';
    while($i < count($headers))
    {
        $columnInfo = current($headers);
        echo '<th>' .  $columnInfo . '</th>';
        next($headers);
        $i = $i + 1;
    }

    // Bind transaction ID pass from HTML form to placeholder variable
    // in SQL statement.
    $stmt -> bind_param("s", $transactionID);
    $stmt -> execute();
    $result = $stmt -> get_result();

    // Retrieve data from table and loop through each row and insert data into
    // table    
    while($array = $result -> fetch_assoc())
    {
        echo '<tr align = "center">';
        $count = count($array);
        $y = 0;
        while($y < $count)
        {
            $c_row = current($array);
            if ($c_row == NULL)
            {
                echo '<td> N/A </td>';
            }
            else
                echo '<td>' . $c_row . '</td>';
            next($array);
            $y = $y + 1;
        }
        echo '</tr>';
    }
    echo "</table>";
    
    // Ask user to verify the information that is about to be deleted and 
    // and ask if they're sure they want to delete it.
    echo
    "
    <form action = 'TransactionDeleteProcess.php' method = 'post'>
        <label for = 'yes'> Are you sure you want to delete this record?: </label>
            <input type = 'hidden' id = 'yes' name = 'transactionID' value = '$transactionID'>
            <input type = 'submit' id = 'yes' name = 'response' value = 'Yes'>
    </form>
    <form action = 'TransactionDeleteForm.html' method = 'post'>
            <input type = 'submit' id = 'no' name = 'no' value = 'No'> 
    </form>
    ";
    exit();
}