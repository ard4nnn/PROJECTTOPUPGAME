<?php
require_once '../../config/db.php';
require_once '../../includes/header.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$error = '';
$game = null;
$produk_list = [];
$metode_list = [];

$mock_games = [
    'mobile-legends' => [
        'id' => 1,
        'nama_game' => 'Mobile Legends',
        'deskripsi' => 'Top up Diamond Mobile Legends termurah dan tercepat hanya dalam hitungan detik.',
        'produk' => [
            ['id' => 1, 'nama_produk' => '86 Diamonds', 'harga' => 20000],
            ['id' => 2, 'nama_produk' => '172 Diamonds', 'harga' => 40000],
            ['id' => 3, 'nama_produk' => '257 Diamonds', 'harga' => 60000],
            ['id' => 4, 'nama_produk' => '706 Diamonds', 'harga' => 150000]
        ]
    ],
    'free-fire' => [
        'id' => 2,
        'nama_game' => 'Free Fire',
        'deskripsi' => 'Top up Diamond Free Fire untuk membeli elite pass dan bundle favoritmu.',
        'produk' => [
            ['id' => 5, 'nama_produk' => '70 Diamonds', 'harga' => 10000],
            ['id' => 6, 'nama_produk' => '140 Diamonds', 'harga' => 20000],
            ['id' => 7, 'nama_produk' => '355 Diamonds', 'harga' => 50000]
        ]
    ],
    'pubg-mobile' => [
        'id' => 3,
        'nama_game' => 'PUBG Mobile',
        'deskripsi' => 'Top up UC PUBG Mobile termurah untuk skin keren dan Royale Pass.',
        'produk' => [
            ['id' => 8, 'nama_produk' => '60 UC', 'harga' => 15000],
            ['id' => 9, 'nama_produk' => '325 UC', 'harga' => 75000]
        ]
    ],
    'genshin-impact' => [
        'id' => 4,
        'nama_game' => 'Genshin Impact',
        'deskripsi' => 'Top up Genesis Crystals Genshin Impact untuk gacha karakter impianmu.',
        'produk' => [
            ['id' => 10, 'nama_produk' => '60 Genesis Crystals', 'harga' => 16000],
            ['id' => 11, 'nama_produk' => '300 Genesis Crystals', 'harga' => 79000]
        ]
    ]
];

$mock_payments = [
    ['id' => 1, 'nama' => 'DANA', 'kode' => 'DANA'],
    ['id' => 2, 'nama' => 'GoPay', 'kode' => 'GOPAY'],
    ['id' => 3, 'nama' => 'OVO', 'kode' => 'OVO'],
    ['id' => 4, 'nama' => 'Transfer Bank BCA', 'kode' => 'BCA']
];

if (empty($slug)) {
    $slug = 'mobile-legends';
}

if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM games WHERE slug = ? AND status = 'aktif'");
        $stmt->execute([$slug]);
        $game = $stmt->fetch();

        if ($game) {
            $stmtProd = $pdo->prepare("SELECT * FROM produk WHERE game_id = ? AND status = 'aktif' ORDER BY harga ASC");
            $stmtProd->execute([$game['id']]);
            $produk_list = $stmtProd->fetchAll();

            $stmtMetode = $pdo->query("SELECT * FROM metode_bayar WHERE status = 'aktif' ORDER BY nama ASC");
            $metode_list = $stmtMetode->fetchAll();
        }
    } catch (PDOException $e) {
        $db_connected = false;
    }
}

if (!$db_connected || !$game) {
    if (array_key_exists($slug, $mock_games)) {
        $game = [
            'id' => $mock_games[$slug]['id'],
            'nama_game' => $mock_games[$slug]['nama_game'],
            'slug' => $slug,
            'deskripsi' => $mock_games[$slug]['deskripsi']
        ];
        $produk_list = $mock_games[$slug]['produk'];
        $metode_list = $mock_payments;
    } else {
        $error = 'Game tidak ditemukan!';
    }
}
?>

<div class="container" style="margin-top: 30px;">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="border-radius: var(--radius-md);"><?php echo htmlspecialchars($error); ?></div>
        <a href="../../index.php" class="btn btn-primary">&larr; Kembali ke Beranda</a>
    <?php else: ?>
        
        <div style="margin-bottom: 20px;">
            <a href="../../index.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                &larr; Kembali ke Beranda
            </a>
        </div>

        <div style="display: flex; gap: 30px; flex-wrap: wrap; align-items: flex-start;">
            
            <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 20px;">
                
                <div class="card" style="background: linear-gradient(135deg, var(--card-bg) 0%, var(--bg-color) 100%);">
                    <div style="background-color: var(--primary-color); color: white; display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; margin-bottom: 15px;">
                        GAME VOUCHER
                    </div>
                    <h2 style="margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;"><?php echo htmlspecialchars($game['nama_game']); ?></h2>
                    <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6; margin-top: 10px; margin-bottom: 0;">
                        <?php echo htmlspecialchars($game['deskripsi'] ? $game['deskripsi'] : 'Top up instan termurah.'); ?>
                    </p>
                </div>

                <div class="card">
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <span style="background-color: var(--primary-color); color: white; border-radius: 50%; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px;">1</span>
                        Lengkapi Data Akun
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label for="id_game_user" style="font-weight: 600; font-size: 14px;">User ID Game</label>
                        <input type="text" id="id_game_user" placeholder="Masukkan ID Game Anda (contoh: 1284759)" 
                               style="width: 100%; padding: 12px 15px; border-radius: var(--radius-md); border: 2px solid var(--card-border); background-color: var(--bg-color); color: var(--text-color); font-family: inherit; font-size: 15px; outline: none;">
                        <small style="color: var(--text-muted);">Masukkan ID Game Anda dengan benar. Kami tidak bertanggung jawab atas kesalahan input ID.</small>
                    </div>
                </div>

                <div class="card">
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <span style="background-color: var(--primary-color); color: white; border-radius: 50%; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px;">3</span>
                        Metode Pembayaran
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;" id="payment-options">
                        <?php foreach ($metode_list as $metode): ?>
                            <div class="payment-card" data-id="<?php echo $metode['id']; ?>" data-name="<?php echo htmlspecialchars($metode['nama']); ?>"
                                 style="border: 2px solid var(--card-border); border-radius: var(--radius-md); padding: 15px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s ease;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="radio-indicator" style="width: 18px; height: 18px; border: 2px solid var(--text-muted); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 10px; height: 10px; border-radius: 50%; background-color: transparent;"></div>
                                    </div>
                                    <strong style="font-size: 15px;"><?php echo htmlspecialchars($metode['nama']); ?></strong>
                                </div>
                                <span class="badge" style="background-color: var(--card-border); color: var(--text-muted);"><?php echo htmlspecialchars($metode['kode']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <div style="flex: 1.5; min-width: 320px; display: flex; flex-direction: column; gap: 20px;">
                
                <div class="card">
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <span style="background-color: var(--primary-color); color: white; border-radius: 50%; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px;">2</span>
                        Pilih Nominal Pembelian
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 15px;" id="product-options">
                        <?php foreach ($produk_list as $prod): ?>
                            <div class="product-card" data-id="<?php echo $prod['id']; ?>" data-name="<?php echo htmlspecialchars($prod['nama_produk']); ?>" data-price="<?php echo $prod['harga']; ?>"
                                 style="border: 2px solid var(--card-border); border-radius: var(--radius-md); padding: 20px; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background-color: var(--card-bg); transition: all 0.2s ease; position: relative;">
                                <span style="font-size: 15px; font-weight: 700; margin-bottom: 8px;"><?php echo htmlspecialchars($prod['nama_produk']); ?></span>
                                <span style="color: var(--primary-color); font-weight: 800; font-size: 15px;">Rp <?php echo number_format($prod['harga'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card" style="background: linear-gradient(135deg, var(--card-bg) 0%, var(--bg-color) 100%);">
                    <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 18px; font-weight: 700;">4. Verifikasi Pembelian</h3>
                    <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">Lengkapi ID Game, nominal top up, dan metode pembayaran di sebelah kiri untuk melihat ringkasan pembayaran.</p>
                    
                    <div style="border-top: 1px solid var(--card-border); padding-top: 15px; margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; font-size: 15px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Game:</span>
                            <strong id="summary-game"><?php echo htmlspecialchars($game['nama_game']); ?></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Target ID:</span>
                            <strong id="summary-id">-</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Produk:</span>
                            <strong id="summary-product">-</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Metode Pembayaran:</span>
                            <strong id="summary-payment">-</strong>
                        </div>
                        <div style="border-top: 2px dashed var(--card-border); padding-top: 10px; margin-top: 5px; display: flex; justify-content: space-between; font-size: 18px;">
                            <strong style="color: var(--text-color);">Total Pembayaran:</strong>
                            <strong id="summary-total" style="color: var(--primary-color);">Rp 0</strong>
                        </div>
                    </div>

                    <button class="btn btn-primary" id="btn-submit" style="width: 100%; padding: 15px; font-size: 16px; border-radius: var(--radius-md);" disabled>
                        Konfirmasi & Beli Sekarang
                    </button>
                </div>

            </div>

        </div>

    <?php endif; ?>
</div>

<div id="checkout-modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div style="background-color: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-lg); padding: 30px; max-width: 480px; width: 90%; text-align: center; box-shadow: var(--shadow-lg); position: relative; animation: modalSlide 0.3s ease;">
        <span style="font-size: 50px;">🎉</span>
        <h3 style="margin-top: 15px; font-size: 22px; font-weight: 700;">Pemesanan Berhasil Dikirim!</h3>
        <p style="color: var(--text-muted); font-size: 14px; line-height: 1.5; margin-bottom: 20px;">
            Pesanan top up Anda berhasil dibuat dengan status <strong class="badge badge-pending">PENDING</strong>.
        </p>

        <div style="background-color: var(--bg-color); border: 1px solid var(--card-border); padding: 15px; border-radius: var(--radius-md); text-align: left; font-size: 14px; display: flex; flex-direction: column; gap: 8px; margin-bottom: 25px;">
            <div><strong>Detail Transaksi:</strong></div>
            <div style="display: flex; justify-content: space-between;"><span>Game:</span><span id="modal-game">-</span></div>
            <div style="display: flex; justify-content: space-between;"><span>User ID:</span><span id="modal-id">-</span></div>
            <div style="display: flex; justify-content: space-between;"><span>Produk:</span><span id="modal-product">-</span></div>
            <div style="display: flex; justify-content: space-between;"><span>Metode:</span><span id="modal-payment">-</span></div>
            <div style="display: flex; justify-content: space-between; border-top: 1px dashed var(--card-border); padding-top: 5px; font-weight: bold;">
                <span>Total Tagihan:</span><span id="modal-total" style="color: var(--primary-color);">-</span>
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button class="btn btn-outline" id="modal-close" style="flex: 1;">Tutup</button>
            <a href="../riwayat.php" class="btn btn-primary" style="flex: 1;">Lihat Riwayat</a>
        </div>
    </div>
</div>

<style>
    .product-card:hover, .payment-card:hover {
        border-color: var(--primary-color) !important;
        transform: translateY(-2px);
    }
    
    .product-card.selected, .payment-card.selected {
        border-color: var(--primary-color) !important;
        background-color: rgba(99, 102, 241, 0.05) !important;
    }

    .payment-card.selected .radio-indicator {
        border-color: var(--primary-color) !important;
    }
    .payment-card.selected .radio-indicator div {
        background-color: var(--primary-color) !important;
    }

    @keyframes modalSlide {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<script>
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

    idInput.addEventListener('input', function() {
        const idVal = this.value.trim();
        summaryId.textContent = idVal ? idVal : '-';
        validateForm();
    });

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
        const idVal = idInput.value.trim();
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

    modalClose.addEventListener('click', function() {
        checkoutModal.style.display = 'none';
        idInput.value = '';
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
</script>

<?php require_once '../../includes/footer.php'; ?>
