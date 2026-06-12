<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

$result = $conn->query("SELECT * FROM job_applications ORDER BY created_at DESC");

if (!$result) {
    die("SQL помилка: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Заявки на вакансії</title>
</head>
<body>

<h1>Заявки на вакансії</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>ПІБ</th>
        <th>Телефон</th>
        <th>Портфоліо</th>
        <th>Дата</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['portfolio']) ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
    <?php endwhile; ?>

</table>


</body>
</html>