<?php
require_once __DIR__ . '/includes/admin-guard.php';
require_once __DIR__ . '/../config/db.php';

$success_msg = '';
$error_msg = '';

$edit_flash = null;
$is_edit = false;

// Load flash sale for editing
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($db_connected && $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM flash_sale WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_flash = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($edit_flash) {
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

        if ($action === 'save_flash') {
            $judul     = trim($_POST['judul'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $end_time  = trim($_POST['end_time'] ?? '');
            $status    = trim($_POST['status'] ?? 'aktif');
            $flash_id  = isset($_POST['flash_id']) ? (int)$_POST['flash_id'] : 0;

            // Validasi input
            if (empty($judul) || empty($end_time)) {
                $error_msg = 'Judul dan Waktu Berakhir wajib diisi!';
            } elseif (!in_array($status, ['aktif', 'nonaktif'])) {
                $error_msg = 'Status tidak valid.';
            } else {
                if ($db_connected && $pdo) {
                    try {
                        $formatted_end = date('Y-m-d H:i:s', strtotime($end_time));
                        if ($flash_id > 0) {
                            // Update
                            $stmt = $pdo->prepare("UPDATE flash_sale SET judul = ?, deskripsi = ?, end_time = ?, status = ? WHERE id = ?");
                            $stmt->execute([$judul, $deskripsi, $formatted_end, $status, $flash_id]);
                            $success_msg = "Berhasil memperbarui flash sale: {$judul}";
                            $is_edit = false;
                            $edit_flash = null;
                        } else {
                            // Insert
                            $stmt = $pdo->prepare("INSERT INTO flash_sale (judul, deskripsi, end_time, status) VALUES (?, ?, ?, ?)");
                            $stmt->execute([$judul, $deskripsi, $formatted_end, $status]);
                            $success_msg = "Berhasil menambahkan flash sale baru: {$judul}";
                        }
                    } catch (PDOException $e) {
                        $error_msg = "Terjadi kesalahan database: " . $e->getMessage();
                    }
                }
            }
        } elseif ($action === 'toggle_status') {
            $flash_id = (int)$_POST['flash_id'];
            if ($db_connected && $pdo) {
                try {
                    $stmt = $pdo->prepare("SELECT status, judul FROM flash_sale WHERE id = ?");
                    $stmt->execute([$flash_id]);
                    $flash = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($flash) {
                        $new_status = $flash['status'] === 'aktif' ? 'nonaktif' : 'aktif';
                        $stmt = $pdo->prepare("UPDATE flash_sale SET status = ? WHERE id = ?");
                        $stmt->execute([$new_status, $flash_id]);
                        $success_msg = "Status flash sale '{$flash['judul']}' berhasil diubah menjadi: " . strtoupper($new_status);
                    }
                } catch (PDOException $e) {
                    $error_msg = "Gagal mengubah status flash sale: " . $e->getMessage();
                }
            }
        }
    }
}

// Fetch all flash sales
$flash_list = [];
if ($db_connected && $pdo) {
    try {
        $flash_list = $pdo->query("SELECT * FROM flash_sale ORDER BY end_time DESC")->fetchAll(PDO::FETCH_ASSOC);
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
      <h1 class="ft-page-title">Kelola Flash Sale</h1>
      <p class="ft-page-sub">
          Atur promo kilat dan diskon khusus voucher game di beranda utama.
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
      
      <!-- Left Column: Flash Sales Table -->
      <div class="ft-table-wrap">
        <table class="ft-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Judul Promo</th>
              <th>Deskripsi</th>
              <th>Waktu Berakhir</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($flash_list)): ?>
              <?php foreach ($flash_list as $f): 
                  $is_expired = strtotime($f['end_time']) < time();
              ?>
                <tr>
                  <td><code>#<?= $f['id'] ?></code></td>
                  <td><strong><?= htmlspecialchars($f['judul']) ?></strong></td>
                  <td><span style="color:#888;"><?= htmlspecialchars($f['deskripsi'] ?? '-') ?></span></td>
                  <td>
                    <span style="font-weight:600; color: <?= $is_expired ? '#ef4444' : '#FBBF24' ?>;">
                      <?= date('d/m/Y H:i', strtotime($f['end_time'])) ?>
                    </span>
                    <?php if ($is_expired): ?>
                      <div style="font-size:9.5px; color:#ef4444; font-weight:700;">EXPIRED</div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($f['status'] === 'aktif'): ?>
                      <span class="ft-badge success">Aktif</span>
                    <?php else: ?>
                      <span class="ft-badge failed">Nonaktif</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div style="display:inline-flex; gap:5px; margin:0;">
                      <a href="?edit=<?= $f['id'] ?>" class="ft-btn ft-btn-secondary" style="padding: 3px 8px; font-size:11px;">Edit</a>
                      
                      <!-- Toggle Status Form -->
                      <form method="POST" action="" style="display:inline; margin:0;">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="flash_id" value="<?= $f['id'] ?>">
                        <button type="submit" class="ft-btn <?= $f['status'] === 'aktif' ? 'ft-btn-secondary' : 'ft-btn-primary' ?>" style="padding: 3px 8px; font-size:11px; font-weight:normal;" onclick="return confirm('Apakah Anda yakin ingin <?= $f['status'] === 'aktif' ? 'menonaktifkan' : 'mengaktifkan' ?> promo ini?')">
                          <?= $f['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
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
                    <div class="ft-empty-icon">🔥</div>
                    <p class="ft-empty-title">Data flash sale kosong!</p>
                    <p class="ft-empty-sub">Silakan buat flash sale baru di form kanan.</p>
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
          <?= $is_edit ? 'Edit Flash Sale' : 'Buat Flash Sale Baru' ?>
        </h2>
        
        <form method="POST" action="" style="margin-top: 1rem;" class="space-y-4">
          <?= csrf_field(); ?>
          <input type="hidden" name="action" value="save_flash">
          <?php if ($is_edit): ?>
            <input type="hidden" name="flash_id" value="<?= htmlspecialchars($edit_flash['id']) ?>">
          <?php endif; ?>

          <div class="ft-form-group">
            <label class="ft-label" for="judul">Judul Promo</label>
            <input type="text" name="judul" id="judul" class="ft-input" required placeholder="Contoh: PASTI TER-MURAAHH" value="<?= htmlspecialchars($is_edit ? $edit_flash['judul'] : '') ?>">
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="deskripsi">Deskripsi / Subtitle</label>
            <input type="text" name="deskripsi" id="deskripsi" class="ft-input" placeholder="Contoh: Bandingkan dan Buktikan Sendiri!" value="<?= htmlspecialchars($is_edit ? ($edit_flash['deskripsi'] ?? '') : '') ?>">
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="end_time">Waktu Berakhir</label>
            <input type="datetime-local" name="end_time" id="end_time" class="ft-input" required value="<?= $is_edit ? date('Y-m-d\TH:i', strtotime($edit_flash['end_time'])) : '' ?>">
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="status">Status Default</label>
            <select name="status" id="status" class="ft-select">
              <option value="aktif" <?= ($is_edit && $edit_flash['status'] === 'aktif') ? 'selected' : '' ?>>Aktif (Tampil Publik)</option>
              <option value="nonaktif" <?= ($is_edit && $edit_flash['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif (Disembunyikan)</option>
            </select>
          </div>

          <div style="display:flex; gap:5px; padding-top:10px;">
            <button type="submit" class="ft-btn ft-btn-primary" style="flex:1; justify-content:center;">
              <?= $is_edit ? 'Simpan Promo' : 'Buat Promo' ?>
            </button>
            <?php if ($is_edit): ?>
              <a href="flash-sale.php" class="ft-btn ft-btn-secondary" style="flex:1; justify-content:center;">Batal</a>
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
