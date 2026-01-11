// 1. Spending vs Budget Line Chart
const ctxLine = document.getElementById('spendingBudgetChart').getContext('2d');
new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Actual Spending',
            data: [2400, 2200, 2800, 2500, 3100, 2900],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Budget',
            data: [2500, 2500, 2500, 2500, 2500, 2500],
            borderColor: '#10b981',
            borderDash: [5, 5],
            pointRadius: 0
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// 2. Weekly Bar Chart
const ctxBar = document.getElementById('weeklyBarChart').getContext('2d');
new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
            data: [600, 800, 950, 550],
            backgroundColor: '#8b5cf6',
            borderRadius: 8
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

// 3. Spending Distribution Donut
const ctxDonut = document.getElementById('distributionDonut').getContext('2d');
new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
        labels: ['Shopping', 'Food', 'Transportation', 'Bills'],
        datasets: [{
            data: [21, 27, 14, 38],
            backgroundColor: ['#a855f7', '#3b82f6', '#f43f5e', '#f59e0b'],
            borderWidth: 0
        }]
    },
    options: { cutout: '70%', plugins: { legend: { position: 'right' } } }
});





// 4. Category Performance List
const categories = [
    { name: 'Food & Dining', change: '+12%', amount: '$850', color: '#3b82f6' },
    { name: 'Shopping', change: '+5%', amount: '$650', color: '#a855f7' },
    { name: 'Bills & Utilities', change: '-2%', amount: '$900', color: '#f59e0b' }
];

const listContainer = document.getElementById('categoryList');
categories.forEach(cat => {
    listContainer.innerHTML += `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="width: 10px; height: 10px; border-radius: 50%; background: ${cat.color}"></span>
                <span style="font-weight: 500;">${cat.name}</span>
            </div>
            <div style="text-align: right;">
                <span style="color: ${cat.change.startsWith('+') ? '#ef4444' : '#10b981'}; font-size: 0.8rem;">${cat.change}</span>
                <div style="font-weight: bold;">${cat.amount}</div>
            </div>
        </div>
    `;
});