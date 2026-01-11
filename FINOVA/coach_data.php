<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "unauthorized"]);
    exit;
}

$user = $_SESSION['user_id'];

/* Spending by Category */
$q1 = $conn->query("
    SELECT c.name AS category, SUM(t.amount) AS total
    FROM transactions t
    JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = $user
      AND t.transaction_type = 'expense'
    GROUP BY c.name
");

$categories = [];
while ($r = $q1->fetch_assoc()) {
    $categories[$r['category']] = (float)$r['total'];
}

/* Goals */
$q2 = $conn->query("
    SELECT goal_name, target_amount, current_amount
    FROM financial_goals
    WHERE user_id = $user
");

$goals = [];
while ($g = $q2->fetch_assoc()) {
    $goals[] = [
        "name" => $g['goal_name'],
        "target" => (float)$g['target_amount'],
        "saved" => (float)$g['current_amount']
    ];
}

echo json_encode([
    "categories" => $categories,
    "goals" => $goals
]);
