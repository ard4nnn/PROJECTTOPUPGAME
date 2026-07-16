// Game Top Up Form Logic
let selectedProduct = null;
let selectedPayment = null;
let purchaseQty = 1;

const idInput = document.getElementById('id_game_user');           // hidden proxy
const visibleId = document.getElementById('visible_id_game_user'); // the visible input the user types into
const serverId = document.getElementById('server_game_user');      // optional server field
const productCards = document.querySelectorAll('.product-card');
const paymentCards = document.querySelectorAll('.payment-card');

const summaryId = document.getElementById('summary-id');
const summaryProduct = document.getElementById('summary-product');
const summaryPayment = document.getElementById('summary-payment');
const summaryTotal = document.getElementById('summary-total');
const btnSubmit = document.getElementById('btn-submit');

// ─── Helper: is account data filled? ─────────────────────────────
function isAccountFilled() {
    // Check the visible ID field (the one the user actually types into)
    if (visibleId) return visibleId.value.trim().length > 0;
    // Fallback to hidden input
    if (idInput) return idInput.value.trim().length > 0;
    return false;
}

// ─── Helper: calculate total with quantity ─────────────────────
function calcTotal() {
    if (!selectedProduct) return 0;
    return selectedProduct.price * purchaseQty;
}

function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID');
}

function updateSummaryTotal() {
    if (summaryTotal) {
        summaryTotal.textContent = formatRupiah(calcTotal());
    }
}

// ─── Toast Notification System ────────────────────────────────────
function showToast(message, type) {
    // type: 'error' | 'success' | 'warning'
    const existing = document.getElementById('topup-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'topup-toast';
    toast.className = 'topup-toast topup-toast--' + (type || 'error');

    const iconSvg = {
        error:   '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        success: '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        warning: '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>'
    };

    toast.innerHTML =
        '<span class="topup-toast-icon">' + (iconSvg[type] || iconSvg.error) + '</span>' +
        '<span class="topup-toast-msg">' + message + '</span>';

    document.body.appendChild(toast);

    // Animate in
    requestAnimationFrame(function() {
        toast.classList.add('topup-toast--visible');
    });

    // Auto-dismiss after 3.5s
    setTimeout(function() {
        toast.classList.remove('topup-toast--visible');
        toast.classList.add('topup-toast--hiding');
        setTimeout(function() { toast.remove(); }, 400);
    }, 3500);
}

// ─── Inject toast CSS ─────────────────────────────────────────────
(function injectToastStyles() {
    if (document.getElementById('topup-toast-styles')) return;
    var s = document.createElement('style');
    s.id = 'topup-toast-styles';
    s.textContent =
        '.topup-toast{position:fixed;bottom:30px;left:50%;transform:translateX(-50%) translateY(20px);opacity:0;z-index:9999;display:flex;align-items:center;gap:10px;padding:14px 22px;border-radius:10px;font-size:14px;font-weight:600;font-family:inherit;box-shadow:0 8px 30px rgba(0,0,0,.35);pointer-events:none;transition:opacity .35s ease,transform .35s cubic-bezier(.4,0,.2,1);max-width:90vw;white-space:nowrap}' +
        '.topup-toast--visible{opacity:1;transform:translateX(-50%) translateY(0)}' +
        '.topup-toast--hiding{opacity:0;transform:translateX(-50%) translateY(20px)}' +
        '.topup-toast--error{background:#1e1e1e;color:#f1f1f1;border:1px solid #333}' +
        '.topup-toast--error .topup-toast-icon{color:#ef4444}' +
        '.topup-toast--success{background:#1e1e1e;color:#f1f1f1;border:1px solid #333}' +
        '.topup-toast--success .topup-toast-icon{color:#22c55e}' +
        '.topup-toast--warning{background:#1e1e1e;color:#f1f1f1;border:1px solid #333}' +
        '.topup-toast--warning .topup-toast-icon{color:#f59e0b}' +
        '.topup-toast-icon{display:flex;align-items:center;flex-shrink:0}' +
        '.topup-toast-msg{line-height:1.3}';
    document.head.appendChild(s);
})();

// ─── ID Input listeners ──────────────────────────────────────────
if (idInput) {
    idInput.addEventListener('input', function() {
        var idVal = this.value.trim();
        summaryId.textContent = idVal ? idVal : '-';
        validateForm();
    });
}
if (visibleId) {
    visibleId.addEventListener('focus', function() {
        this.style.borderColor = 'var(--primary-color)';
    });
    visibleId.addEventListener('blur', function() {
        this.style.borderColor = 'var(--card-border)';
    });
}

// ─── Product Card Click ──────────────────────────────────────────
productCards.forEach(function(card) {
    card.addEventListener('click', function() {
        // Block selection if account data is empty
        if (!isAccountFilled()) {
            showToast('Silahkan isi data akun terlebih dahulu.', 'error');
            if (visibleId) {
                visibleId.style.borderColor = '#ef4444';
                visibleId.focus();
                setTimeout(function() { visibleId.style.borderColor = 'var(--card-border)'; }, 2000);
            }
            return;
        }

        productCards.forEach(function(c) { c.classList.remove('selected'); });
        card.classList.add('selected');

        selectedProduct = {
            id:    card.getAttribute('data-id'),
            name:  card.getAttribute('data-name'),
            price: parseFloat(card.getAttribute('data-price'))
        };

        summaryProduct.textContent = selectedProduct.name + (purchaseQty > 1 ? ' x' + purchaseQty : '');
        updateSummaryTotal();
        validateForm();

        // Auto-scroll ke card Verifikasi Pembelian
        var verifyCard = document.getElementById('verification-card');
        if (verifyCard) {
            setTimeout(function() {
                verifyCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 150);
        }
    });
});

// ─── Payment Card Click ──────────────────────────────────────────
paymentCards.forEach(function(card) {
    card.addEventListener('click', function() {
        // Block selection if account data is empty
        if (!isAccountFilled()) {
            showToast('Silahkan isi data akun terlebih dahulu.', 'error');
            if (visibleId) {
                visibleId.style.borderColor = '#ef4444';
                visibleId.focus();
                setTimeout(function() { visibleId.style.borderColor = 'var(--card-border)'; }, 2000);
            }
            return;
        }

        paymentCards.forEach(function(c) { c.classList.remove('selected'); });
        card.classList.add('selected');

        selectedPayment = {
            id:   card.getAttribute('data-id'),
            name: card.getAttribute('data-name')
        };

        summaryPayment.textContent = selectedPayment.name;
        validateForm();
    });
});

// ─── Quantity Input Control ──────────────────────────────────────
var qtyInput  = document.getElementById('qty-input');
var qtyPlus   = document.getElementById('qty-plus');
var qtyMinus  = document.getElementById('qty-minus');

function applyQty(newQty) {
    newQty = parseInt(newQty, 10);
    if (isNaN(newQty) || newQty < 1) newQty = 1;
    if (newQty > 99) newQty = 99;
    purchaseQty = newQty;
    if (qtyInput) qtyInput.value = purchaseQty;

    // Update minus button visual state
    if (qtyMinus) {
        qtyMinus.style.opacity = purchaseQty <= 1 ? '0.4' : '1';
        qtyMinus.style.cursor  = purchaseQty <= 1 ? 'not-allowed' : 'pointer';
    }

    // Reflect in summary
    if (selectedProduct) {
        summaryProduct.textContent = selectedProduct.name + (purchaseQty > 1 ? ' x' + purchaseQty : '');
        updateSummaryTotal();
    }
}

if (qtyPlus) {
    qtyPlus.addEventListener('click', function() {
        applyQty(purchaseQty + 1);
    });
}
if (qtyMinus) {
    qtyMinus.addEventListener('click', function() {
        applyQty(purchaseQty - 1);
    });
}
if (qtyInput) {
    qtyInput.addEventListener('input', function() {
        applyQty(this.value);
    });
    qtyInput.addEventListener('blur', function() {
        applyQty(this.value);
    });
}

// Initial state
applyQty(1);

// ─── Form Validation ─────────────────────────────────────────────
function validateForm() {
    if (isAccountFilled() && selectedProduct && selectedPayment) {
        btnSubmit.removeAttribute('disabled');
    } else {
        btnSubmit.setAttribute('disabled', 'true');
    }
}

// ─── Checkout Modal ──────────────────────────────────────────────
var checkoutModal = document.getElementById('checkout-modal');
var modalClose    = document.getElementById('modal-close');

var modalInvoice = document.getElementById('modal-invoice-id');
var modalGame    = document.getElementById('modal-game');
var modalId      = document.getElementById('modal-id');
var modalProduct = document.getElementById('modal-product');
var modalPayment = document.getElementById('modal-payment');
var modalTotal   = document.getElementById('modal-total');

if (btnSubmit) {
    btnSubmit.addEventListener('click', function() {
        var productLabel = selectedProduct.name + (purchaseQty > 1 ? ' x' + purchaseQty : '');
        var totalPrice   = calcTotal();

        // Cek apakah config backend tersedia
        if (window._topupConfig && window._topupConfig.processUrl) {
            btnSubmit.setAttribute('disabled', 'true');
            btnSubmit.textContent = window.currentLang === 'id' ? 'Memproses...' : 'Processing...';

            var formData = new FormData();
            formData.append('produk_id', selectedProduct.id);
            formData.append('id_game_user', idInput ? idInput.value.trim() : (visibleId ? visibleId.value.trim() : ''));
            formData.append('metode_bayar_id', selectedPayment.id);
            formData.append('qty', purchaseQty);

            fetch(window._topupConfig.processUrl, {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.json().then(function(data) {
                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan server.');
                    }
                    return data;
                });
            })
            .then(function(data) {
                btnSubmit.removeAttribute('disabled');
                btnSubmit.textContent = window.currentLang === 'id' ? 'Konfirmasi & Beli Sekarang' : 'Confirm & Buy Now';

                if (data.success) {
                    // Checkout sukses ke DB
                    modalGame.textContent    = document.getElementById('summary-game').textContent;
                    modalId.textContent      = idInput ? idInput.value.trim() : (visibleId ? visibleId.value.trim() : '-');
                    modalProduct.textContent = productLabel;
                    modalPayment.textContent = selectedPayment.name;
                    modalTotal.textContent   = formatRupiah(data.nominal_transfer || totalPrice);
                    
                    if (modalInvoice) {
                        modalInvoice.textContent = '#' + data.invoice_id;
                    }
                    checkoutModal.style.display = 'flex';
                } else {
                    showToast(data.message || 'Gagal memproses transaksi.', 'error');
                }
            })
            .catch(function(error) {
                btnSubmit.removeAttribute('disabled');
                btnSubmit.textContent = window.currentLang === 'id' ? 'Konfirmasi & Beli Sekarang' : 'Confirm & Buy Now';
                showToast(error.message || (window.currentLang === 'id' ? 'Gagal menghubungi server.' : 'Failed to connect to server.'), 'error');
            });
        } else {
            showToast(window.currentLang === 'id' ? 'Layanan sedang mengalami gangguan, silakan coba lagi.' : 'Service is temporarily unavailable, please try again.', 'error');
        }
    });
}

if (modalClose) {
    modalClose.addEventListener('click', function() {
        checkoutModal.style.display = 'none';
        if (visibleId) visibleId.value = '';
        if (serverId) serverId.value = '';
        if (idInput) idInput.value = '';
        productCards.forEach(function(c) { c.classList.remove('selected'); });
        paymentCards.forEach(function(c) { c.classList.remove('selected'); });
        selectedProduct = null;
        selectedPayment = null;
        purchaseQty = 1;
        applyQty(1);
        summaryId.textContent      = '-';
        summaryProduct.textContent = '-';
        summaryPayment.textContent = '-';
        summaryTotal.textContent   = 'Rp 0';
        if (modalInvoice) modalInvoice.textContent = '-';
        btnSubmit.setAttribute('disabled', 'true');
    });
}
