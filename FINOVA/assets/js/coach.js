const chatBox = document.getElementById("chatBox");
const input = document.getElementById("userInput");

let financeData = null;

/* Load user financial data */
fetch("coach_data.php")
  .then(r => r.json())
  .then(d => financeData = d);

function addMessage(text, sender) {
  const div = document.createElement("div");
  div.className = "msg " + sender;
  div.innerHTML = sender === "ai" ? `<b>AI Coach</b><br>${text}` : text;
  chatBox.appendChild(div);
  chatBox.scrollTop = chatBox.scrollHeight;
}

function send() {
  const q = input.value.trim();
  if (!q) return;
  addMessage(q, "user");
  input.value = "";

  setTimeout(() => {
    addMessage(getAIResponse(q), "ai");
  }, 600);
}

function ask(topic) {
  input.value = topic;
  send();
}

function getAIResponse(q) {
  if (!financeData) return "Analyzing your data…";

  q = q.toLowerCase();
  const cats = financeData.categories;
  const goals = financeData.goals;

  if (q.includes("food")) {
    const food = cats["Food"] || 0;
    return `You spent ₹${food.toLocaleString()} on food.
This is one of your top categories.
Tip: Reducing delivery orders could save ~₹2,000/month.`;
  }

  if (q.includes("biggest")) {
    const top = Object.entries(cats).sort((a,b)=>b[1]-a[1])[0];
    return `Your biggest expense is **${top[0]}** – ₹${top[1].toLocaleString()}.`;
  }

  if (q.includes("goal")) {
    if (!goals.length) return "You have no active goals yet.";
    const g = goals[0];
    const p = ((g.saved / g.target) * 100).toFixed(0);
    return `Your goal "${g.name}" is ${p}% complete.
Keep saving consistently to reach it early.`;
  }

  if (q.includes("save")) {
    return `Based on your spending pattern:
• Cut top category by 10%
• Cancel unused services  
You can increase savings by ₹3,000–₹4,000/month.`;
  }

  return "Ask me about expenses, goals, or savings strategy.";
}
