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

require 'include/db.php';

$db = getDB();

use App\Page\PositivePage;
use App\Page\PageUnderLimit;
use App\Page\PerPageConstant;
use App\Page\QueryStringPage;
use App\Entity\PDOMessageCount;
use App\Page\PageCountFromTotal;

$perPage = new PerPageConstant(3);
$messageCount = new PDOMessageCount($db);
$pageCount = new PageCountFromTotal($messageCount, $perPage);
$currentPage = new PageUnderLimit(
    new PositivePage(
        new QueryStringPage($_GET, 'page', 1)
    ),
    $pageCount
);

use App\Sql\OrderClause;
use App\Sql\SelectClause;
use App\Sql\PageLimitClause;

$sql = new PageLimitClause(
    new OrderClause(
        new SelectClause('messages'),
        'id'
    ),
    $currentPage,
    $perPage
);

$messages = $db->query($sql->asString())->fetchAll(PDO::FETCH_ASSOC);

array_walk($messages, 'addAge');

echo $twig->render('main.html.twig', [
    'fields' => $fields,
    'messages' => [
        'current' => $currentPage->toInt(),
        'perPage' => $perPage->toInt(),
        'total' => $pageCount->toInt(),
        'list' => $messages,
    ],
]);
