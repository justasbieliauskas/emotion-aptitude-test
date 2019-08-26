<?php

function validateName(array $post, string $key): bool
{
    if(empty($post[$key])) {
        return false;
    }
    $name = trim($post[$key]);
    if(strlen($name) > 255) {
        return false;
    }
    if(!preg_match('/^[A-Za-zĄČĘĖĮŠŲŪŽąčęėįšųūž]+$/', $name)) {
        return false;
    }
    return true;
}

function validateBirthday(array $post): bool
{
    if(empty($post['birthday'])) {
        return false;
    }
    $date = \DateTime::createFromFormat('Y-m-d', trim($post['birthday']));
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

function validateEmail(array $post): bool
{
    if(!isset($post['email'])) {
        return true;
    }
    $email = trim($post['email']);
    if(empty($email)) {
        return true;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

function validateContent(array $post): bool
{
    if(empty($post['content'])) {
        return false;
    }
    $content = $post['content'];
    $length = mb_strlen($content, 'UTF-8');
    if($length > 65535) {
        return false;
    }
    return true;
}

$post = ['content' => ''];
var_dump(validateContent($post));
