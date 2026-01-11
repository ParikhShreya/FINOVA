<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$userId = $_SESSION['user_id'];

/* User info */
$userRes = $conn->query("SELECT full_name FROM users WHERE id = $userId");
$user = $userRes->fetch_assoc();

/* Current month summary */
$month = date('Y-m');
$sumRes = $conn->query("
    SELECT 
        COALESCE(total_income, 0) AS income,
        COALESCE(total_expense, 0) AS expense,
        COALESCE(net_savings, 0) AS savings
    FROM monthly_summary 
    WHERE user_id = $userId AND month_year = '$month'
");

$summary = $sumRes->num_rows 
    ? $sumRes->fetch_assoc() 
    : ['income'=>0,'expense'=>0,'savings'=>0];

/* Spending trend (last 6 months) */
$trendRes = $conn->query("
    SELECT month_year, total_expense 
    FROM monthly_summary 
    WHERE user_id = $userId
    ORDER BY month_year DESC
    LIMIT 6
");

$labels = [];
$values = [];

while ($row = $trendRes->fetch_assoc()) {
    $labels[] = $row['month_year'];
    $values[] = (float)$row['total_expense'];
}

$labels = array_reverse($labels);
$values = array_reverse($values);

/* Portfolio */
$portRes = $conn->query("
    SELECT asset_type, SUM(current_value) as total
    FROM portfolio
    WHERE user_id = $userId
    GROUP BY asset_type
");

$portfolio = [];
while ($row = $portRes->fetch_assoc()) {
    $portfolio[] = $row;
}

/* Goals */
$goalRes = $conn->query("
    SELECT goal_name, target_amount, current_amount
    FROM financial_goals
    WHERE user_id = $userId AND status = 'active'
    LIMIT 2
");

$goals = [];
while ($row = $goalRes->fetch_assoc()) {
    $goals[] = $row;
}

/* AI Insight */
$insightRes = $conn->query("
    SELECT message FROM ai_insights
    WHERE user_id = $userId
    ORDER BY created_at DESC
    LIMIT 1
");

$insight = $insightRes->num_rows ? $insightRes->fetch_assoc()['message'] : "No insights yet.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FINOVA Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #cfdcff; }
        .glass-card { background: white; border: 1px solid #e2e8f0; border-radius: 12px; }
        .ai-gradient { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
        
        /* Custom Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #0b1f4b;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }
        .nav-left { display: flex; align-items: center; gap: 0.75rem; }
        .logo-img { height: 40px; width: 40px; object-fit: contain; }
        .brand-name { font-size: 1.25rem; font-weight: 700; color: #cfdcff; }
        
        .nav-center { display: flex; gap: 1.5rem; }
        .nav-center a { 
            text-decoration: none; 
            color: #cfdcff; 
            font-size: 0.875rem; 
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-center a:hover { color: #2563eb; }
        .nav-center a.active { color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 4px; }

        .nav-right .profile-avatar {
            width: 40px;
            height: 40px;
            background: #2563eb;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
    </style>
</head>
<body class="p-0"> 
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

  


    <div class="max-w-7xl mx-auto px-4 md:px-8 pb-8">
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Welcome back, Shreyaparikh2006</h2>
            <p class="text-gray-500 text-sm">Here's your financial overview</p>
        </section>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="glass-card p-5">
                <div class="flex justify-between text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">
                    Financial Health Score <i class="fas fa-sync-alt text-green-400"></i>
                </div>
                <div class="text-3xl font-bold text-gray-800">85/100</div>
                <div class="w-full bg-white h-1.5 mt-4 rounded-full overflow-hidden">
                    <div class="bg-blue-600 h-full" style="width: 85%"></div>
                </div>
                <p class="text-xs text-green-500 mt-2 font-semibold">+5 from last month</p>
            </div>
            <div class="glass-card p-5">
                <div class="flex justify-between text-xs text-gray-400 font-bold mb-2">SAVINGS RATE <i class="fas fa-chart-line text-blue-400"></i></div>
                <div class="text-3xl font-bold text-gray-800">32%</div>
                <p class="text-xs text-gray-400 mt-6">Above average performance</p>
            </div>
            <div class="glass-card p-5">
                <div class="flex justify-between text-xs text-gray-400 font-bold mb-2">EXPENSE RATIO <i class="fas fa-bolt text-purple-400"></i></div>
                <div class="text-3xl font-bold text-gray-800">68%</div>
                <div class="w-full bg-gray-100 h-1.5 mt-4 rounded-full">
                    <div class="bg-blue-400 h-full" style="width: 68%"></div>
                </div>
                <p class="text-xs text-orange-500 mt-2 font-semibold">-2% from target</p>
            </div>
            <div class="glass-card p-5">
                <div class="flex justify-between text-xs text-gray-400 font-bold mb-2">GOAL PROGRESS <i class="fas fa-bullseye text-pink-400"></i></div>
                <div class="text-3xl font-bold text-gray-800">67%</div>
                <div class="w-full bg-gray-100 h-1.5 mt-4 rounded-full">
                    <div class="bg-blue-800 h-full" style="width: 67%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2">On track to meet targets</p>
            </div>
        </div>

        <div class="glass-card p-4 grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 items-center">
            <div class="flex items-center gap-4 px-4 md:border-r border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-wallet text-blue-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Monthly Budget</p>
                    <p class="text-lg font-extrabold text-[#1b2559]">$2,850</p>
                </div>
            </div>

            <div class="flex items-center gap-4 px-4 md:border-r border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-green-600 uppercase tracking-tight">Income (Month)</p>
                    <p class="text-lg font-extrabold text-[#1b2559]">$4,200</p>
                </div>
            </div>

            <div class="flex items-center gap-4 px-4 md:border-r border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-arrow-down text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-red-500 uppercase tracking-tight">Expenses (Month)</p>
                    <p class="text-lg font-extrabold text-[#1b2559]">$2,850</p>
                </div>
            </div>

            <div class="flex items-center gap-4 px-4">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-piggy-bank text-purple-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-purple-600 uppercase tracking-tight">Net Savings</p>
                    <p class="text-lg font-extrabold text-[#1b2559]">$1,350</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 glass-card p-6">
                <h3 class="font-bold text-gray-800 mb-6">Investment Summary</h3>
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="w-48 h-48">
                        <canvas id="investmentChart"></canvas>
                    </div>
                    <div class="flex-1 w-full space-y-3">
                        <div class="flex justify-between text-sm"><span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-blue-600"></div> Stocks</span> <strong>$45,000 (45%)</strong></div>
                        <div class="flex justify-between text-sm"><span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-purple-600"></div> Bonds</span> <strong>$25,000 (25%)</strong></div>
                        <div class="flex justify-between text-sm"><span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-pink-500"></div> Crypto</span> <strong>$15,000 (15%)</strong></div>
                        <div class="flex justify-between text-sm"><span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-green-400"></div> Cash</span> <strong>$15,000 (15%)</strong></div>
                        <div class="pt-3 border-t flex justify-between font-bold"><span>Total Portfolio</span> <span>$100,000</span></div>
                    </div>
                </div>
            </div>

            <div class="ai-gradient rounded-xl p-6 text-white flex flex-col justify-between shadow-lg">
                <div>
                    <h3 class="flex items-center gap-2 font-bold mb-6 text-lg">
                        <i class="fas fa-robot"></i> AI Financial Coach
                    </h3>
                    <div class="bg-white/10 p-4 rounded-lg mb-4">
                        <p class="text-xs font-bold uppercase mb-1 opacity-80">Smart Insight</p>
                        <p class="text-sm">You're spending 12% more on food this month. Consider meal planning to save $120.</p>
                    </div>
                    <div class="bg-white/10 p-4 rounded-lg">
                        <p class="text-xs font-bold uppercase mb-1 opacity-80">Goal Update</p>
                        <p class="text-sm">You're on track to reach your vacation goal by September!</p>
                    </div>
                </div>
                <button class="w-full bg-white text-blue-600 font-bold py-2 rounded-lg mt-6 hover:bg-gray-100 transition">View All Recommendations</button>
            </div>
        </div>

        <div class="glass-card p-6 mb-8">
            <h3 class="font-bold text-gray-800 mb-6">Monthly Spending Trend</h3>
            <div class="h-64">
                <canvas id="spendingChart"></canvas>
            </div>
        </div>

        <div class="glass-card p-6">
            <h3 class="font-bold text-gray-800 mb-6">Goal Progress Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="font-bold text-sm">Vacation Fund</span>
                        <span class="bg-green-100 text-green-600 text-[10px] px-2 py-0.5 rounded font-bold">ON TRACK</span>
                    </div>
                    <p class="text-xs text-gray-400 mb-4">Target: $5,000</p>
                    <div class="w-full bg-gray-100 h-2 rounded-full mb-2">
                        <div class="bg-blue-600 h-full rounded-full" style="width: 67%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-gray-400 font-bold">
                        <span>Progress: $3,350 / $5,000</span>
                        <span>67%</span>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="font-bold text-sm">Emergency Fund</span>
                        <span class="bg-blue-100 text-blue-600 text-[10px] px-2 py-0.5 rounded font-bold">IN PROGRESS</span>
                    </div>
                    <p class="text-xs text-gray-400 mb-4">Target: $10,000</p>
                    <div class="w-full bg-gray-100 h-2 rounded-full mb-2">
                        <div class="bg-blue-600 h-full rounded-full" style="width: 42%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-gray-400 font-bold">
                        <span>Progress: $4,200 / $10,000</span>
                        <span>42%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Investment Donut Chart
        new Chart(document.getElementById('investmentChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [45, 25, 15, 15],
                    backgroundColor: ['#2563eb', '#9333ea', '#ec4899', '#4ade80'],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Spending Line Chart
        new Chart(document.getElementById('spendingChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Spending',
                    data: [2400, 2200, 2900, 2600, 3100, 2900],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { display: true, color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
</body>
</html>