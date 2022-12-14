<?php
session_start(); 
if(!isset($_SESSION['user']))
{
    header("Location:../login"); 
    die();
}
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    header("Location:../logout");
    die();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>

<?php
    date_default_timezone_set('Asia/Hong_Kong');
    $tomorrow = date('Y-m-d H:i:s', strtotime(' +1 day'));
    $enddate = date('Y-m-d H:i:s', strtotime(' +90 day'));
    $Data = SplitDate($tomorrow, $enddate, "Y-m-d");

    $startTime = date('Y-m-d H:i:s',strtotime('today 8am'));
    $endTime = date('Y-m-d H:i:s',strtotime('today 8pm'));
    $Time = SplitDate($startTime, $endTime, "H:i", "30"); 
?>

<html>
<head>
<title>Appointment Form</title>
</head>
<body>
<form action="result.php" method="post">
<h1>appointment</h1>
Date: <select name="date" required>
    <option value=""></option>
    <?php
    foreach($Data as $key => $value)
    {
        echo '<option value="'.$value.'">'.$value.'</option>';
    }
    ?>
</select>
Time: <select name="time" required>
    <option value=""></option>
    <?php
    foreach($Time as $key => $value)
    {
        echo '<option value="'.$value.'">'.$value.'</option>';
    }
    ?>
</select><br><br>
Location: <select name="location" required>
    <option value=""></option>
	<option value="East Kowloon">East Kowloon</option>
	<option value="Hong Kong Island">Hong Kong Island</option>
	<option value="Sha Ti">Sha Tin</option>
    <option value="Sheung Shui">Sheung Shui</option>
    <option value="Tseung Kwan O">Tseung Kwan O</option>
    <option value="Tsuen Wan">Tsuen Wan</option>
    <option value="Tuen Mun">Tuen Mun</option>
    <option value="West Kowloon">West Kowloon</option>
    <option value="Yuen Long">Yuen Long</option>
</select><br><br>
<input name="submit" type="submit" value="submit">
</form>
<a href='../userdetail'>Your user detail</a>
<a href="../logout">logout</a>
</body>
</html>

<?php
// Generate time slot
function SplitDate($StartTime, $EndTime, $Format, $Duration="1440"){
    $ReturnArray = array ();// Define output
    $StartTime    = strtotime ($StartTime); //Get Timestamp
    $EndTime      = strtotime ($EndTime); //Get Timestamp

    $AddMins  = $Duration * 60;

    while ($StartTime <= $EndTime) //Run loop
    {
        $ReturnArray[] = date ($Format, $StartTime);
        // $StartTime += $AddMins; //Endtime check
        $StartTime += $AddMins; //Endtime check
    }
    return $ReturnArray;   
}
?>