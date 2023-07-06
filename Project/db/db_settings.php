<?php

function connectToDB()
{
    $params = parse_ini_file('config.ini');
    if ($params === false) {
        throw new \Exception("Error reading database configuration file");
    }

    $conStr = sprintf(
        "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
        $params['host'],
        $params['port'],
        $params['database'],
        $params['user'],
        $params['password']
    );
    $pdo = new \PDO($conStr);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $pdo;
}

$connection = connectToDB();