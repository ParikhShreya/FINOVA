<?php
session_start();
header('Content-Type: application/json');

// Disable PHP warnings/notices from being output
error_reporting(E_ERROR | E_PARSE);

require_once "config/db.php";


if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}


$userId = $_SESSION['user_id'];

if (!isset($_FILES['statement'])) {
    echo json_encode(["error" => "No file received"]);
    exit;
}

$uploadDir = "uploads/statements/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$filename = time() . "_" . basename($_FILES['statement']['name']);
$path = $uploadDir . $filename;

if (!move_uploaded_file($_FILES['statement']['tmp_name'], $path)) {
    echo json_encode(["error" => "Failed to move uploaded file"]);
    exit;
}

// Save file info in DB
$stmt = $pdo->prepare("
    INSERT INTO uploaded_documents (user_id, file_path, file_type, processed)
    VALUES (?, ?, ?, 0)
");
$stmt->execute([$userId, $path, $_FILES['statement']['type']]);

// Demo data (fake OCR/parse)
$fakeData = [
    ["Food", 450, "expense"],
    ["Shopping", 1200, "expense"],
    ["Salary", 25000, "income"],
    ["Transport", 180, "expense"]
];

foreach ($fakeData as $row) {
    $pdo->prepare("
        INSERT INTO transactions 
        (user_id, amount, transaction_type, description, transaction_date, source)
        VALUES (?, ?, ?, ?, CURDATE(), 'ocr')
    ")->execute([$userId, $row[1], $row[2], $row[0]]);
}

$totalSpent = 0;
foreach ($fakeData as $d) {
    if($d[2] === 'expense') $totalSpent += $d[1];
}

echo json_encode([
    "total" => count($fakeData),
    "spent" => $totalSpent
]);
