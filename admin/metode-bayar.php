<?php
require_once __DIR__ . '/includes/admin-guard.php';
require_once __DIR__ . '/../config/db.php';

$success_msg = '';
$error_msg = '';

$edit_metode = null;
$is_edit = false;

// Load method for editing
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($db_connected && $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM metode_bayar WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_metode = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($edit_metode) {
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

        if ($action === 'save_metode') {
            $nama    = trim($_POST['nama'] ?? '');
            $kode    = trim($_POST['kode'] ?? '');
            $status  = trim($_POST['status'] ?? 'aktif');
            $metode_id = isset($_POST['metode_id']) ? (int)$_POST['metode_id'] : 0;

            // Validasi input
            if (empty($nama) || empty($kode)) {
                $error_msg = 'Nama metode dan Kode wajib diisi!';
            } elseif (!in_array($status, ['aktif', 'nonaktif'])) {
                $error_msg = 'Status tidak valid.';
            } else {
                if ($db_connected && $pdo) {
                    try {
                        // Check code uniqueness
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM metode_bayar WHERE kode = ? AND id != ?");
                        $stmt->execute([$kode, $metode_id]);
                        if ($stmt->fetchColumn() > 0) {
                            $error_msg = "Kode pembayaran '{$kode}' sudah digunakan oleh metode lain!";
                        } else {
                            if ($metode_id > 0) {
                                // Update
                                $stmt = $pdo->prepare("UPDATE metode_bayar SET nama = ?, kode = ?, status = ? WHERE id = ?");
                                $stmt->execute([$nama, $kode, $status, $metode_id]);
                                $success_msg = "Berhasil memperbarui metode bayar: {$nama}";
                                $is_edit = false;
                                $edit_metode = null;
                            } else {
                                // Insert
                                $stmt = $pdo->prepare("INSERT INTO metode_bayar (nama, kode, status) VALUES (?, ?, ?)");
                                $stmt->execute([$nama, $kode, $status]);
                                $success_msg = "Berhasil menambahkan metode bayar baru: {$nama}";
                            }
                        }
                    } catch (PDOException $e) {
                        $error_msg = "Terjadi kesalahan database: " . $e->getMessage();
                    }
                }
            }
        } elseif ($action === 'toggle_status') {
            $metode_id = (int)$_POST['metode_id'];
            if ($db_connected && $pdo) {
                try {
                    $stmt = $pdo->prepare("SELECT status, nama FROM metode_bayar WHERE id = ?");
                    $stmt->execute([$metode_id]);
                    $metode = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($metode) {
                        $new_status = $metode['status'] === 'aktif' ? 'nonaktif' : 'aktif';
                        $stmt = $pdo->prepare("UPDATE metode_bayar SET status = ? WHERE id = ?");
                        $stmt->execute([$new_status, $metode_id]);
                        $success_msg = "Status metode bayar '{$metode['nama']}' berhasil diubah menjadi: " . strtoupper($new_status);
                    }
                } catch (PDOException $e) {
                    $error_msg = "Gagal mengubah status metode bayar: " . $e->getMessage();
                }
            }
        }
    }
}

// Fetch all payment methods
$metode_list = [];
if ($db_connected && $pdo) {
    try {
        $metode_list = $pdo->query("SELECT * FROM metode_bayar ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
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
      <h1 class="ft-page-title">Metode Pembayaran</h1>
      <p class="ft-page-sub">
          Kelola metode pembayaran pelanggan untuk checkout voucher game.
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
      
      <!-- Left Column: Payment Methods Table -->
      <div class="ft-table-wrap">
        <table class="ft-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Metode</th>
              <th>Kode Bayar</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($metode_list)): ?>
              <?php foreach ($metode_list as $m): ?>
                <tr>
                  <td><code>#<?= $m['id'] ?></code></td>
                  <td><strong><?= htmlspecialchars($m['nama']) ?></strong></td>
                  <td><code><?= htmlspecialchars($m['kode']) ?></code></td>
                  <td>
                    <?php if ($m['status'] === 'aktif'): ?>
                      <span class="ft-badge success">Aktif</span>
                    <?php else: ?>
                      <span class="ft-badge failed">Nonaktif</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div style="display:inline-flex; gap:5px; margin:0;">
                      <a href="?edit=<?= $m['id'] ?>" class="ft-btn ft-btn-secondary" style="padding: 3px 8px; font-size:11px;">Edit</a>
                      
                      <!-- Toggle Status Form -->
                      <form method="POST" action="" style="display:inline; margin:0;">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="metode_id" value="<?= $m['id'] ?>">
                        <button type="submit" class="ft-btn <?= $m['status'] === 'aktif' ? 'ft-btn-secondary' : 'ft-btn-primary' ?>" style="padding: 3px 8px; font-size:11px; font-weight:normal;" onclick="return confirm('Apakah Anda yakin ingin <?= $m['status'] === 'aktif' ? 'menonaktifkan' : 'mengaktifkan' ?> metode ini?')">
                          <?= $m['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5">
                  <div class="ft-empty">
                    <div class="ft-empty-icon">💳</div>
                    <p class="ft-empty-title">Data metode pembayaran kosong!</p>
                    <p class="ft-empty-sub">Silakan tambahkan metode bayar baru di form kanan.</p>
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
          <?= $is_edit ? 'Edit Metode' : 'Tambah Metode Baru' ?>
        </h2>
        
        <form method="POST" action="" style="margin-top: 1rem;" class="space-y-4">
          <?= csrf_field(); ?>
          <input type="hidden" name="action" value="save_metode">
          <?php if ($is_edit): ?>
            <input type="hidden" name="metode_id" value="<?= htmlspecialchars($edit_metode['id']) ?>">
          <?php endif; ?>

          <div class="ft-form-group">
            <label class="ft-label" for="nama">Nama Metode Bayar</label>
            <input type="text" name="nama" id="nama" class="ft-input" required placeholder="Contoh: QRIS Gopay / Transfer BCA" value="<?= htmlspecialchars($is_edit ? $edit_metode['nama'] : '') ?>">
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="kode">Kode Pembayaran (Unik)</label>
            <input type="text" name="kode" id="kode" class="ft-input" required placeholder="Contoh: qris / bca" value="<?= htmlspecialchars($is_edit ? $edit_metode['kode'] : '') ?>">
            <span style="font-size:10.5px; color:#666;">Identifier unik di sistem. Contoh: mandiri, shopeepay.</span>
          </div>

          <div class="ft-form-group">
            <label class="ft-label" for="status">Status Default</label>
            <select name="status" id="status" class="ft-select">
              <option value="aktif" <?= ($is_edit && $edit_metode['status'] === 'aktif') ? 'selected' : '' ?>>Aktif (Tampil Publik)</option>
              <option value="nonaktif" <?= ($is_edit && $edit_metode['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif (Disembunyikan)</option>
            </select>
          </div>

          <div style="display:flex; gap:5px; padding-top:10px;">
            <button type="submit" class="ft-btn ft-btn-primary" style="flex:1; justify-content:center;">
              <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Metode' ?>
            </button>
            <?php if ($is_edit): ?>
              <a href="metode-bayar.php" class="ft-btn ft-btn-secondary" style="flex:1; justify-content:center;">Batal</a>
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
