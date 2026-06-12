<?php

header('Content-Type: application/json; charset=utf-8');

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $portfolio = trim($_POST['portfolio'] ?? '');

    if (empty($name) || empty($phone)) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Заповніть всі поля'
        ]);

        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO job_applications
        (
            full_name,
            phone,
            portfolio
        )
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param(
        "sss",
        $name,
        $phone,
        $portfolio
    );

    if ($stmt->execute()) {

        echo json_encode([
            'status' => 'success',
            'message' => 'Вашу заявку успішно відправлено!'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Помилка при відправці заявки'
        ]);
    }
}
?>