<?php

require_once './classes/Db.php';
require_once './classes/Pagination.php';
require_once './classes/Validator.php';
require_once './functions.php';
$config = require_once 'config.php';

// Подключение к БД
$db = Db::getInstance()->getConnection($config['db']);

$data = json_decode(file_get_contents('php://input'), true);

// Pagination
if (isset($data['page'])) {
    $page = (int)$data['page'];
    $total = $db->query("SELECT COUNT(*) AS count FROM city")->findColumn();
    $pagination = new Pagination((int)$page, $config['per_page'], $total);
    $start = $pagination->get_start();

    $cities = $db->query("SELECT * FROM city LIMIT {$start}, {$config['per_page']}")->findAll();
    require_once 'views/index-content.tpl.php';
    die;
}


/// Add city
if (isset($_POST['addCity'])) {
    $data = $_POST;
    $validator = new Validator();
    $validation = $validator->validate($data, [
        'name' => [
            'required' => true,
        ],
        'population' => [
            'minNum' => 1,
        ]
    ]);
    if ($validation->hasErrors()) {
        $errors = '<ul class="list-unstyled text-start text-danger">';
        foreach ($validation->getErrors() as $v) {
            foreach ($v as $error) {
                $errors .= "<li>{$error}</li>";
            }
        }
        $errors .= '</ul>';
        $res = ['answer' => 'error', 'errors' => $errors];
    } else {
        $db->query("INSERT INTO city (`name`, `population`) VALUES (?, ?)", [$data['name'], $data['population']]);
        $res = ['answer' => 'success'];
    }
    echo json_encode($res);
    die;
}
