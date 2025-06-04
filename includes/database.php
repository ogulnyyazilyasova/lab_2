<?php
class Database
{
    private static $connection;
    private static $config = [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'database' => 'books'
    ];

    /**
     * Инициализация подключения к базе данных
     */
    public static function init()
    {
        try {
            self::$connection = new PDO(
                "mysql:host=" . self::$config['host'] . ";dbname=" . self::$config['database'] . ";charset=utf8",
                self::$config['user'],
                self::$config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }

    /**
     * Выполнение SQL-запроса
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного запроса
     * @return PDOStatement|false Возвращает объект PDOStatement или false при ошибке
     */
    public static function executeQuery($query, $params = [])
    {
        try {
            $stmt = self::$connection->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Ошибка выполнения запроса: " . $e->getMessage() .
                "\nQuery: " . $query .
                "\nParams: " . print_r($params, true));
            throw $e;
        }
    }

    /**
     * Получение всех строк результата
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного запроса
     * @return array Массив с результатами
     */
    public static function fetchAll($query, $params = [])
    {
        $stmt = self::executeQuery($query, $params);
        return $stmt->fetchAll();
    }

    /**
     * Получение одной строки результата
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного запроса
     * @return array|false Ассоциативный массив с результатом или false, если нет данных
     */
    public static function fetchOne($query, $params = [])
    {
        $stmt = self::executeQuery($query, $params);
        return $stmt->fetch();
    }

    /**
     * Получение значения одного столбца
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного запроса
     * @return mixed Значение столбца
     */
    public static function fetchColumn($query, $params = [])
    {
        $stmt = self::executeQuery($query, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Настройка параметров подключения из переменных окружения
     */
    public static function configureFromEnv()
    {
        self::$config = [
            'host' => getenv('DB_HOST') ?: 'localhost',
            'user' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASSWORD') ?: 'root',
            'database' => getenv('DB_NAME') ?: 'books'
        ];
    }
    
    public static function escape($value)
    {
        if (!self::$connection) {
            self::init();
        }
        return self::$connection->quote($value);
    }
}

// Инициализация подключения при первом использовании
Database::configureFromEnv();
Database::init();
