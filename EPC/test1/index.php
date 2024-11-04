<?php
/**
 * Класс init, который нельзя наследовать.
 * Этот класс предназначен для работы с таблицей test: создания таблицы, заполнения случайными данными и получения данных.
 */
final class Init
{
    /**
     * @var mysqli|null Соединение с базой данных
     */
    private $conn;

    /**
     * Конструктор класса, который устанавливает соединение с базой данных и вызывает методы create() и fill().
     */
    public function __construct()
    {
        // Подключаемся к базе данных
        $this->conn = new mysqli("localhost", "root", "");

        // Название базы данных
        $dbName = "test_base";

        // Проверка существования базы данных
        $dbExists = $this->conn->query("SHOW DATABASES LIKE '$dbName'");
        if ($dbExists->num_rows == 0) {
            // Создание базы данных, если она не существует
            $sql = "CREATE DATABASE $dbName";
            if ($this->conn->query($sql) === TRUE) {
                echo "База данных $dbName успешно создана.<br>";
            } else {
                die("Ошибка при создании базы данных: <br>" . $this->conn->error);
            }
        } else {
            echo "База данных $dbName уже существует.<br>";
        }

        // Подключение к базе данных
        $this->conn->select_db($dbName);

        // Проверяем успешность подключения
        if ($this->conn->connect_error) {
            die("Ошибка подключения: " . $this->conn->connect_error);
        }

        // Выполняем создание таблицы и заполнение данными
        $this->create();
        $this->fill();
    }

    /**
     * Деструктор класса, который закрывает соединение с базой данных.
     */
    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**
     * Создает таблицу test с пятью полями.
     * Метод доступен только внутри класса.
     *
     * @return void
     */
    private function create()
    {
        // SQL-запрос для создания таблицы
        $sql = "CREATE TABLE IF NOT EXISTS test (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255),
                    value INT,
                    date DATE,
                    result ENUM('normal', 'success', 'fail')
                )";

        // Выполняем запрос
        if (!$this->conn->query($sql)) {
            echo "Ошибка создания таблицы: " . $this->conn->error;
        }
    }

    /**
     * Заполняет таблицу test случайными данными.
     * Метод доступен только внутри класса.
     *
     * @return void
     */
    private function fill()
    {
        // SQL-запрос для вставки случайных данных в таблицу
        $sql = "INSERT INTO test (name, value, date, result) VALUES 
                ('Name1', FLOOR(RAND()*100), CURDATE(), 'normal'),
                ('Name2', FLOOR(RAND()*100), CURDATE(), 'success'),
                ('Name3', FLOOR(RAND()*100), CURDATE(), 'fail')";

        // Выполняем запрос
        if (!$this->conn->query($sql)) {
            echo "Ошибка заполнения таблицы: " . $this->conn->error;
        }
    }

    /**
     * Получает данные из таблицы test, фильтруя по результатам 'normal' и 'success'.
     * Доступен извне класса.
     *
     * @return array Массив данных, соответствующих критериям.
     */
    public function get()
    {
        $sql = "SELECT * FROM test WHERE result IN ('normal', 'success')";
        
        $result = $this->conn->query($sql);
        
        // Проверка результата запроса
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            echo "Ошибка выполнения запроса: " . $this->conn->error;
            return [];
        }
    }
}

// Создаем экземпляр класса и вызываем метод get
$instance = new Init();
$data = $instance->get();
// Разбиваем на строки, чтобы понятнее было
foreach ($data as $row) {
    echo "ID: {$row['id']} | Name: {$row['name']} | Value: {$row['value']} | Date: {$row['date']} | Result: {$row['result']}<br>";
}