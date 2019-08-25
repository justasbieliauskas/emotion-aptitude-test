<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

$session = [
    'post' => [
        'success' => false,
        'fields' => [
            'name' => [
                'value' => null,
                'valid' => false,
            ],
            'birthday' => [
                'value' => '2009-03-25',
                'valid' => true,
            ],
            'email' => [
                'value' => 'foo@example.com',
                'valid' => true,
            ],
            'content' => [
                'value' => null,
                'valid' => false,
            ],
        ],
    ],
];

$fields = [];
if(isset($session['post']) && !$session['post']['success']) {
    $fields = $session['post']['fields'];
}

$db = new PDO('sqlite:' . __DIR__ . '/../sqlite.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$perPage = 3;

$messageCount = $db->query('SELECT COUNT(*) FROM messages')->fetchColumn();
$pagesCount = ceil($messageCount / $perPage);

$requestedPage = 1;
if(isset($_GET['page']) && ctype_digit($_GET['page'])) {
    $requestedPage = (int) $_GET['page'];
    if($requestedPage > $pagesCount) {
        $requestedPage = $pagesCount;
    }
}

$offset = ($requestedPage - 1) * $perPage;
$messages = $db->query("SELECT * FROM messages LIMIT $perPage OFFSET $offset");

echo $twig->render('main.html.twig', [
    'fields' => $fields,
    'messages' => [
        'total' => $pagesCount,
        'current' => $requestedPage,
        'list' => $messages,
    ],
]);
