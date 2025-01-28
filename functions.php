<?php

require_once './classes/Db.php';

function dd($data, $die = false)
{
    echo '<pre>' . print_r($data, 1) . '</pre>';
    if ($die) {
        die;
    }
}


function search_cities(string $search): array 
{
    global $db;
    return $db->query("SELECT id, name, population FROM city WHERE name LIKE ?", ["%{$search}%"])->findAll();
}



function getPaginations()
{

    $db_config = [
        'host' => "localhost",
        'dbname' => 'world',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'options' => [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // ассоциативный массив
        ]
    ];

    // Подключение к БД
    $db = Db::getInstance()->getConnection($db_config);

    // Общее колличество городов
    $total = $db->query("SELECT COUNT(*) AS count FROM city")->getColumn();

    // Колличество нужных записей
    $per_page = 10;

    // Общее колличество страниц для пагинации
    $pages_cnt = ceil($total / $per_page);

    // Получаем страницу, если нет или отрицательное значение, то = 1
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    // Если значение больше, то вернем последнюю страницу
    if ($page > $pages_cnt) {
        $page = $pages_cnt;
    }

    $start = ($page - 1) * $per_page;
    $cities = $db->query("SELECT * FROM city LIMIT {$start}, {$per_page}")->findAll();
    return ['data' => $cities, 'start' => $start, 'pages_cnt' => $pages_cnt];

    // for ($i = 1; $i <= $data['pages_cnt']; $i++) {
    //     echo "<a href='?page={$i}' style='text-decoration: none'> {$i}</a>";
    // }
}