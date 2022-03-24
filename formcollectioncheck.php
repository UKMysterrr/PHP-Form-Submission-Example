<?php
session_start();
require 'database_login.php'; //Database Login For connection
if(isset($_POST['submit'])) {
    $stmt = $conn->prepare('select * from account where input = :input'); //Checks database for input
	$stmt->bindValue('input', $_POST['input']); //Binds input value to statement
	$stmt->execute();
	$account = $stmt->mysql_fetch_object($input); //Fetches Input
    if($account != NULL) { //If Fetched
        if (secret_verify($_POST['secret'], $account->secret)){
            $_SESSION['input'] = $_POST['input']; //Verify correct Secret, then Set Session and navigate to success page
            header('location:success.php');
        } else {
            $error = 'Input Invalid'; //Else say Input is invalid
        }
    } else {
        $error = 'Input Invalid'; //And Again
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PHP BCrypt Decryption Secret Access Example</title>
</head>

<body>
    <center>
        <h1>BCrypt Decryption Secret Access</h1>

        <form method="post">

        <?php echo isset($error) ? $error : ''; ?>

            <p>
                <label for="input">Input</label>
                <input type="text" name="input">
            </p>
            <p>
                <label for="input">Secret</label>
                <input type="text" name="secret">
            </p>

            <input type="submit" value="Submission" name="submit">
        </form>
    </center>
</body>
</html>