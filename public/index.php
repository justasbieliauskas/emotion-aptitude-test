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

use App\Template\PageVars;

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

$fields = [];
if(isset($_SESSION['invalid'])) {
    $fields = $_SESSION['invalid']['fields'];
    unset($_SESSION['invalid']);
}

$perPage = 3;

require 'include/db.php';

$db = getDB();

$pageVars = (new PageVars(
    $db,
    $_GET,
    'page',
    $perPage
))->toArray('current', 'perPage', 'total');

$requestedPage = $pageVars['current'];

$offset = ($requestedPage - 1) * $perPage;
$sql = "SELECT * FROM messages ORDER BY id DESC LIMIT $perPage OFFSET $offset";

$messages = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

array_walk($messages, 'addAge');

echo $twig->render('main.html.twig', [
    'fields' => $fields,
    'messages' => $pageVars + ['list' => $messages],
]);
