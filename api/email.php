<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo json_encode(['exists' => false, 'error' => 'Invalid email']);
        exit;
    }

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=gradelens', 'root', '');
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $exists = $stmt->fetchColumn() > 0;

        echo json_encode(['exists' => $exists]);
    } catch (PDOException $e) {
        echo json_encode(['exists' => false, 'error' => 'Database error']);
    }
}