<!-- 
Programmer Name: 02

File Purpose:
This PHP script establishes a connection to the MySQL database used in the application.
It defines database credentials and initializes a connection using `mysqli_connect`.

Detailed Code Notes:
- Connection Parameters: Specifies the database host, username, password, and database name.
- Error Handling: Checks for connection errors and terminates the script with a descriptive error message if the connection fails.
-->

<?php

$dbhost = "localhost";
$dbuser= "root";
$dbpass = "cs3319";
$dbname = "assign2db";
$connection = mysqli_connect($dbhost, $dbuser,$dbpass, $dbname);
if (mysqli_connect_errno()) {
     die("database connection failed :" .
     mysqli_connect_error() .
     "(" . mysqli_connect_errno() . ")"
         );
    }
?>
