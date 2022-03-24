<?php
print_r($_POST); //Detect keys and their values in an array.
//^^ run this alone to see result

$db_host = 'localhost';
//Host IP Address, localhost used for testing purposes, would change on a proper HTTPS webserver.
$db_username = 'root';
$db_password = '';
//These values should be defined outside webroot, and included inside at the top to prevent easy access
$db_name = 'database_db'; //Name of Database, NOT the Table Name
mysql_connect($db_host, $db_username, $db_password) or die(mysql_error());
// Die function kills the script and defines error, helps with fatal error finding
mysql_select_db($db_name);

// This function will run within each post array including multi-dimensional arrays to prevent SQL Injection.
function ExtendedAddslash(&$params) {
    foreach ($params as & $var) {
    // check if each variable defined is an array. If correct will ad slashes to each key inside.
    is_array($var) ? ExtendedAddslash($var) : $var = addslashes($var);
    }
}

// Initialize function for every $_POST variable used in the code below
ExtendedAddslash($_POST);

if (isset($_POST['submit'])) { //Checks for a POST submission
  
    // Definition of data variables - $variable = $_POST['html input'] - gathered from the name attribute
    $input = $_POST['input'];
      
    if (empty($input)) {
        echo "data is empty";
    } else {
        
    // search submission ID
    $query = "SELECT * FROM `table_name` WHERE `input` = '$input'";
    $sqlsearch = mysql_query($query);
    $resultcount = mysql_numrows($sqlsearch);
        if ($resultcount == 1) { //Checks for results and if one will echo error
        echo 'Name is already inside database.'
        }
        else { //Else inputs into table
        //Following Snippet based from https://stackoverflow.com/questions/49359543/inserting-data-into-mysql-with-html-form-using-post-and-serverrequest-meth
        $sql = "INSERT INTO user_info (input) 
        VALUES(':input')" or die(mysql_error()); //Or reports fatal error.
        $req = $connection->prepare($sql);
        $req->bindParam(":input", $input);
        $req->execute();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  
<head>
    <title>PHP Form Submission Example</title>
</head>
  
<body>
    <center>
        <h1>Storing Form data in Database</h1>
        <form action="insert.php" method="post">
<p>
                <label for="input">Input</label>
                <input type="text" name="input">
            </p>

            <input type="submit" value="REQUEST_METHOD">
        </form>
    </center>
</body>

<?php 

//The Reason why name is used and not id
//https://stackoverflow.com/questions/1397592/difference-between-id-and-name-attributes-in-html

?>

</html>