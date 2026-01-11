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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transactions | FINOVA</title>
    <link rel="stylesheet" href="assets/css/goals.css">
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
    <a href="transaction.php" >Transactions</a>
    <a href="goals.php" class="active">Goals</a>
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
        <main class="main-viewport">
            <header class="luxury-header">
                <div class="header-content">
                    
                    <h1>Financial Goals </h1>
                    <p>Stop wishing, start planning. Your future self will thank you.</p>
                </div>
                <div class="header-actions">
                    <button class="btn-premium" onclick="openGoalDrawer()">
                        <i data-lucide="sparkles"></i> Create New Goal
                    </button>
                </div>
            </header>

            <div class="stats-glass-grid">
                <div class="glass-card">
                    <div class="card-icon blue"><i data-lucide="target"></i></div>
                    <div class="card-data">
                        <span class="label">Total Targets</span>
                        <h2 id="sumTarget">₹0</h2>
                    </div>
                </div>
                <div class="glass-card">
                    <div class="card-icon green"><i data-lucide="trending-up"></i></div>
                    <div class="card-data">
                        <span class="label">Total Saved</span>
                        <h2 id="sumSaved">₹0</h2>
                    </div>
                    <div class="progress-mini-bar"><div id="totalProgressFill" class="fill"></div></div>
                </div>
                <div class="glass-card">
                    <div class="card-icon purple"><i data-lucide="zap"></i></div>
                    <div class="card-data">
                        <span class="label">Monthly Speed</span>
                        <h2 id="sumMonthly">₹0</h2>
                    </div>
                </div>
            </div>

            <div class="content-split">
                <section class="goals-feed">
                    <div class="feed-header">
                        <h3>Active Milestones</h3>
                        <div class="view-toggles">
                            <button class="active">All</button>
                            <button>Short Term</button>
                            <button>Long Term</button>
                        </div>
                    </div>
                    <div id="goalsContainer" class="goals-grid">
                        </div>
                </section>

                <aside class="analytics-sidebar">
                    <div class="glass-card chart-card">
                        <h3>Savings Projection</h3>
                        <p class="sub">AI-based timeline prediction</p>
                        <div class="chart-container">
                            <canvas id="projectionChart"></canvas>
                        </div>
                    </div>
                    <div class="glass-card ai-insight">
                        <div class="ai-head">
                            <i data-lucide="bot"></i>
                            <span>Finova AI Coach</span>
                        </div>
                        <p id="ai-advice">Add a goal to see personalized financial advice!</p>
                    </div>
                </aside>
            </div>
        </main>
    </div>

    <div id="goalDrawer" class="drawer-overlay" onclick="closeGoalDrawer()">
        <div class="drawer-panel" onclick="event.stopPropagation()">
            <div class="drawer-header">
                <h2>Define Your Dream</h2>
               
            </div>
            <form id="goalForm">
                <div class="input-group">
                    <label>What are you saving for?</label>
                    <input type="text" id="gName" placeholder="e.g. Tesla Model S, Maldives Trip" required>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <label>Target (₹)</label>
                        <input type="number" id="gTarget" placeholder="Amount" required>
                    </div>
                    <div class="input-group">
                        <label>Saved (₹)</label>
                        <input type="number" id="gCurrent" placeholder="Initial deposit" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Monthly Contribution (₹)</label>
                    <input type="number" id="gMonthly" placeholder="How much per month?" required>
                </div>
                <div class="input-group">
                    <label>Category Icon</label>
                    <select id="gCat">
                        <option value="Home">Life & Assets</option>
                        <option value="Travel">Travel & Experiences</option>
                        <option value="tech">Technology & Gadgets</option>
                        <option value="education">Education & Growth</option>
                        <option value="Safety">Insurances</option>
                        <option value="other">other goals</option>
                    </select>
                </div>
                <button type="submit" class="btn-confirm">Launch Goal 🚀</button>
            </form>
        </div>
    </div>

    <script src="assets/js/goals.js"></script>
</body>
</html>