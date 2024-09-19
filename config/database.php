<?php
$db_config = [
    'host' => '',
    'username' => '',
    'password' => '',
    'database' => ''
];

function get_db_connection() {
    global $db_config;
    $conn = new mysqli($db_config['host'], $db_config['username'], $db_config['password'], $db_config['database']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
