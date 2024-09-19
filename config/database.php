<?php
$db_config = [
    'host' => 'sql103.iceiy.com',
    'username' => 'icei_37344178',
    'password' => 'bso67dhBqFuX',
    'database' => 'icei_37344178_ascendancy'
];

function get_db_connection() {
    global $db_config;
    $conn = new mysqli($db_config['host'], $db_config['username'], $db_config['password'], $db_config['database']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}