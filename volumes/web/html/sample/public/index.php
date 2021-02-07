<?php
    print("Hello PHP on Docker-Compose").PHP_EOL;

    $db = new PDO(
        // database name
        "pgsql:host=db;dbname=admin;",
        // username
        "admin",
        // password
        "admin"
    );

    $sql    = "SELECT * FROM test";
    $res    = $db->query($sql);
    $result = $res->fetchAll(PDO::FETCH_ASSOC);

    var_dump($result);