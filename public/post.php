<?php
session_start();

require 'validation.php';
require 'db.php';

$fields = getFields($_POST);

if(!fieldsValid($fields)) {
    $_SESSION['invalid'] = ['fields' => $fields];
} else {
    insertToDB();
}

header('Location: index.php');
