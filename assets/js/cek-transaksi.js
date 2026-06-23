// If not connected to database or transaction not found in DB, check local storage (Demo Mode)
if (window.searchId && !window.isDbTx) {
    const orderHistory = JSON.parse(localStorage.getItem('order_history')) || [];
    // Match either raw ID or with hashtag
    const cleanSearch = window.searchId.replace('#', '').trim();
    const matched = orderHistory.find(order => String(order.id) === cleanSearch);

    const resultBox = document.getElementById('tx-result-box');
    const errorBox = document.getElementById('tx-error-box');

    if (matched) {
        if (resultBox && errorBox) {
            resultBox.style.display = 'block';
            errorBox.style.display = 'none';
        }

        const resInvoice = document.getElementById('res-invoice');
        const resGame = document.getElementById('res-game');
        const resProduct = document.getElementById('res-product');
        const resTarget = document.getElementById('res-target');
        const resPayment = document.getElementById('res-payment');
        const resStatus = document.getElementById('res-status');
        const resTotal = document.getElementById('res-total');

        if (resInvoice) resInvoice.textContent = '#' + matched.id + ' (Demo)';
        if (resGame) resGame.textContent = matched.game;
        if (resProduct) resProduct.textContent = matched.product;
        if (resTarget) resTarget.textContent = matched.targetId;
        if (resPayment) resPayment.textContent = matched.payment;
        
        if (resStatus) {
            resStatus.className = 'badge badge-' + matched.status;
            resStatus.textContent = matched.status.toUpperCase();
        }

        if (resTotal) resTotal.textContent = 'Rp ' + matched.price.toLocaleString('id-ID');
    } else {
        if (resultBox && errorBox) {
            resultBox.style.display = 'none';
            errorBox.style.display = 'block';
            errorBox.textContent = window.currentLang === 'id' ? '⚠️ Transaksi tidak ditemukan di riwayat demo!' : '⚠️ Transaction not found in demo history!';
        }
    }
}

const inputTxId = document.getElementById('input-tx-id');
if (inputTxId) {
    inputTxId.addEventListener('focus', function() {
        this.style.borderColor = 'var(--primary-color)';
    });
    inputTxId.addEventListener('blur', function() {
        this.style.borderColor = 'var(--card-border)';
    });
}
