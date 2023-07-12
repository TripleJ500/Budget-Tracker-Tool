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
$month = isset($_POST['month']) ? $_POST['month'] : NULL;
$year = isset($_POST['year']) ? $_POST['year'] : NULL;
$response = isset($_POST['response']) ? $_POST['response'] : NULL;

// Verify that the necessary information was provided, if not, redirect user
// To HTML form to try again. In this case, ensuring that transactionID, 
// month and year aren't all empty while response is no and ensuring that 
// month, year, and transaction ID aren't filled out at the same time 
if($transactionID == '' && ($month == '' && $year == '') && $response == 0)
{
    ?>
    <form action = "TransactionQueryForm.html" method = "post">
        <label>You are missing information. Please fill out every field:</label> 
            <input type = "submit" name = "return" value="Return To Last Page" />
    </form>
    <?php
    exit();
}

if(($month != '' && $year != '')  && $transactionID != '')
{
    ?>
    <form action = "TransactionQueryForm.html" method = "post">
        <label>You can only search using transaction ID OR month & year:</label> 
            <input type = "submit" name = "return" value="Return To Last Page" />
    </form>
    <?php
    exit();
}

$sql;
$result;
// If response is yes, show all records in the transaction table as well as
// summing up the total cost by company, frequency, and transaction type.
if($response == 1)
{
    $i = 0;
    $sql1 = "SELECT * FROM transactions";
    $sql2 = "SELECT transactionType AS 'Transaction Type', SUM(amount) 
             AS 'Total Cost' FROM transactions GROUP BY transactionType";
    $sql3 = "SELECT company AS 'Company', SUM(amount) AS 'Total Cost' 
             FROM transactions GROUP BY company";
    $sql4 = "SELECT frequency AS 'Frequency', SUM(amount) AS 'Total Cost' 
             FROM transactions GROUP BY frequency";  
    
    // Query all SQL statements. No need to prepare it as no data was passed to  
    // the program; meaning no possibility for SQL injection.
    $result1 = $db -> query($sql1);
    $result2 = $db -> query($sql2);
    $result3 = $db -> query($sql3);
    $result4 = $db -> query($sql4);

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

    // Retrieve data from transaction table and loop through each row and insert 
    // data into table
    while($row = $result1 -> fetch_assoc())
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

    // Retrieve summed data from amount column table and loop through each row  
    // and insert data into table. 
    echo nl2br("\n\n\n");
    echo nl2br("Transaction Totals by Transaction Type:");
    $i = 0;
    $headers = array("Transaction Type", "Amount");
    echo '<table style = "width:50%">';
    while($i < count($headers))
    {
        $columnInfo = current($headers);
        echo '<th>' .  $columnInfo . '</th>';
        next($headers);
        $i = $i + 1;
    }

    while($row = $result2 -> fetch_row())
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

    // Retrieve summed data from amount column table and loop through each row  
    // and insert data into table. 
    echo nl2br("\n\n\n");
    echo nl2br("Transaction Totals by Company:");
    $i = 0;
    $headers = array("Company", "Amount");
    echo '<table style = "width:50%">';
    while($i < count($headers))
    {
        $columnInfo = current($headers);
        echo '<th>' .  $columnInfo . '</th>';
        next($headers);
        $i = $i + 1;
    }

    while($row = $result3 -> fetch_row())
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

    // Retrieve summed data from amount column table and loop through each row  
    // and insert data into table. 
    echo nl2br("\n\n\n");
    echo nl2br("Transaction Totals by Frequency Type:");
    $i = 0;
    $headers = array("Frequency", "Amount");
    echo '<table style = "width:50%">';
    while($i < count($headers))
    {
        $columnInfo = current($headers);
        echo '<th>' .  $columnInfo . '</th>';
        next($headers);
        $i = $i + 1;
    }

    while($row = $result4 -> fetch_row())
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
} 
// Knowing that response is no, we check to see if there is any data within
// the month or year fields. If there is, we will query the database by the
// the parameters set by what was passed in those fields.
elseif($month != '' || $year != '')
{
    $i = 0;
    $stmt1 = $db -> stmt_init();
    $stmt2 = $db -> stmt_init();

    // If there was data in the month field and NOT the year field, we will
    // search the table by the month that was given.
    if($month != '' && $year == '')
    {   
        print($month);
        $sql1 = "SELECT * FROM transactions WHERE MONTH(purchaseDate) = ?";
        $sql2 = "SELECT SUM(amount) AS 'Total Coast' FROM transactions 
                 WHERE MONTH(purchaseDate) = ? GROUP BY MONTH(purchaseDate)";

        // If prepared statements fail, prompt user to return to form and try again
        if(! $stmt1 -> prepare($sql1))
        {
            ?>
            <form action = "TransactionQueryForm.html" method = "post">
                <label>Faulty Statement, Please Try Again.</label>
                    <input type = "submit" name = "Return" value = "Return To Form"/>
            </form>
            <?php
            $db -> close();    
        }
        
        if(! $stmt2 -> prepare($sql2))
        {
            ?>
            <form action = "TransactionQueryForm.html" method = "post">
                <label>Faulty Statement, Please Try Again.</label>
                    <input type = "submit" name = "Return" value = "Return To Form"/>
            </form>
            <?php
            $db -> close();    
        } 
        // Bind month variable passed from HTML form to placeholder variable
        // in SQL statement. Then execute the statements and store the array
        // in the result variable.
        $stmt1 -> bind_param("i", $month);        
        $stmt1 -> execute();
        $result1 = $stmt1 -> get_result();
        $stmt2 -> bind_param("i", $month);
        $stmt2 -> execute();
        $result2 = $stmt2 -> get_result();
    
    } 

    // If there was data in the year field and NOT the month field, we will
    // search the table by the year that was given.
    elseif($month == '' && $year != '')
    {
        $sql1 = "SELECT * FROM transactions WHERE YEAR(purchaseDate) = ?";
        $sql2 = "SELECT SUM(amount) AS 'Total Coast' FROM transactions 
                 WHERE YEAR(purchaseDate) = ? GROUP BY YEAR(purchaseDate)";

        // If prepared statements fail, prompt user to return to form and try again
        if(! $stmt1 -> prepare($sql1))
        {
            ?>
            <form action = "TransactionQueryForm.html" method = "post">
                <label>Faulty Statement, Please Try Again.</label>
                    <input type = "submit" name = "Return" value = "Return To Form"/>
            </form>
            <?php
            $db -> close();    
        }

        if(! $stmt2 -> prepare($sql2))
        {
            ?>
            <form action = "TransactionQueryForm.html" method = "post">
                <label>Faulty Statement, Please Try Again.</label>
                    <input type = "submit" name = "Return" value = "Return To Form"/>
            </form>
            <?php
            $db -> close();    
        } 

        // Bind year variable passed from HTML form to placeholder variable
        // in SQL statement. Then execute the statements and store the array
        // in the result variable.
        $stmt1 -> bind_param("i", $year);
        $stmt1 -> execute();     
        $result1 = $stmt1 -> get_result();
        $stmt2 -> bind_param("i", $year);
        $stmt2 -> execute();
        $result2 = $stmt2 -> get_result();
        
    } 
    // If there was data in both the month and year field, we will search the 
    // table by the month that was given.
    else
    {
        $sqll = "SELECT * FROM transactions WHERE MONTH(purchaseDate) = ? AND YEAR (purchaseDate) = ?";
        $sql2 = "SELECT SUM(amount) AS 'Total Coast' FROM transactions 
                 WHERE MONTH(purchaseDate) = ? AND YEAR(purchaseDate) = ? GROUP BY YEAR(purchaseDate)";

        // If prepared statements fail, prompt user to return to form and try again
        if(! $stmt1 -> prepare($sqll))
        {
            ?>
            <form action = "TransactionQueryForm.html" method = "post">
                <label>Faulty Statement, Please Try Again.</label>
                    <input type = "submit" name = "Return" value = "Return To Form"/>
            </form>
            <?php
            $db -> close();    
        }

        if(! $stmt2 -> prepare($sql2))
        {
            ?>
            <form action = "TransactionQueryForm.html" method = "post">
                <label>Faulty Statement, Please Try Again.</label>
                    <input type = "submit" name = "Return" value = "Return To Form"/>
            </form>
            <?php
            $db -> close();    
        } 

        // Bind year and month variables passed from HTML form to placeholder 
        // variables in SQL statement. Then execute the statements and store 
        // the arrays in the two result variables.
        $stmt1 -> bind_param("ii", $month, $year);
        $stmt1 -> execute();       
        $result1 = $stmt1 -> get_result();
        $stmt2 -> bind_param("ii", $month, $year);
        $stmt2 -> execute();
        $result2 = $stmt2 -> get_result();
    
    }

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

    while($row = $result1 -> fetch_row())
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

    // Display queried information in a table using HTML. If there is no
    // data in the table, we will print out N/A
    echo nl2br("\n\n\n");
    echo nl2br("Amount Total During this Period:");
    $i = 0;
    $row = $result2 -> fetch_row();
    if($row){
        echo '
        <table style = "width:15%">
            <tr align = "center">
            <td>' . current($row) . '<td>
            </tr>
        </table>
        ';
    } else {
        echo '
        <table style = "width:15%">
            <tr align = "center">
            <td> N/A <td>
            </tr>
        </table>
        ';
    }

} 
// If the other two if statements are not true, that means the user searched
// using a transaction ID, so we will process the query by searching where 
// transaction ID is the same as the ID provided.
else
{
    // Initialize and prepare SQL statement to protect against possible SQL injection    
    $i = 0;
    $date = $year.$month;
    $stmt = $db -> stmt_init();
    $sql = "SELECT * FROM transactions WHERE transactionID = ?";

    // If prepared statement fails, prompt user to return to form and try again                 
    if(! $stmt -> prepare($sql))
    {
        ?>
        <form action = "TransactionQueryForm.html" method = "post">
            <label>Faulty Statement, Please Try Again.</label>
                <input type = "submit" name = "Return" value = "Return To Form"/>
        </form>
        <?php
        $db -> close();    
    }

    // Bind transaction ID variable passed from HTML form to placeholder 
    // variable in SQL statement. Then execute the statement and store 
    // the array in the result variable.    
    $stmt -> bind_param("i", $transactionID);
    $stmt -> execute();
    $result = $stmt -> get_result();

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

    while($row = $result -> fetch_row())
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
}
// Ask the user if they would like to do another query or
// if they would like to return to the main menu.
?>
<form>
    <br/>
    <label>Do Another Search?</label>
</form>
<form action = "TransactionQueryForm.html" method = "post">
    <input type = "submit" value = "Do Another Search"/>
    <br/>
</form>
<form action = "MainMenu.html" method = "post">
    <input type = "submit" value = "Return to Main Menu"/>
</form>