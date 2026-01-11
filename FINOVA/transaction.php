<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}
$avatar = "U"; // fallback

if (!empty($_SESSION['first_name'])) {
    $avatar = strtoupper(substr($_SESSION['first_name'], 0, 1));
}


require_once "config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("
        INSERT INTO transactions 
        (user_id, transaction_type, amount, description, transaction_date, source)
        VALUES (?, ?, ?, ?, ?, 'manual')
    ");

    $stmt->bind_param(
        "isdss",
        $_SESSION['user_id'],
        $data['type'],          // income / expense
        $data['amount'],
        $data['description'],
        $data['date']
    );

    $stmt->execute();

    echo json_encode(["status" => "ok"]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transactions | FINOVA</title>
    <link rel="stylesheet" href="assets/css/transactions.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
  <!-- Left -->
  <div class="nav-left">
    <img src="assets/images/finovo_logo.jpeg" alt="Finova Logo" class="logo-img">
    <span class="brand-name">Finova</span>
  </div>

  <!-- Center -->
  <div class="nav-center">
    <a href="dashboard.php">Dashboard</a>
    <a href="transaction.php"  class="active" >Transactions</a>
    <a href="goals.php">Goals</a>
    <a href="analyzer.php">Analyzer</a>
    <a href="coach.php">AI Coach</a>
  </div>

  <!-- Right -->
  <div class="nav-right">
    <div class="dropdown">
      <button class="dropdown-btn">
        Online <span class="arrow">▼</span>
      </button>

      <div class="dropdown-menu">
        <a href="#">📊 Financial Consultant</a>
        <a href="#">📘 Financial Education</a>
      </div>
    </div>
  <div class="nav-right">
    <div class="profile-avatar"><?php echo $avatar; ?></div>
  </div>
  </div>
</nav>

<div class="container">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1>Transactions</h1>
            <p>View and manage your transaction history</p>
        </div>
        <div class="actions">
         <button class="btn-info" id="connectBankBtn">Connect Bank</button>
         <button class="btn-primary" id="addTransactionBtn">+ Add Transaction</button>
        <button class="btn-secondary" id="exportBtn">Export</button>

        </div>

    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card">
            <span class="label">Total Income</span>
            <h2 class="green" id="totalIncome">$0.00</h2>
            <p>This month</p>
        </div>

        <div class="card">
            <span class="label">Total Expenses</span>
            <h2 class="red" id="totalExpense">$0.00</h2>
            <p>This month</p>
        </div>

        <div class="card">
            <span class="label">Net Cash Flow</span>
            <h2 class="green" id="netCash">$0.00</h2>
            <p>Surplus</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters">
        <input type="text" id="searchInput" placeholder="Search transactions...">

        <select id="categoryFilter">
            <option value="">All Categories</option>
            <option>Food & Dining</option>
            <option>Travel</option>
            <option>Entertainment</option>
            <option>Income</option>
        </select>

        <input type="date" id="dateFilter">
    </div>

    <!-- Transactions -->
    <div class="transactions">
        <h3>Recent Transactions</h3>

        <!-- JS will render transactions here -->
        <div id="transactionsList">
            <!-- Existing static data can be removed later -->
        </div>
    </div>

</div>
<div class="modal-overlay" id="addModal">
    <div class="modal-box">
        <button class="close-modal" id="closeModal">×</button>
        <h2>Add Entry</h2>
        <label>Type</label>
        <div class="type-toggle">
            <button id="expenseBtn" class="active">Expense</button>
            <button id="incomeBtn">Income</button>
        </div>

        <label>Amount (₹)</label>
        <input type="number" id="amountInput" placeholder="0.00">

        <label>Category</label>
        <select id="categoryInput">
            <option>Food</option>
            <option>Travel</option>
            <option>Entertainment</option>
            <option>Shopping</option>
        </select>

        <label>Description</label>
        <input type="text" id="titleInput" placeholder="E.g. Lunch">

        <label>Date</label>
        <input type="date" id="dateInput">

        <button class="confirm-btn" id="confirmTransaction">Confirm Transaction</button>
    </div>
</div>
<div class="modal-overlay" id="otpModal">
    <div class="modal-box">
        <button class="close-modal" id="closeOtp">×</button>
        <h2>Verify Bank</h2>
        <p style="font-size:13px;color:#6b7280;margin-bottom:10px;">
            OTP sent to your registered mobile number
        </p>
        <input type="text" id="otpInput" placeholder="Enter 4-digit OTP" maxlength="4">
        <button class="confirm-btn" id="verifyOtpBtn">Verify</button>
    </div>
</div>

<!-- JavaScript -->
<script src="assets/js/transactions.js"></script>
</body>
</html>
