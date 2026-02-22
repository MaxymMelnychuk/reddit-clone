<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'security');
define('DB_USER', 'root');


function getPdo(): PDO {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    return new PDO($dsn, DB_USER);
}
