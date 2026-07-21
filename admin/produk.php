<?php
require_once __DIR__ . '/includes/admin-guard.php';
require_once __DIR__ . '/../config/db.php';

$success_msg = '';
$error_msg = '';

$edit_produk = null;
$is_edit = false;

// Load product for editing
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($db_connected && $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_produk = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($edit_produk) {
            $is_edit = true;
        }
    }
}

// Handle Form Submissions (Add, Edit, Toggle Status)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!csrf_verify()) {
        $error_msg = 'Token CSRF tidak valid. Silakan coba lagi.';
    } else {
        $action = $_POST['action'];

        if ($action === 'save_produk') {
            $game_id     = (int)($_POST['game_id'] ?? 0);
            $nama_produk = trim($_POST['nama_produk'] ?? '');
            $jumlah      = trim($_POST['jumlah'] ?? '');
            $harga       = trim($_POST['harga'] ?? '');
            $status      = trim($_POST['status'] ?? 'aktif');
            $produk_id   = isset($_POST['produk_id']) ? (int)$_POST['produk_id'] : 0;

            // Validasi input
            if ($game_id <= 0 || empty($nama_produk) || empty($jumlah) || $harga === '') {
                $error_msg = 'Semua field wajib diisi!';
            } elseif (!is_numeric($harga) || (float)$harga <= 0) {
                $error_msg = 'Harga harus berupa angka dan bernilai positif (> 0)!';
            } elseif (!in_array($status, ['aktif', 'nonaktif'])) {
                $error_msg = 'Status tidak valid.';
            } else {
                if ($db_connected && $pdo) {
                    try {
                        // Check if game exists
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM games WHERE id = ?");
                        $stmt->execute([$game_id]);
                        if ($stmt->fetchColumn() == 0) {
                            $error_msg = "Game tidak valid!";
                        } else {
                            if ($produk_id > 0) {
                                // Update
                                $stmt = $pdo->prepare("UPDATE produk SET game_id = ?, nama_produk = ?, jumlah = ?, harga = ?, status = ? WHERE id = ?");
                                $stmt->execute([$game_id, $nama_produk, $jumlah, $harga, $status, $produk_id]);
                                $success_msg = "Berhasil memperbarui produk: {$nama_produk}";
                                $is_edit = false;
                                $edit_produk = null;
                            } else {
                                // Insert
                                $stmt = $pdo->prepare("INSERT INTO produk (game_id, nama_produk, jumlah, harga, status) VALUES (?, ?, ?, ?, ?)");
                                $stmt->execute([$game_id, $nama_produk, $jumlah, $harga, $status]);
                                $success_msg = "Berhasil menambahkan produk baru: {$nama_produk}";
                            }
                        }
                    } catch (PDOException $e) {
                        $error_msg = "Terjadi kesalahan database: " . $e->getMessage();
                    }
                }
            }
        } elseif ($action === 'toggle_status') {
            $produk_id = (int)$_POST['produk_id'];
            if ($db_connected && $pdo) {
                try {
                    $stmt = $pdo->prepare("SELECT status, nama_produk FROM produk WHERE id = ?");
                    $stmt->execute([$produk_id]);
                    $produk = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($produk) {
                        $new_status = $produk['status'] === 'aktif' ? 'nonaktif' : 'aktif';
                        $stmt = $pdo->prepare("UPDATE produk SET status = ? WHERE id = ?");
                        $stmt->execute([$new_status, $produk_id]);
                        $success_msg = "Status produk '{$produk['nama_produk']}' berhasil diubah menjadi: " . strtoupper($new_status);
                    }
                } catch (PDOException $e) {
                    $error_msg = "Gagal mengubah status produk: " . $e->getMessage();
                }
            }
        }
    }
}

// Game Filter
$f_game = isset($_GET['game_filter']) ? (int)$_GET['game_filter'] : 0;

// Fetch all games & products
$games_list = [];
$products_list = [];

if ($db_connected && $pdo) {
    try {
        $games_list = $pdo->query("SELECT * FROM games ORDER BY nama_game ASC")->fetchAll(PDO::FETCH_ASSOC);

        $where = '';
        $params = [];
        if ($f_game > 0) {
            $where = "WHERE p.game_id = ?";
            $params = [$f_game];
        }

        $stmt = $pdo->prepare("
            SELECT p.*, g.nama_game
            FROM produk p
            LEFT JOIN games g ON p.game_id = g.id
            $where
            ORDER BY g.nama_game ASC, p.harga ASC
        ");
        $stmt->execute($params);
        $products_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // fail silently
    }
}

function rupiah($n) {
    return 'Rp ' . number_format($n, 0, ',', '.');
}

require_once __DIR__ . '/../includes/header.php';
?>
<!-- Link dashboard styling -->
<link rel="stylesheet" href="../user/dashboard/dashboard.css">

<div class="ft-wrapper">
  <?php include __DIR__ . '/includes/admin-sidebar.php'; ?>
  <main class="ft-main">

    <div class="ft-page-header">
      <h1 class="ft-page-title">Kelola Produk</h1>
      <p class="ft-page-sub">
          Atur harga, jumlah item, dan game untuk setiap produk FUNtopup.
      </p>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($success_msg)): ?>
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg text-xs mb-4">
            ✅ <?= htmlspecialchars($success_msg) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error_msg)): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-xs mb-4">
            ⚠️ <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <!-- Two Column Layout: Table (with filter) on left, Form on right -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
      
      <!-- Left Column: Products List & Filter -->
      <div>
        <!-- Filter Card -->
        <div class="ft-filter-card" style="padding:0.75rem 1rem; margin-bottom:1rem;">
          <form method="GET" action="">
            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
              <div style="flex:1; min-width:200px;">
                <select name="game_filter" class="ft-select" onchange="this.form.submit()">
                  <option value="0">Semua Game (Tampilkan Semua)</option>
                  <?php foreach ($games_list as $g): ?>
                    <option value="<?= $g['id'] ?>" <?= $f_game === (int)$g['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($g['nama_game']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <button type="submit" class="ft-btn ft-btn-primary" style="padding: 0.5rem 1rem;">Filter</button>
              <?php if ($f_game > 0): ?>
                <a href="produk.php" class="ft-btn ft-btn-secondary" style="padding: 0.5rem 1rem;">Clear</a>
              <?php endif; ?>
            </div>
          </form>
        </div>

        <div class="ft-table-wrap">
          <table class="ft-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Game</th>
                <th>Nama Produk</th>
                <th>Jumlah / Detail</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($products_list)): ?>
                <?php foreach ($products_list as $p): ?>
                  <tr>
                    <td><code>#<?= $p['id'] ?></code></td>
                    <td><strong style="color:#FBBF24;"><?= htmlspecialchars($p['nama_game'] ?? '-') ?></strong></td>
                    <td><strong><?= htmlspecialchars($p['nama_produk']) ?></strong></td>
                    <td><code><?= htmlspecialchars($p['jumlah']) ?></code></td>
                    <td><?= rupiah($p['harga']) ?></td>
                    <td>
                      <?php if ($p['status'] === 'aktif'): ?>
                        <span class="ft-badge success">Aktif</span>
                      <?php else: ?>
                        <span class="ft-badge failed">Nonaktif</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div style="display:inline-flex; gap:5px; margin:0;">
                        <a href="?game_filter=<?= $f_game ?>&edit=<?= $p['id'] ?>" class="ft-btn ft-btn-secondary" style="padding: 3px 8px; font-size:11px;">Edit</a>
                        
                        <!-- Toggle Status Form -->
                        <form method="POST" action="" style="display:inline; margin:0;">
                          <?= csrf_field(); ?>
                          <input type="hidden" name="action" value="toggle_status">
                          <input type="hidden" name="produk_id" value="<?= $p['id'] ?>">
                          <button type="submit" class="ft-btn <?= $p['status'] === 'aktif' ? 'ft-btn-secondary' : 'ft-btn-primary' ?>" style="padding: 3px 8px; font-size:11px; font-weight:normal;" onclick="return confirm('Apakah Anda yakin ingin <?= $p['status'] === 'aktif' ? 'menonaktifkan' : 'mengaktifkan' ?> produk ini?')">
                            <?= $p['status'] === 'aktif' ? 'Nonaktif' : 'Aktifkan' ?>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7">
                    <div class="ft-empty">
                      <div class="ft-empty-icon">📦</div>
                      <p class="ft-empty-title">Data produk kosong!</p>
                      <p class="ft-empty-sub">Silakan tambahkan produk baru di form kanan.</p>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Right Column: Add/Edit Form -->
      <div class="ft-card" style="margin-bottom:0;">
        <h2 class="ft-section-title" style="margin-top:0; font-size:1.1rem; border-bottom:1px solid #222240; padding-bottom:8px;">
          <?= $is_edit ? 'Edit Produk' : 'Tambah Produk Baru' ?>
        </h2>
        
        <form method="POST" action="" style="margin-top: 1rem;" class="space-y-4">
          <?= csrf_field(); ?>
          <input type="hidden" name="action" value="save_produk">
          <?php if ($is_edit): ?>
            <input type="hidden" name="produk_id" value="<?= htmlspecialchars($edit_produk['id']) ?>">
          <?php endif; ?>

          <div class="ft-form-group">
            <label class="ft-label" for="game_id">Pilih Game</label>
            <select name="game_id" id="game_id" class="ft-select" required>
              <option value="">-- Pilih Game --</option>
              <?php foreach ($games_list as $g): ?>
                <option value="<?= $g['id'] ?>" <?= ($is_edit && $edit_produk['game_id'] == $g['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($g['nama_game']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="nama_produk">Nama Produk</label>
            <input type="text" name="nama_produk" id="nama_produk" class="ft-input" required placeholder="Contoh: 5 Diamond Fast" value="<?= htmlspecialchars($is_edit ? $edit_produk['nama_produk'] : '') ?>">
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="jumlah">Jumlah / Detail Item</label>
            <input type="text" name="jumlah" id="jumlah" class="ft-input" required placeholder="Contoh: 5 Diamond atau 1 Bulan" value="<?= htmlspecialchars($is_edit ? $edit_produk['jumlah'] : '') ?>">
            <span style="font-size:10.5px; color:#666;">Nilai nominal internal (misal: 5, 50, 30 hari).</span>
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="harga">Harga (Rupiah, Positif)</label>
            <input type="number" step="0.01" name="harga" id="harga" class="ft-input" required placeholder="Contoh: 15000" min="0.01" value="<?= htmlspecialchars($is_edit ? $edit_produk['harga'] : '') ?>">
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="status">Status Default</label>
            <select name="status" id="status" class="ft-select">
              <option value="aktif" <?= ($is_edit && $edit_produk['status'] === 'aktif') ? 'selected' : '' ?>>Aktif (Tampil Publik)</option>
              <option value="nonaktif" <?= ($is_edit && $edit_produk['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif (Disembunyikan)</option>
            </select>
          </div>

          <div style="display:flex; gap:5px; padding-top:10px;">
            <button type="submit" class="ft-btn ft-btn-primary" style="flex:1; justify-content:center;">
              <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Produk' ?>
            </button>
            <?php if ($is_edit): ?>
              <a href="produk.php?game_filter=<?= $f_game ?>" class="ft-btn ft-btn-secondary" style="flex:1; justify-content:center;">Batal</a>
            <?php endif; ?>
          </div>
        </form>
      </div>

    </div>

  </main>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
