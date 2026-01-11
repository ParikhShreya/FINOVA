// Initial Data for Charts
let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
let incomeData = [4000, 4200, 3800, 4500, 4100, 4200];
let expenseData = [2500, 2600, 2400, 2900, 2700, 2850];

let lineChart, pieChart;

// Initialize Charts on Load
window.onload = function() {
    renderCharts();
    updateStats();
};

function renderCharts() {
    // Line Chart
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                { label: 'Income', data: incomeData, borderColor: '#10b981', tension: 0.4, fill: false },
                { label: 'Expense', data: expenseData, borderColor: '#ef4444', tension: 0.4, fill: false }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Pie Chart (Static for now, but colors matching UI)
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    pieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Stocks', 'Savings', 'Crypto', 'Gold'],
            datasets: [{
                data: [40, 30, 15, 15],
                backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: { cutout: '70%', plugins: { legend: { display: false } } }
    });
}

function applyData() {
    const inc = parseFloat(document.getElementById('newIncome').value);
    const exp = parseFloat(document.getElementById('newExpense').value);
    const month = document.getElementById('newMonth').value;

    if (!inc || !exp || !month) {
        alert("Please fill all fields!");
        return;
    }

    // 1. Update Graph Arrays
    months.push(month);
    incomeData.push(inc);
    expenseData.push(exp);

    // 2. Update Charts
    lineChart.update();

    // 3. Update Pocket Money Mode & Stats
    updatePocketMoney(inc, exp);
    updateStats(inc, exp);
}

function updatePocketMoney(income, expense) {
    const remaining = income - expense;
    const dailyBurn = expense / 30; // simple prediction logic
    const daysLeft = Math.floor(remaining / dailyBurn);

    // Update UI elements
    const pmCard = document.querySelector('.pocket-money-card');
    pmCard.querySelector('.val').innerText = `₹${remaining.toLocaleString()}`;
    
    const alertBox = pmCard.querySelector('.prediction-alert span');
    if (daysLeft > 0) {
        alertBox.innerHTML = `At current speed, money will finish in <strong>${daysLeft} days</strong>.`;
    } else {
        alertBox.innerHTML = `<strong style="color:red">Alert: You have exceeded your budget!</strong>`;
    }

    // Update Progress Bar (Percentage of income left)
    const percentLeft = (remaining / income) * 100;
    pmCard.querySelector('.progress-bar').style.width = `${Math.max(0, percentLeft)}%`;
}

function updateStats(inc, exp) {
    // Update summary strip
    if (inc && exp) {
        document.getElementById('summaryExp').innerText = `₹${exp}`;
        document.getElementById('summarySavings').innerText = `₹${inc - exp}`;
        
        // Update Expense Ratio Card
        const ratio = Math.round((exp / inc) * 100);
        document.getElementById('expRatioDisplay').innerText = `${ratio}%`;
        document.querySelector('.progress-bar.purple').style.width = `${ratio}%`;
    }
}