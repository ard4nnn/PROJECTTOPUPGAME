// Countdown Timer for Flash Sale
const timerContainer = document.getElementById('flash-sale-countdown');
if (timerContainer) {
    const endTimeString = timerContainer.getAttribute('data-endtime');
    const endTime = new Date(endTimeString).getTime();

    const daysEl = document.getElementById('fs-days');
    const hoursEl = document.getElementById('fs-hours');
    const minsEl = document.getElementById('fs-mins');
    const secsEl = document.getElementById('fs-secs');

    function updateCountdown() {
        const now = new Date().getTime();
        const difference = endTime - now;

        if (difference <= 0) {
            clearInterval(countdownInterval);
            if (daysEl) daysEl.textContent = '00';
            if (hoursEl) hoursEl.textContent = '00';
            if (minsEl) minsEl.textContent = '00';
            if (secsEl) secsEl.textContent = '00';
            return;
        }

        const days = Math.floor(difference / (1000 * 60 * 60 * 24));
        const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((difference % (1000 * 60)) / 1000);

        if (daysEl) daysEl.textContent = days.toString().padStart(2, '0');
        if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
        if (minsEl) minsEl.textContent = minutes.toString().padStart(2, '0');
        if (secsEl) secsEl.textContent = seconds.toString().padStart(2, '0');
    }

    updateCountdown();
    const countdownInterval = setInterval(updateCountdown, 1000);
}

// Flash Sale scrolling is now fully animated via CSS (style.css marquee). JS code removed to prevent conflicts.

// Copy Promo Voucher
function copyPromoCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        const toast = document.getElementById('toast-notif');
        const toastText = document.getElementById('toast-text');
        const isEng = window.currentLang === 'en';

        toastText.textContent = isEng ? `Promo code "${code}" copied!` : `Kode promo "${code}" berhasil disalin!`;

        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }).catch(err => {
        console.error('Gagal menyalin: ', err);
    });
}

// Dynamic Filter Game (synchronized from header search)
const hiddenSearchInput = document.getElementById('search-input');
const gameGrid = document.getElementById('game-grid');
const gameCards = document.querySelectorAll('.game-card');
const noGameAlert = document.getElementById('no-game-alert');

if (hiddenSearchInput) {
    hiddenSearchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        let visibleCount = 0;

        gameCards.forEach(card => {
            const gameName = card.getAttribute('data-name');
            if (gameName.includes(query)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            gameGrid.style.display = 'none';
            noGameAlert.style.display = 'block';
        } else {
            gameGrid.style.display = 'grid';
            noGameAlert.style.display = 'none';
        }
    });
}
