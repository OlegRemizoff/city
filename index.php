<?php 

require_once './classes/Db.php';
require_once './classes/Pagination.php';
require_once './functions.php';
$config = require_once 'config.php';

// Подключение к БД
$db = Db::getInstance()->getConnection($config['db']);

$page = $_GET['page'] ?? 1;
$total = $db->query("SELECT COUNT(*) AS count FROM city")->getColumn();
$pagination = new Pagination((int)$page, $config['per_page'], $total);
$start = $pagination->getStart();

$cities = $db->query("SELECT * FROM city LIMIT {$start}, {$config['per_page']}")->findAll();

require_once 'views/index.tpl.php';


