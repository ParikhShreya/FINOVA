const fileInput = document.getElementById('fileInput');
let categoryChart, trendChart;

fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('statement', file);

    fetch("upload_statement.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert("Upload error: " + data.error);
            return;
        }

        document.getElementById('resultsArea').style.display = 'block';
        document.getElementById('resTotal').innerText = data.total;
        document.getElementById('resSpent').innerText = `₹${data.spent}`;
        updateCharts(data.spent);

        window.scrollTo({ top: 600, behavior: 'smooth' });
    })
    .catch(err => console.error("UPLOAD ERROR:", err));
});

function updateCharts(seed) {
    const pieCtx = document.getElementById('categoryPie').getContext('2d');
    const barCtx = document.getElementById('spendingBar').getContext('2d');

    if(categoryChart) categoryChart.destroy();
    if(trendChart) trendChart.destroy();

    categoryChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Food','Shopping','Bills','Travel'],
            datasets: [{
                data: [seed*0.25, seed*0.5, seed*0.15, seed*0.1],
                backgroundColor: ['#3b82f6','#8b5cf6','#ec4899','#f59e0b'],
                borderWidth: 0
            }]
        },
        options: { cutout: '75%', plugins: { legend: { position: 'bottom' } } }
    });

    trendChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Monthly Spending',
                data: [seed*0.8, seed, seed*0.9, seed*1.2, seed, seed*1.1],
                backgroundColor: '#3b82f6',
                borderRadius: 4
            }]
        }
    });
}
