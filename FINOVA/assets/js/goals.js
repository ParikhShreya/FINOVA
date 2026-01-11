let goals = [];
let projectionChart = null;

function openGoalDrawer() {
    document.getElementById('goalDrawer').classList.add('active');
}
function closeGoalDrawer() {
    document.getElementById('goalDrawer').classList.remove('active');
}

/* -------- LOAD GOALS FROM DB -------- */
fetch("fetch_goals.php")
    .then(res => res.json())
    .then(data => {
        goals = data.map(g => ({
            id: g.id,
            name: g.name,
            target: parseFloat(g.target),
            current: parseFloat(g.saved),
            monthly: parseFloat(g.monthly),
            category: g.category
        }));
        updateUI();
    });

/* -------- SAVE GOAL -------- */
document.getElementById('goalForm').onsubmit = function(e) {
    e.preventDefault();

    const newGoal = {
        name: gName.value,
        target: parseFloat(gTarget.value),
        saved: parseFloat(gCurrent.value),
        monthly: parseFloat(gMonthly.value),
        category: gCat.value
    };

    fetch("save_goal.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(newGoal)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === "ok") {
            goals.push({
                ...newGoal,
                current: newGoal.saved
            });

            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#6366f1', '#10b981', '#ffffff']
            });

            updateUI();
            closeGoalDrawer();
            document.getElementById('goalForm').reset();
        }
    });
};

function updateUI() {
    const container = document.getElementById('goalsContainer');
    container.innerHTML = '';

    let totalT = 0, totalS = 0, totalM = 0;

    goals.forEach(g => {
        totalT += g.target;
        totalS += g.current;
        totalM += g.monthly;

        const progress = Math.min(((g.current / g.target) * 100), 100).toFixed(0);
        const monthsRemaining = Math.ceil((g.target - g.current) / g.monthly);

        const card = `
            <div class="goal-premium-card">
                <div class="goal-header">
                    <div>
                        <span class="category-tag">${g.category}</span>
                        <h3 style="margin-top:10px;">${g.name}</h3>
                    </div>
                </div>

                <div class="progress-container">
                    <div class="p-text">
                        <span>Progress</span>
                        <span>${progress}%</span>
                    </div>
                    <div class="p-bar-bg"><div class="p-bar-fill" style="width:${progress}%"></div></div>
                </div>

                <div class="goal-meta-grid">
                    <div>
                        <small>Current</small>
                        <strong>₹${g.current.toLocaleString()}</strong>
                    </div>
                    <div style="text-align:right">
                        <small>Target</small>
                        <strong>₹${g.target.toLocaleString()}</strong>
                    </div>
                </div>

                <div class="smart-eta">
                    Estimated completion in <strong>${monthsRemaining} months</strong>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });

    document.getElementById('sumTarget').innerText = `₹${(totalT/1000).toFixed(1)}K`;
    document.getElementById('sumSaved').innerText = `₹${(totalS/1000).toFixed(1)}K`;
    document.getElementById('sumMonthly').innerText = `₹${totalM.toLocaleString()}`;

    const totalP = (totalS / totalT) * 100 || 0;
    document.getElementById('totalProgressFill').style.width = totalP + '%';

    initProjectionChart(totalS, totalM);
    lucide.createIcons();
}

function initProjectionChart(saved, monthly) {
    const ctx = document.getElementById('projectionChart').getContext('2d');
    if (projectionChart) projectionChart.destroy();

    const labels = ['Now', '3m', '6m', '9m', '12m'];
    const data = [
        saved,
        saved + monthly * 3,
        saved + monthly * 6,
        saved + monthly * 9,
        saved + monthly * 12
    ];

    projectionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });
}
