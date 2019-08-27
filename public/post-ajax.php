<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'db.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

require 'validation.php';

$fields = getFields($_POST);

header('Content-Type: application/json');

if(fieldsValid($fields)) {
    insertToDB($fields);

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
            'firstname' => $fields['firstName']['valid'],
            'lastname' => $fields['lastName']['valid'],
            'birthdate' => $fields['dateOfBirth']['valid'],
            'email' => $fields['email']['valid'],
            'message' => $fields['content']['valid'],
        ],
    ];
}

echo json_encode($response);
