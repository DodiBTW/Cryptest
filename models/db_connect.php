<?php
// SI MYSQL :
// $host = getenv('DB_HOST');
// $user = getenv('DB_USER');
// $password = getenv('DB_PASSWORD');
// $dbname = getenv('DB_NAME');
// Function get_db_connection(){
//     $conn = new mysqli($host, $user, $password, $dbname);
//     if ($conn->connect_error) {
//         die("Connection failed: " . $conn->connect_error);
//     }
//     return $conn;
// }


// Pour sqlite (plus simple)
//$dbname = getenv('DB_NAME');
$dbname = "history.db";
function get_db_connection(){
    $conn = new SQLite3($dbname);
    if (!$conn) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>