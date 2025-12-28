// 1. Modal Logic
function openModal(imgUrl) {
    const modal = document.getElementById('imageModal');
    const fullImg = document.getElementById('fullImg');
    const captionText = document.getElementById('caption');
    const titleText = document.querySelector('.title').innerText;

    modal.style.display = "block";
    fullImg.src = imgUrl;
    captionText.innerHTML = titleText;
}

function closeModal() {
    document.getElementById('imageModal').style.display = "none";
}

document.addEventListener('keydown', function(e) {
    if (e.key === "Escape") closeModal();
});

// 2. Starfield Animation
const canvas = document.getElementById('starfield');
const ctx = canvas.getContext('2d');
let stars = [];

function initStars() {
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
    stars = [];
    for (let i = 0; i < 150; i++) {
        stars.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            size: Math.random() * 1.5,
            baseX: 0,
            baseY: 0
        });
    }
}

document.querySelector('.info-panel').addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    const mouseX = e.clientX - rect.left;
    const mouseY = e.clientY - rect.top;

    stars.forEach(star => {
        star.baseX = (mouseX - canvas.width / 2) * 0.05;
        star.baseY = (mouseY - canvas.height / 2) * 0.05;
    });
});

function drawStars() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "rgba(255, 255, 255, 0.5)";
    stars.forEach(star => {
        ctx.beginPath();
        ctx.arc(star.x + star.baseX, star.y + star.baseY, star.size, 0, Math.PI * 2);
        ctx.fill();
    });
    requestAnimationFrame(drawStars);
}

window.addEventListener('resize', initStars);
initStars();
drawStars();

// 3. Tab System
function switchTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
}

// 4. Flatpickr Initialization
flatpickr("#cosmic-date", {
    altInput: true,
    altFormat: "F j, Y",
    dateFormat: "Y-m-d",
    maxDate: "today",
    disableMobile: "true"
});