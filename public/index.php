<?php
session_start();

function addAge(&$message): void
{
    $timeZone = new \DateTimeZone('Europe/Vilnius');
    $now = new \DateTime('now', $timeZone);
    $dateOfBirth = \DateTime::createFromFormat(
        'Y-m-d',
        $message['date_of_birth']
    );
    $diff = $dateOfBirth->diff($now);
    $message['age'] = $diff->format('%y');
}

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

$fields = [];
if(isset($_SESSION['invalid'])) {
    $fields = $_SESSION['invalid']['fields'];
    unset($_SESSION['invalid']);
}

$db = include 'pdo.php';

$perPage = 3;

$messageCount = $db->query('SELECT COUNT(*) FROM messages')->fetchColumn();
$pagesCount = ceil($messageCount / $perPage);

$requestedPage = 1;
if(isset($_GET['page']) && ctype_digit($_GET['page'])) {
    $requestedPage = (int) $_GET['page'];
    if($requestedPage === 0) {
        $requestedPage = 1;
    }
    if($requestedPage > $pagesCount) {
        $requestedPage = $pagesCount;
    }
}

$offset = ($requestedPage - 1) * $perPage;
$sql = "SELECT * FROM messages ORDER BY id DESC LIMIT $perPage OFFSET $offset";

$messages = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

array_walk($messages, 'addAge');

echo $twig->render('main.html.twig', [
    'fields' => $fields,
    'messages' => [
        'total' => $pagesCount,
        'current' => $requestedPage,
        'list' => $messages,
    ],
]);
