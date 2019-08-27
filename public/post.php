<?php
session_start();

require 'include/validation.php';
require 'include/db.php';

$fields = getFields($_POST);

if(!fieldsValid($fields)) {
    $_SESSION['invalid'] = ['fields' => $fields];
} else {
    insertToDB($fields);
}

header('Location: index.php');
