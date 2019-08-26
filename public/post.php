<?php
session_start();

function validateName(?string $name): bool
{
    $name = trim($name);
    if(empty($name)) {
        return false;
    }
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
    $birthday = trim($birthday);
    if(empty($birthday)) {
        return false;
    }
    $date = \DateTime::createFromFormat('Y-m-d', $birthday);
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
    $email = trim($email);
    if(empty($email)) {
        return true;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

function validateContent(?string $content): bool
{
    if(empty($content)) {
        return false;
    }
    if(ctype_space($content)) {
        return false;
    }
    $content = $post['content'];
    $length = mb_strlen($content, 'UTF-8');
    if($length > 65535) {
        return false;
    }
    return true;
}

function validateFields(array $post, array $controls): array
{
    $validations = [];
    foreach($controls as $control) {
        list($srcKey, $validation, $destKey) = $control;
        $value = $post[$srcKey] ?? null;
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
    unset($_SESSION['invalid']);
}

header('Location: index.php');
