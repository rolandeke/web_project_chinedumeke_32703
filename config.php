<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'yvonne32703');
define('DB_NAME', 'usersdb_32703');

try {

    $DNS = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    $connection = new PDO($DNS, DB_USER, DB_PASS);

} catch (PDOException $ex) {
    throw $ex;
    exit;
}
