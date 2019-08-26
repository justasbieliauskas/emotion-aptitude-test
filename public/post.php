<?php
session_start();

function validateName(?string $name): bool
{
    if($name === null) {
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
    if($birthday === null) {
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
    if($email === null) {
        return true;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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

function getFieldValues(array $post, array $keys): array
{
    $values = [];
    foreach($keys as $key) {
        $value = null;
        if(!empty($post[$key]) && !ctype_space($post[$key])) {
            $value = $post[$key];
        }
        $values[$key] = $value;
    }
    return $values;
}

function trimFieldValues(array $fields, string $contentKey): array
{
    $trimedFields = [];
    foreach($fields as $key => $value) {
        $newValue = $value;
        if(!empty($newValue)) {
            if($key !== $contentKey) {
                $newValue = trim($newValue);
            }
        }
        $trimedFields[$key] = $newValue;
    }
    return $trimedFields;
}

function getFieldValidations(array $values, array $mapping): array
{
    $validations = [];
    foreach($values as $key => $value) {
        $validation = $mapping[$key];
        $validations[$key] = $validation($value);
    }
    return $validations;
}

function joinFieldValuesWithValidations(array $values, array $validations): array
{
    $fields = [];
    foreach($values as $key => $value) {
        $fields[$key] = [
            'value' => $value,
            'valid' => $validations[$key],
        ];
    }
    return $fields;
}

function changeFieldKeys(array $fields, array $mapping): array
{
    $newFields = [];
    foreach ($fields as $key => $field) {
        $newKey = $mapping[$key];
        $newFields[$newKey] = $field;
    }
    return $newFields;
}

function getFields(array $post): array
{
    $values = getFieldValues(
        $post,
        ['firstname', 'lastname', 'birthdate', 'email', 'message']
    );
    $values = trimFieldValues($values, 'message');
    $validations = getFieldValidations($values, [
        'firstname' => 'validateName',
        'lastname' => 'validateName',
        'birthdate' => 'validateBirthday',
        'email' => 'validateEmail',
        'message' => 'validateContent',
    ]);
    $fields = joinFieldValuesWithValidations($values, $validations);
    $fields = changeFieldKeys($fields, [
        'firstname' => 'firstName',
        'lastname' => 'lastName',
        'birthdate' => 'dateOfBirth',
        'email' => 'email',
        'message' => 'content',
    ]);
    return $fields;
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

$fields = getFields($_POST);

if(!fieldsValid($fields)) {
    $_SESSION['invalid'] = ['fields' => $fields];
} else {
    $db = include 'pdo.php';

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

header('Location: index.php');
