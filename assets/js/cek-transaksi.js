// Input focus and border styling for cek-transaksi page
const inputTxId = document.getElementById('input-tx-id');
if (inputTxId) {
    inputTxId.addEventListener('focus', function() {
        this.style.borderColor = 'var(--primary-color)';
    });
    inputTxId.addEventListener('blur', function() {
        this.style.borderColor = 'var(--card-border)';
    });
}
