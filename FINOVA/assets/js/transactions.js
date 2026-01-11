let transactions = [];
let exportPassword = "";
let currentType = "Expense";

fetch("fetch_transaction.php")
    .then(res => res.json())
    .then(data => {
        transactions = data;
        renderTransactions(transactions);
        updateSummary();
    })
    .catch(err => console.error(err));

const modal = document.getElementById("addModal");
const titleInput = document.getElementById("titleInput");
const amountInput = document.getElementById("amountInput");
const categoryInput = document.getElementById("categoryInput");
const dateInput = document.getElementById("dateInput");

const expenseBtn = document.getElementById("expenseBtn");
const incomeBtn = document.getElementById("incomeBtn");

/* OPEN / CLOSE MODAL */

document.getElementById("addTransactionBtn").onclick = () => {
    modal.style.display = "flex";
};

document.getElementById("closeModal").onclick = () => {
    modal.style.display = "none";
};

modal.addEventListener("click", e => {
    if (e.target === modal) modal.style.display = "none";
});

/* TYPE TOGGLE */

expenseBtn.onclick = () => {
    currentType = "Expense";
    expenseBtn.classList.add("active");
    incomeBtn.classList.remove("active");

    categoryInput.innerHTML = `
        <option>Food</option>
        <option>Travel</option>
        <option>Entertainment</option>
        <option>Shopping</option>
    `;
    titleInput.placeholder = "E.g. Lunch";
};

incomeBtn.onclick = () => {
    currentType = "Income";
    incomeBtn.classList.add("active");
    expenseBtn.classList.remove("active");

    categoryInput.innerHTML = `
        <option>Salary</option>
        <option>Freelance</option>
        <option>Business</option>
        <option>Bonus</option>
    `;
    titleInput.placeholder = "E.g. Monthly Salary";
};

/* CONFIRM TRANSACTION */

document.getElementById("confirmTransaction").onclick = () => {
    const data = {
        description: titleInput.value.trim(),
        amount: parseFloat(amountInput.value),
        category: categoryInput.value,
        date: dateInput.value,
        type: currentType
    };

    if (!data.description || !data.amount || !data.date) {
        alert("Please fill all fields");
        return;
    }

    fetch("transaction.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === "ok") {
            transactions.push(data);
            renderTransactions(transactions);
            updateSummary();

            modal.style.display = "none";
            titleInput.value = "";
            amountInput.value = "";
            dateInput.value = "";
        } else {
            alert("Failed to save transaction");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Server error");
    });
};

/* RENDER */

function renderTransactions(list) {
    const container = document.getElementById("transactionsList");
    container.innerHTML = "";

    list.forEach(t => {
        const div = document.createElement("div");
        div.className = "transaction";

        div.innerHTML = `
            <div class="left">
                <div class="icon ${t.type === "Income" ? "green" : "red"}">
                    ${t.type === "Income" ? "↑" : "↓"}
                </div>
                <div>
                    <strong>${t.description || t.title}</strong>
                    <span>${t.date} • ${t.category}</span>
                </div>
            </div>
            <div class="amount ${t.type === "Income" ? "green" : "red"}">
                ${t.type === "Income" ? "+" : "-"}₹${t.amount}
            </div>
        `;
        container.appendChild(div);
    });
}

/* SUMMARY */

function updateSummary() {
    let income = 0, expense = 0;

    transactions.forEach(t => {
        if (t.type === "Income") income += Number(t.amount);
        else expense += Number(t.amount);
    });

    document.getElementById("totalIncome").innerText = `₹${income.toFixed(2)}`;
    document.getElementById("totalExpense").innerText = `₹${expense.toFixed(2)}`;
    document.getElementById("netCash").innerText = `₹${(income - expense).toFixed(2)}`;
}

/* BANK CONNECT (DEMO) */

let fakeOTP = "";

document.getElementById("connectBankBtn").onclick = () => {
    fakeOTP = Math.floor(1000 + Math.random() * 9000).toString();
    alert("Demo OTP: " + fakeOTP);
    document.getElementById("otpModal").style.display = "flex";
};

document.getElementById("closeOtp").onclick = () => {
    document.getElementById("otpModal").style.display = "none";
};

document.getElementById("verifyOtpBtn").onclick = () => {
    const entered = document.getElementById("otpInput").value;

    if (entered !== fakeOTP) {
        alert("Invalid OTP");
        return;
    }

    document.getElementById("otpModal").style.display = "none";

    const btn = document.getElementById("connectBankBtn");
    btn.innerText = "Connecting...";
    btn.disabled = true;

    setTimeout(() => {
        const bankData = [
            { description: "Swiggy", amount: 289, category: "Food", date: "2026-01-08", type: "Expense" },
            { description: "Uber", amount: 142, category: "Travel", date: "2026-01-07", type: "Expense" },
            { description: "Netflix", amount: 499, category: "Entertainment", date: "2026-01-06", type: "Expense" },
            { description: "Salary", amount: 32000, category: "Income", date: "2026-01-01", type: "Income" }
        ];

        bankData.forEach(item => {
            fetch("transaction.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(item)
            });
        });

        transactions.push(...bankData);
        renderTransactions(transactions);
        updateSummary();

        btn.innerText = "Bank Connected";
        alert("Bank verified & transactions synced!");
    }, 200);
};
