<?php

$db = new PDO('sqlite:' . __DIR__ . '/../sqlite.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return $db;
