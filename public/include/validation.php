<?php

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

function transformContentField(string $content): ?string
{
    $content = strip_tags($content);
    $content = trim($content);
    return empty($content) ? null : $content;
}

function transformFieldValues(array $fields, array $mapping): array
{
    $newFields = [];
    foreach($fields as $key => $value) {
        $newValue = null;
        if($value !== null) {
            $transform = $mapping[$key];
            $newValue = $transform($value);
        }
        $newFields[$key] = $newValue;
    }
    return $newFields;
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
    $values = transformFieldValues($values, [
        'firstname' => 'rtrim',
        'lastname' => 'rtrim',
        'birthdate' => 'rtrim',
        'email' => 'rtrim',
        'message' => 'transformContentField',
    ]);
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
