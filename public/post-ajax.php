<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

require 'validation.php';

$fields = getFields($_POST);

header('Content-Type: application/json');

if(fieldsValid($fields)) {
    $dateOfBirth = \DateTime::createFromFormat('Y-m-d', $fields['dateOfBirth']['value']);
    $now = new \DateTime('now', new \DateTimeZone('Europe/Vilnius'));

    $response = [
        'valid' => true,
        'html' => $twig->render('message.html.twig', [
            'message' => [
                'first_name' => $fields['firstName']['value'],
                'last_name' => $fields['lastName']['value'],
                'age' => $dateOfBirth->diff($now)->format('%y'),
                'email' => $fields['email']['value'],
                'content' => $fields['content']['value'],
                'created_at' => $now->format('Y-m-d H:i:s'),
            ],
        ]),
    ];
} else {
    $response = [
        'valid' => false,
        'errors' => [
            'first_name' => $fields['firstName']['valid'],
            'last_name' => $fields['lastName']['valid'],
            'date_of_birth' => $fields['dateOfBirth']['valid'],
            'email' => $fields['email']['valid'],
            'content' => $fields['content']['valid'],
        ],
    ];
}

echo json_encode($response);
