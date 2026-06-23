// Game Top Up Form Logic
let selectedProduct = null;
let selectedPayment = null;

const idInput = document.getElementById('id_game_user');
const productCards = document.querySelectorAll('.product-card');
const paymentCards = document.querySelectorAll('.payment-card');

const summaryId = document.getElementById('summary-id');
const summaryProduct = document.getElementById('summary-product');
const summaryPayment = document.getElementById('summary-payment');
const summaryTotal = document.getElementById('summary-total');
const btnSubmit = document.getElementById('btn-submit');

if (idInput) {
    idInput.addEventListener('input', function() {
        const idVal = this.value.trim();
        summaryId.textContent = idVal ? idVal : '-';
        validateForm();
    });
    idInput.addEventListener('focus', function() {
        this.style.borderColor = 'var(--primary-color)';
    });
    idInput.addEventListener('blur', function() {
        this.style.borderColor = 'var(--card-border)';
    });
}

productCards.forEach(card => {
    card.addEventListener('click', function() {
        productCards.forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');

        selectedProduct = {
            id: this.getAttribute('data-id'),
            name: this.getAttribute('data-name'),
            price: parseFloat(this.getAttribute('data-price'))
        };

        summaryProduct.textContent = selectedProduct.name;
        summaryTotal.textContent = 'Rp ' + selectedProduct.price.toLocaleString('id-ID');
        validateForm();
    });
});

paymentCards.forEach(card => {
    card.addEventListener('click', function() {
        paymentCards.forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');

        selectedPayment = {
            id: this.getAttribute('data-id'),
            name: this.getAttribute('data-name')
        };

        summaryPayment.textContent = selectedPayment.name;
        validateForm();
    });
});

function validateForm() {
    const idVal = idInput ? idInput.value.trim() : '';
    if (idVal && selectedProduct && selectedPayment) {
        btnSubmit.removeAttribute('disabled');
    } else {
        btnSubmit.setAttribute('disabled', 'true');
    }
}

const checkoutModal = document.getElementById('checkout-modal');
const modalClose = document.getElementById('modal-close');

const modalGame = document.getElementById('modal-game');
const modalId = document.getElementById('modal-id');
const modalProduct = document.getElementById('modal-product');
const modalPayment = document.getElementById('modal-payment');
const modalTotal = document.getElementById('modal-total');

if (btnSubmit) {
    btnSubmit.addEventListener('click', function() {
        modalGame.textContent = document.getElementById('summary-game').textContent;
        modalId.textContent = idInput.value.trim();
        modalProduct.textContent = selectedProduct.name;
        modalPayment.textContent = selectedPayment.name;
        modalTotal.textContent = 'Rp ' + selectedProduct.price.toLocaleString('id-ID');

        const newOrder = {
            id: Math.floor(Math.random() * 9000) + 1000,
            date: new Date().toLocaleString('id-ID'),
            game: modalGame.textContent,
            product: selectedProduct.name,
            targetId: idInput.value.trim(),
            payment: selectedPayment.name,
            price: selectedProduct.price,
            status: 'pending'
        };

        let orderHistory = JSON.parse(localStorage.getItem('order_history')) || [];
        orderHistory.unshift(newOrder);
        localStorage.setItem('order_history', JSON.stringify(orderHistory));
        checkoutModal.style.display = 'flex';
    });
}

if (modalClose) {
    modalClose.addEventListener('click', function() {
        checkoutModal.style.display = 'none';
        if (idInput) idInput.value = '';
        productCards.forEach(c => c.classList.remove('selected'));
        paymentCards.forEach(c => c.classList.remove('selected'));
        selectedProduct = null;
        selectedPayment = null;
        summaryId.textContent = '-';
        summaryProduct.textContent = '-';
        summaryPayment.textContent = '-';
        summaryTotal.textContent = 'Rp 0';
        btnSubmit.setAttribute('disabled', 'true');
    });
}
