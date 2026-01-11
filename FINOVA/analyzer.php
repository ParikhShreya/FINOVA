<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$avatar = "U";
if (!empty($_SESSION['first_name'])) {
    $avatar = strtoupper(substr($_SESSION['first_name'], 0, 1));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finova - Bank Statement Analyzer</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/analyzer.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<nav class="navbar">
  <div class="nav-left">
    <img src="assets/images/finovo_logo.jpeg" alt="Finova Logo" class="logo-img">
    <span class="brand-name">Finova</span>
  </div>

  <div class="nav-center">
    <a href="dashboard.php">Dashboard</a>
    <a href="transaction.php">Transactions</a>
    <a href="goals.php">Goals</a>
    <a href="analyzer.php" class="active">Analyzer</a>
    <a href="coach.php">AI Coach</a>
  </div>

  <div class="nav-right">
    <div class="dropdown">
      <button class="dropdown-btn">Online <span class="arrow">▼</span></button>
      <div class="dropdown-menu">
        <a href="#">📊 Financial Consultant</a>
        <a href="#">📘 Financial Education</a>
      </div>
    </div>
    <div class="profile-avatar"><?php echo $avatar; ?></div>
  </div>
</nav>

<main class="dashboard-wrapper">
    <header class="page-header">
        <h1>Bank Statement Analyzer</h1>
        <p>Upload and analyze your bank statements instantly</p>
    </header>

    <div class="card upload-card">
        <div class="upload-title">
            <i data-lucide="upload-cloud"></i> Upload Bank Statement
            <span>Drag & drop or click to browse files (PDF, CSV, Excel)</span>
        </div>

        <div class="drop-zone" id="dropZone">
            <i data-lucide="file-up" class="big-icon"></i>
            <p>Drag & Drop Files Here</p>
            <small>Supports PDF, CSV, XLS, XLSX</small>
            <button class="btn-choose" onclick="document.getElementById('fileInput').click()">Choose File</button>
            <input type="file" id="fileInput" hidden accept=".pdf,.csv,.xls,.xlsx">
        </div>

        <div class="upload-footer-features">
            <div class="feat"><i data-lucide="clock"></i> Auto Categorization</div>
            <div class="feat"><i data-lucide="zap"></i> Spending Patterns</div>
            <div class="feat"><i data-lucide="bell"></i> Smart Alerts</div>
        </div>
    </div>

    <div id="resultsArea" style="display: none;">
        <h3 class="results-title">Analysis Results</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="s-label">Total Transactions <i data-lucide="file-text" class="blue"></i></div>
                <div class="s-value" id="resTotal">0</div>
                <p class="s-sub">Last 30 days</p>
            </div>
            <div class="stat-card">
                <div class="s-label">Total Spent <i data-lucide="trending-down" class="red"></i></div>
                <div class="s-value" id="resSpent">₹0</div>
                <p class="s-sub">This period</p>
            </div>
        </div>

        <div class="charts-row">
            <div class="card chart-card">
                <h4>Spending by Category</h4>
                <div class="chart-container">
                    <canvas id="categoryPie"></canvas>
                </div>
            </div>

            <div class="card chart-card">
                <h4>Monthly Spending Trend</h4>
                <div class="chart-container">
                    <canvas id="spendingBar"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="analyzer.js"></script>
<script>lucide.createIcons();</script>
</body>
</html>
