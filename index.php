<?php
require_once 'config/db.php';
require_once 'includes/header.php';

$games = [];

if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM games WHERE status = 'aktif' ORDER BY nama_game ASC");
        $games = $stmt->fetchAll();
    } catch (PDOException $e) {
        $db_connected = false;
    }
}

if (!$db_connected) {
    $games = [
        [
            'id' => 1,
            'nama_game' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'deskripsi' => 'Top up Diamond Mobile Legends termurah dan tercepat hanya dalam hitungan detik.',
            'status' => 'aktif'
        ],
        [
            'id' => 2,
            'nama_game' => 'Free Fire',
            'slug' => 'free-fire',
            'deskripsi' => 'Top up Diamond Free Fire untuk membeli elite pass dan bundle favoritmu.',
            'status' => 'aktif'
        ],
        [
            'id' => 3,
            'nama_game' => 'PUBG Mobile',
            'slug' => 'pubg-mobile',
            'deskripsi' => 'Top up UC PUBG Mobile termurah untuk skin keren dan Royale Pass.',
            'status' => 'aktif'
        ],
        [
            'id' => 4,
            'nama_game' => 'Genshin Impact',
            'slug' => 'genshin-impact',
            'deskripsi' => 'Top up Genesis Crystals Genshin Impact untuk gacha karakter impianmu.',
            'status' => 'aktif'
        ]
    ];
}
?>

<div style="background: linear-gradient(135deg, var(--primary-color) 0%, #312e81 100%); color: white; padding: 60px 20px; border-radius: var(--radius-lg); margin-top: 20px; position: relative; overflow: hidden; box-shadow: var(--shadow-lg);">
    <div style="position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; top: -100px; right: -50px; pointer-events: none;"></div>
    <div style="position: absolute; width: 150px; height: 150px; background: rgba(255,255,255,0.03); border-radius: 50%; bottom: -50px; left: 10%; pointer-events: none;"></div>
    
    <div style="max-width: 800px; margin: 0 auto; text-align: center; position: relative; z-index: 2;">
        <span class="badge" style="background-color: rgba(255,255,255,0.15); color: #fff; margin-bottom: 15px; font-size: 13px; font-weight: 700;">PROYEK TAHAP AWAL - FRONTEND</span>
        <h1 style="margin: 0; font-size: 42px; font-weight: 800; letter-spacing: -1px; line-height: 1.2;">
            Pusat Top Up Game Tercepat & Terpercaya
        </h1>
        <p style="font-size: 18px; opacity: 0.9; margin-top: 15px; margin-bottom: 30px; font-weight: 400; max-width: 600px; margin-left: auto; margin-right: auto;">
            Dapatkan diamond, voucher, dan mata uang game favorit Anda secara instan dengan harga terjangkau di <strong>FUNtopup</strong>.
        </p>

        <div style="max-width: 500px; margin: 0 auto; position: relative;">
            <input type="text" id="search-input" placeholder="Cari game favorit Anda... (misal: Legends)" 
                   style="width: 100%; padding: 15px 20px; font-size: 16px; border: none; border-radius: 30px; box-shadow: var(--shadow-md); font-family: inherit; color: #0f172a; outline: none;">
            <span style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 20px; pointer-events: none;">🔍</span>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 50px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="margin: 0; font-size: 26px; font-weight: 700; letter-spacing: -0.5px;">Daftar Game Populer</h2>
            <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 15px;">Pilih game untuk mulai melakukan pengisian saldo game Anda.</p>
        </div>
        <?php if (!$db_connected): ?>
            <span class="badge" style="background-color: rgba(239, 68, 68, 0.15); color: var(--danger-color); font-weight: 600;">
                🔌 Offline Mode (Mock Data)
            </span>
        <?php else: ?>
            <span class="badge" style="background-color: rgba(16, 185, 129, 0.15); color: var(--success-color); font-weight: 600;">
                🟢 Online Mode (Database Terkoneksi)
            </span>
        <?php endif; ?>
    </div>

    <div id="game-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
        <?php foreach ($games as $game): ?>
            <div class="game-card" data-name="<?php echo strtolower(htmlspecialchars($game['nama_game'])); ?>" 
                 style="background-color: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; transform: translateY(0); transition: all 0.3s ease;">
                
                <div>
                    <div class="card-banner" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); height: 130px; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: white; position: relative;">
                        <span style="opacity: 0.85; text-shadow: 0 2px 4px rgba(0,0,0,0.5);"><?php echo htmlspecialchars($game['nama_game']); ?></span>
                        
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40px; background: linear-gradient(to top, var(--card-bg), transparent);"></div>
                    </div>

                    <div style="padding: 20px;">
                        <h3 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 700; color: var(--text-color);"><?php echo htmlspecialchars($game['nama_game']); ?></h3>
                        <p style="margin: 0; font-size: 14px; color: var(--text-muted); line-height: 1.5; font-weight: 400; height: 60px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                            <?php echo htmlspecialchars($game['deskripsi'] ? $game['deskripsi'] : 'Top up instan voucher game ' . $game['nama_game'] . ' termurah dan aman.'); ?>
                        </p>
                    </div>
                </div>

                <div style="padding: 0 20px 20px 20px;">
                    <a href="user/topup/game.php?slug=<?php echo $game['slug']; ?>" class="btn btn-primary" style="width: 100%; text-align: center;">
                        Top Up Sekarang
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="no-game-alert" style="display: none; text-align: center; padding: 40px; background-color: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-lg); margin-top: 20px;">
        <span style="font-size: 40px;">🔍</span>
        <h3 style="margin-top: 15px; margin-bottom: 5px;">Game Tidak Ditemukan</h3>
        <p style="color: var(--text-muted); margin: 0;">Silakan periksa kata kunci Anda atau hubungi admin.</p>
    </div>
</div>

<style>
    .game-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-color);
    }
</style>

<script>
    const searchInput = document.getElementById('search-input');
    const gameGrid = document.getElementById('game-grid');
    const gameCards = document.querySelectorAll('.game-card');
    const noGameAlert = document.getElementById('no-game-alert');

    searchInput.addEventListener('input', function() {
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
</script>

<?php require_once 'includes/footer.php'; ?>
