<?php
session_start();

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

require 'include/db.php';

$db = getDB();

use App\Page\PageDTO;
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

$pageDTO = new PageDTO();
$pageDTO
    ->setCurrent($currentPage->toInt())
    ->setTotal($pageCount->toInt())
    ->setPerPage($perPage->toInt());

use App\Db\Sql\Clause\OrderClause;
use App\Db\Sql\Clause\SelectClause;
use App\Db\Sql\Clause\PageLimitClause;

$sql = new PageLimitClause(
    new OrderClause(new SelectClause('messages'), 'id', 'DESC'),
    $currentPage,
    $perPage
);

use App\Entity\DbMessages;

$messages = new DbMessages($sql, $db);

echo $twig->render('index.html.twig', [
    'fields' => $fields,
    'page' => $pageDTO,
    'messages' => $messages,
]);
