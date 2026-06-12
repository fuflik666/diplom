<?php

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';
require_once 'sms_sender.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $barber_id = (int)($_POST['barber_id'] ?? 0);
        $service_id = (int)($_POST['service_id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $time = trim($_POST['time'] ?? '');

        if (
            empty($name) ||
            empty($phone) ||
            empty($date) ||
            empty($time) ||
            empty($barber_id) ||
            empty($service_id)
        ) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Заповніть всі поля'
            ]);

            exit;
        }

        $stmt = $conn->prepare("
            INSERT INTO bookings
            (
                client_name,
                client_phone,
                barber_id,
                service_id,
                booking_date,
                booking_time
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssiiss",
            $name,
            $phone,
            $barber_id,
            $service_id,
            $date,
            $time
        );

        if (!$stmt->execute()) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Помилка запису: ' . $stmt->error
            ]);

            exit;
        }

        $barberName = 'Барбер';
        $serviceName = 'Послуга';

        if ($barber_id == 1) $barberName = 'Олександр';
        if ($barber_id == 2) $barberName = 'Дмитро';
        if ($barber_id == 3) $barberName = 'Артем';

        if ($service_id == 1) $serviceName = 'Чоловіча стрижка';
        if ($service_id == 2) $serviceName = 'Стрижка машинкою';
        if ($service_id == 3) $serviceName = 'Стрижка бороди';
        if ($service_id == 4) $serviceName = 'Укладка волосся';
        if ($service_id == 5) $serviceName = 'Стрижка + Борода';
        if ($service_id == 6) $serviceName = 'Дитяча стрижка';

        $smsText = "Royal Barber

$name, ваш запис підтверджено ✅

Послуга: $serviceName
Барбер: $barberName
Дата: $date
Час: $time

Чекаємо на вас!";

        $smsSent = sendSms($phone, $smsText);

        if ($smsSent) {

            echo json_encode([
                'status' => 'success',
                'message' => 'Запис створено! SMS відправлено.'
            ]);

        } else {

            echo json_encode([
                'status' => 'success',
                'message' => 'Запис створено, але SMS не відправлено.'
            ]);
        }

    } catch (Exception $e) {

        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
?>