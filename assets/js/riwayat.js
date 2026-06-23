// Riwayat (Transaction History) Demo Mode Logic
var isDbConnected = window.isDbConnected || false;

if (!isDbConnected) {
    const orderHistory = JSON.parse(localStorage.getItem('order_history')) || [];
    const tbody = document.getElementById('transaction-tbody');
    const noHistoryRow = document.getElementById('no-history-row');
    const clearDemoContainer = document.getElementById('clear-demo-container');

    if (orderHistory.length > 0) {
        if (noHistoryRow) noHistoryRow.remove();
        if (clearDemoContainer) clearDemoContainer.style.display = 'block';

        orderHistory.forEach(order => {
            const tr = document.createElement('tr');
            tr.style.borderBottom = '1px solid var(--card-border)';

            tr.innerHTML = `
                <td class="col-invoice">#${order.id} (Demo)</td>
                <td class="col-date">${order.date}</td>
                <td class="col-game">${order.game}</td>
                <td class="col-product">${order.product}</td>
                <td><code>${order.targetId}</code></td>
                <td class="col-payment">${order.payment}</td>
                <td class="col-price">Rp ${order.price.toLocaleString('id-ID')}</td>
                <td class="col-status">
                    <span class="badge badge-${order.status}">${order.status.toUpperCase()}</span>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    const btnClearDemo = document.getElementById('btn-clear-demo');
    if (btnClearDemo) {
        btnClearDemo.addEventListener('click', function() {
            const confirmMsg = window.currentLang === 'id'
                ? 'Apakah Anda yakin ingin menghapus semua riwayat transaksi demo ini?'
                : 'Are you sure you want to clear all demo transaction history?';

            if (confirm(confirmMsg)) {
                localStorage.removeItem('order_history');
                window.location.reload();
            }
        });
    }
}
