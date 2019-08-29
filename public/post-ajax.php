<?php

use App\Page\PageDTO;
use App\Page\PositivePage;
use App\Page\PageInterface;
use App\Page\PageUnderLimit;
use App\Page\PerPageConstant;
use App\Entity\PDOMessageCount;
use App\Page\PageCountFromTotal;

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'include/db.php';

function getPageHtml($twig): string
{
    $db = getDB();
    $perPage = new PerPageConstant(3);
    $messageCount = new PDOMessageCount($db);
    $pageCount = new PageCountFromTotal($messageCount, $perPage);
    $currentPage = new PageUnderLimit(
        new PositivePage(
            new class implements PageInterface {
                public function toInt(): int
                {
                    return (int) $_POST['currentPage'];
                }
            }
        ),
        $pageCount
    );
    
    return $twig->render('partials/index/_pages.html.twig', [
        'current' => $currentPage->toInt(),
        'total' => $pageCount->toInt(),
    ]);
}

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

require 'include/validation.php';

$fields = getFields($_POST);

header('Content-Type: application/json');

if(fieldsValid($fields)) {
    insertToDB($fields);

    $dateOfBirth = \DateTime::createFromFormat('Y-m-d', $fields['dateOfBirth']['value']);
    $now = new \DateTime('now', new \DateTimeZone('Europe/Vilnius'));

    $messageHtml = $twig->render('partials/index/_message.html.twig', [
        'message' => [
            'firstName' => $fields['firstName']['value'],
            'lastName' => $fields['lastName']['value'],
            'age' => $dateOfBirth->diff($now)->format('%y'),
            'email' => $fields['email']['value'],
            'content' => $fields['content']['value'],
            'createdAt' => $now->format('Y-m-d H:i:s'),
        ],
    ]);

    $response = [
        'valid' => true,
        'html' => [
            'message' => $messageHtml,
            'pages' => getPageHtml($twig),
        ],
    ];
} else {
    $response = [
        'valid' => false,
        'errors' => [
            'firstname' => $fields['firstName'],
            'lastname' => $fields['lastName'],
            'birthdate' => $fields['dateOfBirth'],
            'email' => $fields['email'],
            'message' => $fields['content'],
        ],
    ];
}

echo json_encode($response);
