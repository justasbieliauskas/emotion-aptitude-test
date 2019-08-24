<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

$db = new PDO('sqlite:' . __DIR__ . '/../sqlite.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$messages = $db->query('SELECT * FROM messages');

echo $twig->render('main.html.twig', ['messages' => $messages]);
