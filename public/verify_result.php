<?php
//Prevent Direct URL Access to PHP Form Files
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
die ("<h2>Access Denied!</h2> This file is protected and not available to public.");
}
?>
<html>
<head>
<title>Register Result</title>
</head>
<body>
<?php
include "../config.php";

// Create connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

// Get user input from the form submitted before
$email = $_POST["email"];
$otp = $_POST["otp"];

// Set a flag to assume all user input follow the format
$allDataCorrect = true;

// Check all data whether follow the format
// if(!preg_match("/[a-zA-Z0-9_]{16}/", $otp))
// {
//     $allDataCorrect = false;
//     $errMsg = $errMsg . "OTP format is invalid<br><br>"; 
// }

if(!$allDataCorrect) {
    die("<h3> $errMsg </h3>");
}

date_default_timezone_set('Asia/Hong_Kong');
$expiryTime = date('Y-m-d h:i:s', strtotime(' -30 minutes'));

// Search user table to see whether user name is exist
$search_sql = $conn->prepare("SELECT CreatedAt FROM OTP WHERE email=? AND otp=? AND CreatedAt >= '$expiryTime'");
$search_sql->bind_param("ss", $email, $otp);
$search_sql->execute();
$search_sql->store_result();
$search_sql->bind_result($CreatedAt_db);
$search_sql->fetch();

if($search_sql->num_rows < 1) 
{
    die("<h2>Your OTP is not correct or expired.</h2>");
}

echo "<p>$email is now verified.</p>";

$update_user_sql = $conn->prepare("UPDATE Users SET `Verified` = 1 WHERE `Email` = '$otp_email'");
$update_user_sql->execute();

echo "<p>Your account is verified. You may now make an appointment.</p>";

?>

<a href="appointment_form.php">Go to appointment page</a>
</body>
</html>