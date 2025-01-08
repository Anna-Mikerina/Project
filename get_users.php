<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Путь к базе данных SQLite
$dbFile = __DIR__ . '/contacts.db'; 

try {
    
    $pdo = new PDO("sqlite:" . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT name, message, date FROM contacts");

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Возвращаем данные в формате JSON
    header('Content-Type: application/json');
    echo json_encode($users);

} catch (Exception $e) {
    // Если произошла ошибка, возвращаем код 500 и сообщение об ошибке
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка сервера: ' . $e->getMessage()]);
}
?>
