<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;

require_once "config/db.php";

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        transaction_type AS type,
        amount,
        description AS title,
        transaction_date AS date,
        source
    FROM transactions
    WHERE user_id = ?
    ORDER BY transaction_date DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
