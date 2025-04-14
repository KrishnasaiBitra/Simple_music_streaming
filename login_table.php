<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = "webproject_2";
// Create connection
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
// Check connection
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}
// sql to create table
$sql = "CREATE TABLE Students (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(50) not null,
password VARCHAR(50) not null

)";
if ($conn->query($sql) === TRUE) {
echo "Table Students created successfully";
}
else {
echo "Error creating table: " . mysqli_error($conn);
}
mysqli_close($conn);
?>