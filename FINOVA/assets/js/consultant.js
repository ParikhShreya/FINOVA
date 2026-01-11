const expertiseFilters = ['All', 'Investments', 'Tax Planning', 'Credit & Loans', 'Budgeting', 'Insurance'];

const consultants = [
    { id: 1, name: 'Priya Sharma', fee: 199, title: 'Certified Financial Planner', initials: 'PS', avatarBg: '#DBEAFE', avatarColor: '#1E40AF', rating: 4.9, reviews: 234, expertise: ['Investments', 'Tax Planning'], experience: 12, clients: 500, nextAvailable: 'Today, 3:00 PM', available: true },
    { id: 2, name: 'Rajesh Kumar', fee: 99, title: 'Investment Specialist', initials: 'RK', avatarBg: '#D1FAE5', avatarColor: '#15803D', rating: 4.8, reviews: 189, expertise: ['Investments', 'Budgeting'], experience: 10, clients: 380, nextAvailable: 'Tomorrow, 10:00 AM', available: false },
    { id: 3, name: 'Ananya Desai', fee: 150, title: 'Tax Expert', initials: 'AD', avatarBg: '#F3E8FF', avatarColor: '#6B21A8', rating: 4.9, reviews: 312, expertise: ['Tax Planning'], experience: 15, clients: 650, nextAvailable: 'Today, 5:30 PM', available: true },
    { id: 4, name: 'Vikram Singh', fee: 99, title: 'Loan Advisor', initials: 'VS', avatarBg: '#FEE2E2', avatarColor: '#991B1B', rating: 4.7, reviews: 156, expertise: ['Credit & Loans'], experience: 8, clients: 290, nextAvailable: 'Dec 22, 2:00 PM', available: false }
];

let selectedFilter = 'All';

function renderFilters() {
    const container = document.getElementById('filterContainer');
    if(!container) return;
    container.innerHTML = expertiseFilters.map(f => `
        <button class="filter-pill ${selectedFilter === f ? 'active' : ''}" onclick="applyFilter('${f}')">${f}</button>
    `).join('');
}

function applyFilter(f) {
    selectedFilter = f;
    renderFilters();
    renderConsultants();
}

function renderConsultants() {
    const grid = document.getElementById('consultantsGrid');
    if(!grid) return;
    const filtered = selectedFilter === 'All' ? consultants : consultants.filter(c => c.expertise.includes(selectedFilter));

    grid.innerHTML = filtered.map(c => `
        <div class="card">
            <div style="display:flex; gap:1rem; margin-bottom:1rem; width:100%;">
                <div class="avatar" style="background:${c.avatarBg}; color:${c.avatarColor}">${c.initials}</div>
                <div style="flex:1">
                    <div style="display:flex; justify-content:space-between; align-items:start">
                        <h3 style="font-size:1rem">${c.name}</h3>
                        ${c.available ? '<span style="color:#16A34A; font-size:0.7rem; font-weight:700">● ONLINE</span>' : ''}
                    </div>
                    <p style="color:#6B7280; font-size:0.8rem">${c.title}</p>
                    <div style="display:flex; align-items:center; gap:4px; font-size:0.8rem; margin-top:4px">
                        <i data-lucide="star" style="width:12px; fill:#F59E0B; color:#F59E0B"></i> <b>${c.rating}</b> (${c.reviews})
                    </div>
                </div>
            </div>
            <div style="margin-bottom:1rem">
                ${c.expertise.map(e => `<span class="tag" style="background:#F1F5F9; color:#475569">${e}</span>`).join('')}
            </div>
            <div style="display:flex; justify-content:space-between; border-top:1px solid #F1F5F9; padding-top:1rem; margin-bottom:1rem">
                <div><div style="font-size:0.7rem; color:#6B7280">Experience</div><b>${c.experience} yrs</b></div>
                <div><div style="font-size:0.7rem; color:#6B7280">Fee</div><b>₹${c.fee}</b></div>
            </div>
            <button class="btn btn-primary" onclick="openBooking(${c.id})"><i data-lucide="video" style="width:16px"></i> Book Consultation</button>
            <button class="btn btn-outline"><i data-lucide="message-square" style="width:16px"></i> Chat</button>
        </div>
    `).join('');
    lucide.createIcons();
}

// Booking Logic
const modal = document.getElementById('bookingModal');
function openBooking(id) {
    const c = consultants.find(item => item.id === id);
    document.getElementById('modalTitle').innerText = `Book ${c.name}`;
    document.getElementById('consultantFee').innerText = `₹${c.fee}`;
    modal.style.display = 'block';
}

if(document.querySelector('.close-modal')) {
    document.querySelector('.close-modal').onclick = () => modal.style.display = 'none';
}

function closeSuccess() {
    document.getElementById('successOverlay').style.display = 'none';
    document.getElementById('bookingForm').reset();
}

document.addEventListener('DOMContentLoaded', () => {
    renderFilters();
    renderConsultants();
});

// Final Submit Logic with Video Meeting Link
document.getElementById('bookingForm').onsubmit = async (e) => {
    e.preventDefault();
    
    const submitBtn = document.querySelector('.submit-booking');
    const originalText = submitBtn.innerText;
    submitBtn.innerText = "Processing...";
    submitBtn.disabled = true;

    // Generate Meeting Link (Jitsi Meet is free and open)
    const roomId = "FINOVA-" + Math.random().toString(36).substring(7).toUpperCase();
    const videoMeetLink = `https://meet.jit.si/${roomId}`;

    const bookingData = {
        email: document.getElementById('bookEmail').value,
        phone: document.getElementById('bookPhone').value,
        date: document.getElementById('bookDate').value,
        time: document.getElementById('bookTime').value,
        consultantName: document.getElementById('modalTitle').innerText,
        fee: document.getElementById('consultantFee').innerText,
        videoLink: videoMeetLink
    };

    try {
        // આ fetch રિક્વેસ્ટ તમારા Backend server (node server.js) સાથે વાત કરશે
        const response = await fetch('http://localhost:3000/send-confirmation', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(bookingData)
        });

        const result = await response.json();

        if (result.success) {
            modal.style.display = 'none';
            document.getElementById('successOverlay').style.display = 'flex';
            
            // અપોઇન્ટમેન્ટ સક્સેસ થયા પછી વિડિયો લિંક બતાવવી
            document.getElementById('successMsg').innerHTML = `
                <p>Booking details sent to <b>${bookingData.email}</b></p>
                <div style="margin-top:20px; padding:15px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; text-align:center;">
                    <p style="margin-bottom:10px; font-weight:bold; color:#166534;">Your Video Consultation Link:</p>
                    <a href="${videoMeetLink}" target="_blank" style="display:inline-block; padding:10px 20px; background:#22c55e; color:white; border-radius:8px; text-decoration:none; font-weight:bold;">
                       🎥 Join Video Call
                    </a>
                    <p style="margin-top:10px; font-size:11px; color:#6b7280;">Meeting ID: ${roomId}</p>
                </div>
            `;
            lucide.createIcons();
        }
    } catch (error) {
        // જો સર્વર ચાલુ ન હોય, તો પણ ડેમો માટે સક્સેસ સ્ક્રીન બતાવવી હોય તો આ વાપરો:
        modal.style.display = 'none';
        document.getElementById('successOverlay').style.display = 'flex';
        document.getElementById('successMsg').innerHTML = `
            <p style="color:red; font-size:12px;">Backend Offline - Demo Mode Active</p>
            <p>Details for <b>${bookingData.email}</b></p>
            <div style="margin-top:20px; padding:15px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; text-align:center;">
                <p style="margin-bottom:10px; font-weight:bold; color:#166534;">Your Video Consultation Link:</p>
                <a href="${videoMeetLink}" target="_blank" style="display:inline-block; padding:10px 20px; background:#22c55e; color:white; border-radius:8px; text-decoration:none; font-weight:bold;">
                   🎥 Join Video Call
                </a>
            </div>
        `;
    } finally {
        submitBtn.innerText = originalText;
        submitBtn.disabled = false;
    }
};

let currentRoomLink = "";

function startVideoCall(name) {
    // ૧. યુનિક રૂમ આઈડી અને લિંક જનરેટ કરો
    const roomId = "FINOVA-" + Math.random().toString(36).substring(7).toUpperCase();
    currentRoomLink = `https://meet.jit.si/${roomId}#config.prejoinPageEnabled=false`;

    // ૨. જો AI સાથે વાત કરી રહ્યા હોય તો પહેલા વિડિયો બતાવો
    if (name === "AI Expert" || name === "Finova AI Humanoid") {
        const videoModal = document.getElementById('aiVideoModal');
        const videoElement = document.getElementById('aiAvatarVideo');
        
        videoModal.style.display = 'flex';
        videoElement.play();

        // જ્યારે વિડિયો પૂરો થાય ત્યારે ઓટોમેટિક કોલ ઓપન કરો
        videoElement.onended = function() {
            skipToCall();
        };
    } else {
        // હ્યુમન કન્સલ્ટન્ટ માટે ડાયરેક્ટ કોલ
        openJitsiWindow(currentRoomLink);
        showSuccessOverlay(name);
    }
}

function skipToCall() {
    // વિડિયો બંધ કરો અને મીટિંગ ઓપન કરો
    const videoElement = document.getElementById('aiAvatarVideo');
    videoElement.pause();
    document.getElementById('aiVideoModal').style.display = 'none';
    
    openJitsiWindow(currentRoomLink);
    showSuccessOverlay("AI Expert");
}

function openJitsiWindow(link) {
    const width = 1000;
    const height = 700;
    const left = (window.innerWidth / 2) - (width / 2);
    const top = (window.innerHeight / 2) - (height / 2);
    window.open(link, 'Finova Session', `width=${width},height=${height},top=${top},left=${left}`);
}

function showSuccessOverlay(name) {
    document.getElementById('successOverlay').style.display = 'flex';
    document.getElementById('videoContainer').innerHTML = `
        <div style="text-align:left; border-left:4px solid #6366f1; padding-left:15px;">
            <p style="font-weight:bold; color:#4338ca;">Live Session with ${name}</p>
            <p style="font-size:12px; color:#6b7280; margin-bottom:10px;">The meeting has opened in a new window.</p>
            <button onclick="openJitsiWindow(currentRoomLink)" style="background:#6366f1; color:white; border:none; padding:8px 12px; border-radius:5px; cursor:pointer;">Re-join Call</button>
        </div>
    `;
    lucide.createIcons();
}