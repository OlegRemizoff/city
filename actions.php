<?php

require_once './classes/Db.php';
require_once './classes/Pagination.php';
require_once './classes/Validator.php';
require_once './functions.php';
$config = require_once 'config.php';

// Подключение к БД
$db = Db::getInstance()->getConnection($config['db']);

$data = json_decode(file_get_contents('php://input'), true);

// pagination
if (isset($data['page'])) {
    $page = (int)$data['page'];
    $total = $db->query("SELECT COUNT(*) AS count FROM city")->getColumn();
    $pagination = new Pagination((int)$page, $config['per_page'], $total);
    $start = $pagination->getStart();

    $cities = $db->query("SELECT * FROM city LIMIT {$start}, {$config['per_page']}")->findAll();
    require_once 'views/index-content.tpl.php';
    die;
}


