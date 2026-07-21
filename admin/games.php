<?php
require_once __DIR__ . '/includes/admin-guard.php';
require_once __DIR__ . '/../config/db.php';

$success_msg = '';
$error_msg = '';

$edit_game = null;
$is_edit = false;

// Load game for editing
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($db_connected && $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_game = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($edit_game) {
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

        if ($action === 'save_game') {
            $nama_game = trim($_POST['nama_game'] ?? '');
            $slug      = trim($_POST['slug'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $status    = trim($_POST['status'] ?? 'aktif');
            $game_id   = isset($_POST['game_id']) ? (int)$_POST['game_id'] : 0;

            // Validasi input
            if (empty($nama_game) || empty($slug)) {
                $error_msg = 'Nama game dan Slug wajib diisi!';
            } elseif (!preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $slug)) {
                $error_msg = 'Format slug tidak valid! Harus huruf kecil, angka, dan strip saja (contoh: mobile-legends, free-fire).';
            } elseif (!in_array($status, ['aktif', 'nonaktif'])) {
                $error_msg = 'Status tidak valid.';
            } else {
                if ($db_connected && $pdo) {
                    try {
                        // Check slug uniqueness
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM games WHERE slug = ? AND id != ?");
                        $stmt->execute([$slug, $game_id]);
                        if ($stmt->fetchColumn() > 0) {
                            $error_msg = "Slug '{$slug}' sudah digunakan oleh game lain!";
                        } else {
                            if ($game_id > 0) {
                                // Update
                                $stmt = $pdo->prepare("UPDATE games SET nama_game = ?, slug = ?, deskripsi = ?, status = ? WHERE id = ?");
                                $stmt->execute([$nama_game, $slug, $deskripsi, $status, $game_id]);
                                $success_msg = "Berhasil memperbarui game: {$nama_game}";
                                // Clear edit state
                                $is_edit = false;
                                $edit_game = null;
                            } else {
                                // Insert
                                $stmt = $pdo->prepare("INSERT INTO games (nama_game, slug, deskripsi, status) VALUES (?, ?, ?, ?)");
                                $stmt->execute([$nama_game, $slug, $deskripsi, $status]);
                                $success_msg = "Berhasil menambahkan game baru: {$nama_game}";
                            }
                        }
                    } catch (PDOException $e) {
                        $error_msg = "Terjadi kesalahan database: " . $e->getMessage();
                    }
                }
            }
        } elseif ($action === 'toggle_status') {
            $game_id = (int)$_POST['game_id'];
            if ($db_connected && $pdo) {
                try {
                    // Fetch current status
                    $stmt = $pdo->prepare("SELECT status, nama_game FROM games WHERE id = ?");
                    $stmt->execute([$game_id]);
                    $game = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($game) {
                        $new_status = $game['status'] === 'aktif' ? 'nonaktif' : 'aktif';
                        $stmt = $pdo->prepare("UPDATE games SET status = ? WHERE id = ?");
                        $stmt->execute([$new_status, $game_id]);
                        $success_msg = "Status game '{$game['nama_game']}' berhasil diubah menjadi: " . strtoupper($new_status);
                    }
                } catch (PDOException $e) {
                    $error_msg = "Gagal mengubah status game: " . $e->getMessage();
                }
            }
        }
    }
}

// Fetch all games
$games_list = [];
if ($db_connected && $pdo) {
    try {
        $games_list = $pdo->query("SELECT * FROM games ORDER BY nama_game ASC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // fail silently
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<!-- Link dashboard styling -->
<link rel="stylesheet" href="../user/dashboard/dashboard.css">

<div class="ft-wrapper">
  <?php include __DIR__ . '/includes/admin-sidebar.php'; ?>
  <main class="ft-main">

    <div class="ft-page-header">
      <h1 class="ft-page-title">Kelola Games</h1>
      <p class="ft-page-sub">
          Tambah game baru atau ubah status game yang sudah ada.
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

    <!-- Two Column Layout: Table on left, Form on right -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
      
      <!-- Left Column: Games Table -->
      <div class="ft-table-wrap">
        <table class="ft-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Game</th>
              <th>Slug (URL)</th>
              <th>Deskripsi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($games_list)): ?>
              <?php foreach ($games_list as $g): ?>
                <tr>
                  <td><code>#<?= $g['id'] ?></code></td>
                  <td><strong><?= htmlspecialchars($g['nama_game']) ?></strong></td>
                  <td><code><?= htmlspecialchars($g['slug']) ?></code></td>
                  <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:#888;">
                    <?= htmlspecialchars($g['deskripsi'] ?? '-') ?>
                  </td>
                  <td>
                    <?php if ($g['status'] === 'aktif'): ?>
                      <span class="ft-badge success">Aktif</span>
                    <?php else: ?>
                      <span class="ft-badge failed">Nonaktif</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div style="display:inline-flex; gap:5px; margin:0;">
                      <a href="?edit=<?= $g['id'] ?>" class="ft-btn ft-btn-secondary" style="padding: 3px 8px; font-size:11px;">Edit</a>
                      
                      <!-- Toggle Status Form -->
                      <form method="POST" action="" style="display:inline; margin:0;">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="game_id" value="<?= $g['id'] ?>">
                        <button type="submit" class="ft-btn <?= $g['status'] === 'aktif' ? 'ft-btn-secondary' : 'ft-btn-primary' ?>" style="padding: 3px 8px; font-size:11px; font-weight:normal;" onclick="return confirm('Apakah Anda yakin ingin <?= $g['status'] === 'aktif' ? 'menonaktifkan' : 'mengaktifkan' ?> game ini?')">
                          <?= $g['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6">
                  <div class="ft-empty">
                    <div class="ft-empty-icon">🎮</div>
                    <p class="ft-empty-title">Data game kosong!</p>
                    <p class="ft-empty-sub">Silakan tambahkan game baru di form kanan.</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Right Column: Add/Edit Form -->
      <div class="ft-card" style="margin-bottom:0;">
        <h2 class="ft-section-title" style="margin-top:0; font-size:1.1rem; border-bottom:1px solid #222240; padding-bottom:8px;">
          <?= $is_edit ? 'Edit Game' : 'Tambah Game Baru' ?>
        </h2>
        
        <form method="POST" action="" style="margin-top: 1rem;" class="space-y-4">
          <?= csrf_field(); ?>
          <input type="hidden" name="action" value="save_game">
          <?php if ($is_edit): ?>
            <input type="hidden" name="game_id" value="<?= htmlspecialchars($edit_game['id']) ?>">
          <?php endif; ?>

          <div class="ft-form-group">
            <label class="ft-label" for="nama_game">Nama Game</label>
            <input type="text" name="nama_game" id="nama_game" class="ft-input" required placeholder="Contoh: Mobile Legends" value="<?= htmlspecialchars($is_edit ? $edit_game['nama_game'] : '') ?>">
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="slug">Slug (Unik &amp; Lowercase dengan strip)</label>
            <input type="text" name="slug" id="slug" class="ft-input" required placeholder="Contoh: mobile-legends" value="<?= htmlspecialchars($is_edit ? $edit_game['slug'] : '') ?>">
            <span style="font-size:10.5px; color:#666;">Hanya huruf kecil, angka, dan strip (-). Contoh: free-fire</span>
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="ft-input" style="height:80px; resize:none;" placeholder="Deskripsi singkat game..."><?= htmlspecialchars($is_edit ? ($edit_game['deskripsi'] ?? '') : '') ?></textarea>
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="status">Status Default</label>
            <select name="status" id="status" class="ft-select">
              <option value="aktif" <?= ($is_edit && $edit_game['status'] === 'aktif') ? 'selected' : '' ?>>Aktif (Tampil Publik)</option>
              <option value="nonaktif" <?= ($is_edit && $edit_game['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif (Disembunyikan)</option>
            </select>
          </div>

          <div style="display:flex; gap:5px; padding-top:10px;">
            <button type="submit" class="ft-btn ft-btn-primary" style="flex:1; justify-content:center;">
              <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Game' ?>
            </button>
            <?php if ($is_edit): ?>
              <a href="games.php" class="ft-btn ft-btn-secondary" style="flex:1; justify-content:center;">Batal</a>
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
