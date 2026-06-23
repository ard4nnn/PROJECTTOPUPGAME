// Slider Logic
const track = document.getElementById('carousel-track');
const prevBtn = document.getElementById('carousel-prev');
const nextBtn = document.getElementById('carousel-next');
const dots = document.querySelectorAll('.dot');

let currentSlide = 0;
const totalSlides = 2;
let autoSlideInterval;

function setSlide(index) {
    currentSlide = index;
    if (currentSlide < 0) currentSlide = totalSlides - 1;
    if (currentSlide >= totalSlides) currentSlide = 0;

    if (track) {
        track.style.transform = `translateX(-${currentSlide * 50}%)`;
    }

    dots.forEach((dot, idx) => {
        if (idx === currentSlide) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
    resetAutoSlide();
}

if (prevBtn && nextBtn) {
    prevBtn.addEventListener('click', () => {
        setSlide(currentSlide - 1);
    });

    nextBtn.addEventListener('click', () => {
        setSlide(currentSlide + 1);
    });
}

function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        setSlide(currentSlide + 1);
    }, 5000);
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
}

if (track) {
    startAutoSlide();
}
