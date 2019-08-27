<?php

function getDB(): PDO
{
    $db = new PDO('sqlite:' . __DIR__ . '/../../sqlite.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;
}

function insertToDB(array $fields): void
{
    $db = getDB();

    $attributes = '(first_name, last_name, date_of_birth, email, content, created_at)';
    $sql = "INSERT INTO messages $attributes VALUES (?, ?, ?, ?, ?, ?)";

    $now = new \DateTime('now', new \DateTimeZone('Europe/Vilnius'));
    $email = $fields['email']['value'];

    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $fields['firstName']['value']);
    $stmt->bindValue(2, $fields['lastName']['value']);
    $stmt->bindValue(3, $fields['dateOfBirth']['value']);
    $stmt->bindValue(4, $email, $email === null ? PDO::PARAM_INT : PDO::PARAM_STR);
    $stmt->bindValue(5, $fields['content']['value']);
    $stmt->bindValue(6, $now->format('Y-m-d H:i:s'));
    $stmt->execute();
}
