<?php


class Db
{

    private static ?self $instance = null;
    private $connection;   
    private PDOStatement $stmt;

    // предотвращения создания новых экземпляров
    private function __construct()
    {
    }

    // Клонирование запрещено
    private function __clone() {}


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(array $db_config)
    {

        $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']};";
        
        try {
            $this->connection = new PDO($dsn, $db_config['username'], $db_config['password'], $db_config['options']);
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
        return $this;
    }



    public function query($query, $params = [])//: PDOStatement
    {
        $this->stmt = $this->connection->prepare($query);
        $this->stmt->execute($params);
        return $this;
    }

    public function findAll()
    {
        return $this->stmt->fetchAll();
    }

    public function find()
    {
        return $this->stmt->fetch();;
    }

    public function findOrFail()
    {
        $res = $this->find();
        if (!$res) {
            die();
        }
        return $res;

    }

    
}