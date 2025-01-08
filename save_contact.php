<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Путь к базе данных SQLite
$dbFile = __DIR__ . '/contacts.db'; 

try {

    $pdo = new PDO("sqlite:" . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Проверяем существование таблицы
    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        message TEXT,
        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Получаем данные из POST-запроса
    $postData = json_decode(file_get_contents('php://input'), true);

    // Проверка корректности данных
    if (!isset($postData['name'], $postData['email'], $postData['message'])) {
        throw new Exception("Не все данные переданы");
    }

    // Вставляем данные в таблицу
    $sql = "INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $postData['name'],
        ':email' => $postData['email'],
        ':message' => $postData['message'],
    ]);

    header('Content-Type: application/json'); 
    echo json_encode(['message' => 'Данные успешно сохранены']);
} catch (Exception $e) {
    
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Ошибка сервера: ' . $e->getMessage()]);
}
?>
