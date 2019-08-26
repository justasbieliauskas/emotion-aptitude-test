<?php
session_start();

function validateName(?string $name): bool
{
    if($name === null) {
        return false;
    }
    $name = trim($name);
    if(strlen($name) > 255) {
        return false;
    }
    if(!preg_match('/^[A-Za-zĄČĘĖĮŠŲŪŽąčęėįšųūž]+$/', $name)) {
        return false;
    }
    return true;
}

function validateBirthday(?string $birthday): bool
{
    if($birthday === null) {
        return false;
    }
    $date = \DateTime::createFromFormat('Y-m-d', trim($birthday));
    $errors = \DateTime::getLastErrors();
    $errorCount = $errors['warning_count'] + $errors['error_count'];
    if($errorCount > 0) {
        return false;
    }
    $now = new \DateTime('now', new \DateTimeZone('Europe/Vilnius'));
    if($date->format('Y-m-d') >= $now->format('Y-m-d')) {
        return false;
    }
    $diff = $date->diff($now);
    if($diff->y > 100) {
        return false;
    }
    return true;
}

function validateEmail(?string $email): bool
{
    if($email === null) {
        return true;
    }
    if(!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

function validateContent(?string $content): bool
{
    if($content === null) {
        return false;
    }
    $length = mb_strlen($content, 'UTF-8');
    if($length > 65535) {
        return false;
    }
    return true;
}

function getFieldValue(array $post, string $key): ?string
{
    if(empty($post[$key])) {
        return null;
    }
    $value = $post[$key];
    if(ctype_space($value)) {
        return null;
    }
    return $value;
}

function validateFields(array $post, array $controls): array
{
    $validations = [];
    foreach($controls as $control) {
        list($srcKey, $validation, $destKey) = $control;
        $value = getFieldValue($post, $srcKey);
        $validations[$destKey] = [
            'value' => $value,
            'valid' => $validation($value),
        ];
    }
    return $validations;
}

function fieldsValid(array $fields): bool
{
    foreach($fields as $field) {
        if(!$field['valid']) {
            return false;
        }
    }
    return true;
}

$fields = validateFields($_POST, [
    ['firstname', 'validateName', 'firstName'],
    ['lastname', 'validateName', 'lastName'],
    ['birthdate', 'validateBirthday', 'dateOfBirth'],
    ['email', 'validateEmail', 'email'],
    ['message', 'validateContent', 'content'],
]);

if(!fieldsValid($fields)) {
    $_SESSION['invalid'] = ['fields' => $fields];
} else {
    $db = include 'pdo.php';

    $attributes = '(first_name, last_name, date_of_birth, email, content, created_at)';
    $sql = "INSERT INTO messages $attributes VALUES (?, ?, ?, ?, ?, ?)";

    $now = new \DateTime('now', new \DateTimeZone('Europe/Vilnius'));

    $db->prepare($sql)->execute([
        $fields['firstName']['value'],
        $fields['lastName']['value'],
        $fields['dateOfBirth']['value'],
        $fields['email']['value'],
        $fields['content']['value'],
        $now->format('Y-m-d H:i:s')
    ]);

    unset($_SESSION['invalid']);
}

header('Location: index.php');
