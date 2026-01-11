<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FINOVA – AI Coach</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/coach-style.css">
</head>
<body>

<nav class="navbar">
  <!-- Logo -->
  <div class="nav-left">
    <img src="assets/images/finovo_logo.jpeg" alt="Finova Logo" class="logo-img">
    <span class="brand-name">Finova</span>
  </div>

  <!-- Navigation Links -->
  <div class="nav-center">
    <a href="dashboard.php">Dashboard</a>
    <a href="transaction.php">Transactions</a>
    <a href="goals.php">Goals</a>
    <a href="analyzer.php">Analyzer</a>
    <a href="coach.php">AI Coach</a>
  </div>

  <!-- Profile -->
  <div class="nav-right">
    <div class="profile-avatar">S</div>
  </div>
</nav>


<main class="container">

  <!-- CHAT -->
  <section class="chat-section">
    <h2>✨ Chat with Your AI Coach</h2>
    <p class="sub">Ask questions about your finances</p>

    <div class="chat-box" id="chatBox">
      <div class="msg ai">
        <b>AI Coach</b><br>
        Hello Shreya! I’ve analyzed your financial data and I’m here to help you spend smarter, save better, and reach your goals faster.
      </div>
    </div>

    <div class="suggestions">
      <button onclick="ask('reduce food spending')">Reduce food spending</button>
      <button onclick="ask('vacation goal')">Vacation goal</button>
      <button onclick="ask('biggest expenses')">Biggest expenses</button>
      <button onclick="ask('increase savings')">Increase savings</button>
    </div>

    <div class="input-box">
      <input id="userInput" placeholder="Ask me anything about your finances...">
      <button onclick="send()">➤</button>
    </div>
  </section>

  <!-- SIDE PANEL -->
  <aside class="side-panel">

    <div class="card">
      <h3>Active Insights</h3>
      <div class="insight blue">📈 Food spending ↑ 12%</div>
      <div class="insight green">🎯 Goal 67% completed</div>
      <div class="insight orange">⚠ 3 unused subscriptions</div>
    </div>

    <div class="card">
      <h3>Top Recommendations</h3>
      <p><b>Meal Planning</b><br><small>Save ₹3,000/month</small></p>
      <p><b>Optimize Subscriptions</b><br><small>Save ₹500/month</small></p>
      <p><b>Emergency Fund</b><br><small>Improve security</small></p>
    </div>

    <div class="card">
      <h3>AI Agents Active</h3>
      <ul>
        <li>Spending Analyst <span class="dot"></span></li>
        <li>Goal Planner <span class="dot"></span></li>
        <li>Risk Monitor <span class="dot"></span></li>
        <li>Financial Coach <span class="dot"></span></li>
      </ul>
    </div>

  </aside>

</main>

<script src="assets/js/coach.js"></script>
</body>
</html>
