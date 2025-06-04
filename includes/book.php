<?php
require_once 'database.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Ramsey\Uuid\Uuid;

class Book
{
    private $title;
    private $author;
    private $file;
    private $outDate;
    private $catalog;
    private $about;
    private $uuid;

    public function __construct($title, $author, $file, $outDate = null, $catalog = null, $about = null)
    {
        $this->title = $title;
        $this->author = $author;
        $this->file = $file;
        $this->outDate = $outDate;
        $this->catalog = $catalog;
        $this->about = $about;
        $this->uuid = Uuid::uuid4()->toString();
    }

    public static function handleFileUpload()
    {
        $uploadDir = '/uploads/pdf/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['fileToUpload'])) {
            $file = $_FILES['fileToUpload'];
            
            // Проверка типа файла
            $allowedTypes = ['application/pdf'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Разрешена загрузка только PDF файлов');
            }

            // Проверка размера файла (например, не более 50MB)
            if ($file['size'] > 50 * 1024 * 1024) {
                throw new Exception('Файл слишком большой. Максимальный размер 50MB');
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = time() . '.' . $extension;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return $destination;
            }
        }
        return null;
    }

    public static function initializeDatabase() {
        self::createBooksTable();
        self::createCatalogsTable();
        self::insertCatalogs();
    }

    public static function createCatalogsTable()
    {
        $query = "
            CREATE TABLE IF NOT EXISTS catalogs (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL UNIQUE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        return Database::executeQuery($query);
    }

    public static function insertCatalogs()
    {
        $catalogs = [
            'Аналитическая геометрия',
            'Высшая математика',
            'Общая химия',
            'Физика. Основные законы',
            'История России',
            'Русский язык',
            'Литература',
            'Информатика',
            'Биология',
            'География',
            'Экономика',
            'Право',
            'Математический анализ',
            'Теория вероятностей',
            'Теория чисел',
            'Органическая химия',
            'Неорганическая химия',
            'Астрономия',
            'Философия',
            'Социальные науки',
            'Политология',
            'Психология',
            'Социология',
            'Музыковедение',
            'Искусствоведение',
            'История культуры',
            'Логика',
            'Эстетика',
            'Лингвистика',
            'Технические науки',
            'Инженерная графика',
            'Электротехника',
            'Механика',
            'Информационные технологии',
            'Управление и экономика предприятий',
            'Юриспруденция',
            'Медицинские науки',
            'Фармакология',
            'Педагогика',
            'Психология образования'
        ];

        foreach ($catalogs as $catalog) {
            $query = "INSERT IGNORE INTO catalogs (name) VALUES (?)";
            Database::executeQuery($query, [$catalog]);
        }
    }

    public static function createBooksTable()
    {
        $query = "
            CREATE TABLE IF NOT EXISTS books (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                author VARCHAR(255) NOT NULL,
                file VARCHAR(255) NOT NULL,
                outDate DATE,
                catalog VARCHAR(100),
                about TEXT,
                uuid VARCHAR(36) UNIQUE NOT NULL,
                image VARCHAR(255) DEFAULT '/img/no_image.png',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (catalog) REFERENCES catalogs(name) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        return Database::executeQuery($query);
    }

    public function save()
    {
        $query = "
            INSERT INTO books 
                (title, author, file, outDate, catalog, about, uuid) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?);
        ";

        $params = [
            $this->title,
            $this->author,
            $this->file,
            $this->outDate,
            $this->catalog,
            $this->about,
            $this->uuid
        ];

        $result = Database::executeQuery($query, $params);
        return $result->rowCount() > 0;
    }

    public static function update($uuid, $data)
    {
        $query = "
            UPDATE books SET 
                title = ?, 
                author = ?, 
                file = ?, 
                outDate = ?, 
                catalog = ?, 
                about = ?
            WHERE uuid = ?;
        ";

        $params = [
            $data['title'],
            $data['author'],
            $data['file'],
            $data['outDate'],
            $data['catalog'],
            $data['about'],
            $uuid
        ];

        $result = Database::executeQuery($query, $params);
        return $result->rowCount() > 0;
    }

    public static function delete($uuid)
    {
        $query = "DELETE FROM books WHERE uuid = ?";
        $result = Database::executeQuery($query, [$uuid]);
        return $result->rowCount() > 0;
    }

    public static function getAll($limit = null, $offset = null)
    {
        $query = "SELECT * FROM books";
        if ($limit !== null) {
            $query .= " LIMIT ?";
            $params = [$limit];
            if ($offset !== null) {
                $query .= " OFFSET ?";
                $params[] = $offset;
            }
            return Database::fetchAll($query, $params);
        }
        return Database::fetchAll($query);
    }

    public static function getCatalogs()
    {
        $query = "SELECT name FROM catalogs ORDER BY name";
        return Database::fetchAll($query, [], PDO::FETCH_COLUMN);
    }

    public static function getByUUID($uuid)
    {
        $query = "SELECT * FROM books WHERE uuid = ?";
        $result = Database::fetchOne($query, [$uuid]);

        if (!$result) {
            throw new Exception("Книга с UUID: $uuid не найдена!");
        }

        return $result;
    }

    public static function search($term, $catalog = null)
    {
        $query = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
        $params = ["%$term%", "%$term%"];

        if ($catalog) {
            $query .= " AND catalog = ?";
            $params[] = $catalog;
        }

        return Database::fetchAll($query, $params);
    }
}