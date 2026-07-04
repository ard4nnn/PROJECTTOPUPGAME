<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];
$msg_sukses = '';
$msg_error  = '';
$user = null;

// Deteksi dinamis nama kolom telepon (no_hp / phone) di tabel users
$db_phone_col = 'no_hp';
if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'phone'");
        if ($stmt->rowCount() > 0) {
            $db_phone_col = 'phone';
        } else {
            $stmt2 = $pdo->query("SHOW COLUMNS FROM users LIKE 'no_hp'");
            if ($stmt2->rowCount() > 0) {
                $db_phone_col = 'no_hp';
            }
        }
    } catch (PDOException $e) {
        $db_phone_col = 'no_hp';
    }
}

// Ambil data user
if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Sinkronisasi data ke session
        if ($user) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'] ?? '';
            $_SESSION['no_hp'] = $user[$db_phone_col] ?? '';
            $_SESSION['phone'] = $user[$db_phone_col] ?? '';
        }
    } catch (PDOException $e) {
        // Fallback ke session jika query error
    }
}

// Fallback profil jika DB offline
if (!$user) {
    $user = [
        'username' => $_SESSION['username'] ?? 'User',
        'email' => $_SESSION['email'] ?? 'demo@example.com',
        'no_hp' => $_SESSION['no_hp'] ?? $_SESSION['phone'] ?? '-',
        'created_at' => date('Y-m-d H:i:s')
    ];
    $user[$db_phone_col] = $user['no_hp'];
}

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profil') {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $phone    = trim($_POST['phone']    ?? '');
        
        if ($username && $email) {
            if ($db_connected && $pdo) {
                try {
                    // Cek username/email unik
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
                    $stmt->execute([$username, $email, $user_id]);
                    
                    if ($stmt->rowCount() > 0) {
                        $msg_error = 'Username atau email sudah digunakan oleh pengguna lain.';
                    } else {
                        // Update biodata
                        $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, {$db_phone_col}=? WHERE id=?");
                        $stmt->execute([$username, $email, $phone, $user_id]);
                        
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $email;
                        $_SESSION['no_hp'] = $phone;
                        $_SESSION['phone'] = $phone;
                        
                        $user['username'] = $username;
                        $user['email']    = $email;
                        $user[$db_phone_col] = $phone;
                        
                        $msg_sukses = 'Profil berhasil diperbarui!';
                    }
                } catch (PDOException $e) {
                    $msg_error = 'Gagal memperbarui profil di database: ' . $e->getMessage();
                }
            } else {
                // Mode Demo (Offline)
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['no_hp'] = $phone;
                $_SESSION['phone'] = $phone;
                
                $user['username'] = $username;
                $user['email']    = $email;
                $user[$db_phone_col] = $phone;
                
                $msg_sukses = 'Profil berhasil diperbarui! (Mode Demo Aktif)';
            }
        } else {
            $msg_error = 'Username dan email tidak boleh kosong.';
        }
    }

    if ($action === 'ganti_password') {
        $pw_lama  = $_POST['pw_lama']  ?? '';
        $pw_baru  = $_POST['pw_baru']  ?? '';
        $pw_konfirm = $_POST['pw_konfirm'] ?? '';
        
        if ($pw_lama && $pw_baru && $pw_konfirm) {
            if ($db_connected && $pdo) {
                try {
                    // Ambil password lama dari DB
                    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $hash_lama = $stmt->fetchColumn();
                    
                    if ($hash_lama && password_verify($pw_lama, $hash_lama)) {
                        if (strlen($pw_baru) >= 6) {
                            if ($pw_baru === $pw_konfirm) {
                                $hash_baru = password_hash($pw_baru, PASSWORD_DEFAULT);
                                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                                $stmt->execute([$hash_baru, $user_id]);
                                $msg_sukses = 'Password berhasil diubah!';
                            } else {
                                $msg_error = 'Konfirmasi password tidak cocok.';
                            }
                        } else {
                            $msg_error = 'Password baru minimal 6 karakter.';
                        }
                    } else {
                        $msg_error = 'Password lama tidak sesuai.';
                    }
                } catch (PDOException $e) {
                    $msg_error = 'Gagal mengubah password di database.';
                }
            } else {
                // Mode Demo (Offline)
                if (strlen($pw_baru) >= 6) {
                    if ($pw_baru === $pw_konfirm) {
                        $msg_sukses = 'Password berhasil diubah! (Simulasi Mode Demo)';
                    } else {
                        $msg_error = 'Konfirmasi password baru tidak cocok.';
                    }
                } else {
                    $msg_error = 'Password baru minimal 6 karakter.';
                }
            }
        } else {
            $msg_error = 'Semua input password wajib diisi.';
        }
    }
}

$avatar_char = strtoupper(substr($user['username'] ?? 'U', 0, 1));

// Load header website
require_once '../../includes/header.php';
?>
<!-- Link dashboard styling -->
<link rel="stylesheet" href="dashboard.css">

<div class="ft-wrapper">
<?php include 'sidebar.php'; ?>
<main class="ft-main">

  <div class="ft-page-header">
    <h1 class="ft-page-title">Profil Saya</h1>
    <p class="ft-page-sub">
        Kelola informasi akun FUNtopup kamu.
        <?php if (!$db_connected): ?>
            <span style="color:#FBBF24; font-weight:800; margin-left:10px;">[🔌 Mode Demo Aktif]</span>
        <?php endif; ?>
    </p>
  </div>

  <?php if($msg_sukses): ?>
    <div style="background:rgba(20,83,45,0.3);border:1px solid #14532d;color:#4ade80;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.85rem;" id="alert-sukses">✅ <?=htmlspecialchars($msg_sukses)?></div>
  <?php endif; ?>
  <?php if($msg_error): ?>
    <div style="background:rgba(127,29,29,0.3);border:1px solid #7f1d1d;color:#f87171;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.85rem;" id="alert-error">⚠ <?=htmlspecialchars($msg_error)?></div>
  <?php endif; ?>

  <!-- Avatar + info -->
  <div class="ft-card" style="padding:1.5rem;display:flex;align-items:center;gap:1.25rem;margin-bottom:1rem;">
    <div class="ft-profile-avatar" style="width:70px;height:70px;font-size:1.8rem;"><?=$avatar_char?></div>
    <div>
      <p style="font-size:1.1rem;font-weight:800;color:#fff; margin:0;"><?=htmlspecialchars($user['username']??'')?></p>
      <span class="ft-member-badge"><?=htmlspecialchars($_SESSION['level']??'Member')?></span>
      <p style="font-size:0.78rem;color:#777;margin-top:6px; margin-bottom:0;">Bergabung sejak <?=date('d M Y', strtotime($user['created_at']??$user['tanggal_daftar']??'now'))?></p>
    </div>
  </div>

  <!-- Form Edit Profil -->
  <div class="ft-card" style="padding:1.5rem;margin-bottom:1rem;">
    <h2 style="font-size:1rem;font-weight:700;color:#fff;margin:0 0 1rem 0;">✏ Edit Informasi Profil</h2>
    <form method="POST">
      <input type="hidden" name="action" value="update_profil">
      <div class="ft-form-grid">
        <div class="ft-form-group">
          <label class="ft-label">Username</label>
          <input type="text" name="username" class="ft-input" required value="<?=htmlspecialchars($user['username']??'')?>">
        </div>
        <div class="ft-form-group">
          <label class="ft-label">Email</label>
          <input type="email" name="email" class="ft-input" required value="<?=htmlspecialchars($user['email']??'')?>">
        </div>
        <div class="ft-form-group">
          <label class="ft-label">No. HP / WhatsApp</label>
          <input type="tel" name="phone" class="ft-input" value="<?=htmlspecialchars($user[$db_phone_col]??'')?>">
        </div>
      </div>
      <button type="submit" class="ft-btn ft-btn-primary" style="margin-top:1rem;">Simpan Perubahan</button>
    </form>
  </div>

  <!-- Form Ganti Password -->
  <div class="ft-card" style="padding:1.5rem;">
    <h2 style="font-size:1rem;font-weight:700;color:#fff;margin:0 0 1rem 0;">🔒 Ganti Password</h2>
    <form method="POST">
      <input type="hidden" name="action" value="ganti_password">
      <div class="ft-form-grid">
        <div class="ft-form-group">
          <label class="ft-label">Password Lama</label>
          <input type="password" name="pw_lama" class="ft-input" required placeholder="••••••••">
        </div>
        <div class="ft-form-group">
          <label class="ft-label">Password Baru (min. 6 karakter)</label>
          <input type="password" name="pw_baru" class="ft-input" required placeholder="••••••••">
        </div>
        <div class="ft-form-group">
          <label class="ft-label">Konfirmasi Password Baru</label>
          <input type="password" name="pw_konfirm" class="ft-input" required placeholder="••••••••">
        </div>
      </div>
      <button type="submit" class="ft-btn ft-btn-primary" style="margin-top:1rem;">Ubah Password</button>
    </form>
  </div>

</main>
</div>

<script>
// Auto remove alert messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const sukses = document.getElementById('alert-sukses');
        const error = document.getElementById('alert-error');
        if (sukses) {
            sukses.style.transition = 'opacity 0.5s ease';
            sukses.style.opacity = '0';
            setTimeout(() => sukses.remove(), 500);
        }
        if (error) {
            error.style.transition = 'opacity 0.5s ease';
            error.style.opacity = '0';
            setTimeout(() => error.remove(), 500);
        }
    }, 5000);
});
</script>

<?php
require_once '../../includes/footer.php';
?>
