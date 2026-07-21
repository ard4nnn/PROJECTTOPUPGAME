<?php
require_once '../../config/db.php';
require_once '../../includes/init.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: ' . $base_url . 'admin/index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ─── CSRF Verification ─────────────────────────────────────────
    if (!csrf_verify()) {
        $error = 'Permintaan tidak valid. Silakan muat ulang halaman dan coba lagi.';
    } else {
        // ─── Rate Limiting: max 5 percobaan gagal dalam 5 menit ───────
        $max_attempts  = 5;
        $lockout_time  = 300; // 5 menit dalam detik

        if (!isset($_SESSION['admin_login_attempts']))     $_SESSION['admin_login_attempts'] = 0;
        if (!isset($_SESSION['admin_login_last_attempt'])) $_SESSION['admin_login_last_attempt'] = 0;

        // Reset jika window waktu sudah habis
        if (time() - $_SESSION['admin_login_last_attempt'] > $lockout_time) {
            $_SESSION['admin_login_attempts'] = 0;
        }

        if ($_SESSION['admin_login_attempts'] >= $max_attempts) {
            $sisa = $lockout_time - (time() - $_SESSION['admin_login_last_attempt']);
            $error = 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ceil($sisa / 60) . ' menit.';
        } else {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            if (empty($username) || empty($password)) {
                $error = 'Harap isi semua field!';
            } else {
                $auth_failed_with_live_db = false;

                if ($db_connected && $pdo) {
                    try {
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
                        $stmt->execute([$username, $username]);
                        $user = $stmt->fetch();

                        if ($user && password_verify($password, $user['password'])) {
                            if ((int)$user['is_admin'] === 1) {
                                // Login sukses — reset rate-limit counter
                                $_SESSION['admin_login_attempts'] = 0;
                                $_SESSION['admin_id'] = $user['id'];
                                $_SESSION['admin_username'] = $user['username'];
                                
                                header('Location: ' . $base_url . 'admin/index.php');
                                exit;
                            } else {
                                $error = 'Akun ini bukan admin!';
                                $auth_failed_with_live_db = true;
                                $_SESSION['admin_login_attempts']++;
                                $_SESSION['admin_login_last_attempt'] = time();
                            }
                        } else {
                            $error = 'Username atau password salah!';
                            $auth_failed_with_live_db = true;
                            // Increment rate-limit counter
                            $_SESSION['admin_login_attempts']++;
                            $_SESSION['admin_login_last_attempt'] = time();
                        }
                    } catch (PDOException $e) {
                        $error = __('layanan_gangguan_login');
                        $db_connected = false;
                    }
                }

                // Gagal karena DB offline dan bukan karena salah kredensial
                if (!$db_connected && !$auth_failed_with_live_db) {
                    $error = __('layanan_gangguan_login');
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — FUNtopup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              zinc: {
                950: '#09090b',
              }
            }
          }
        }
      }
    </script>
    <style>
    body {
      font-family: 'Outfit', sans-serif;
    }
    /* Accent grid lines */
    .accent-lines{position:absolute;inset:0;pointer-events:none;opacity:.45}
    .hline,.vline{position:absolute;background:#27272a}
    .hline{left:0;right:0;height:1px;transform:scaleX(0);transform-origin:50% 50%;animation:drawX .7s ease forwards}
    .vline{top:0;bottom:0;width:1px;transform:scaleY(0);transform-origin:50% 0%;animation:drawY .8s ease forwards}
    .hline:nth-child(1){top:20%;animation-delay:.1s}
    .hline:nth-child(2){top:50%;animation-delay:.2s}
    .hline:nth-child(3){top:80%;animation-delay:.3s}
    .vline:nth-child(4){left:20%;animation-delay:.25s}
    .vline:nth-child(5){left:50%;animation-delay:.35s}
    .vline:nth-child(6){left:80%;animation-delay:.45s}
    @keyframes drawX{to{transform:scaleX(1)}}
    @keyframes drawY{to{transform:scaleY(1)}}

    /* Card fade-up */
    .card-animate{opacity:0;transform:translateY(16px);animation:fadeUp .7s cubic-bezier(.22,.61,.36,1) .2s forwards}
    @keyframes fadeUp{to{opacity:1;transform:translateY(0)}}

    /* Yellow center glow behind card */
    .yellow-glow{position:absolute;inset:0;pointer-events:none;background:radial-gradient(ellipse 55% 35% at 50% 50%, rgba(251,191,36,0.05), transparent 70%)}
    </style>
</head>
<body class="bg-zinc-950 text-zinc-50 min-h-screen flex items-center justify-center relative overflow-hidden">

  <!-- Subtle yellow center glow -->
  <div class="yellow-glow"></div>

  <!-- Accent grid lines -->
  <div class="accent-lines">
    <div class="hline"></div><div class="hline"></div><div class="hline"></div>
    <div class="vline"></div><div class="vline"></div><div class="vline"></div>
  </div>

  <!-- Auth Card -->
  <div class="card-animate relative z-10 w-full max-w-md border border-yellow-400/20 bg-zinc-900/85 backdrop-blur-md shadow-[0_0_50px_rgba(251,191,36,0.10)] rounded-xl p-8 mx-4">
    <div class="space-y-1 text-center pb-6">
      <div class="flex items-center justify-center mb-2 gap-2 select-none">
        <svg viewBox="0 0 24 30" class="w-[20px] h-[26px] fill-yellow-400 text-yellow-400">
          <path d="M14 2 L2 15 L10 15 L7 28 L20 13 L12 13 Z" />
        </svg>
        <span class="text-2xl font-black italic tracking-tighter text-yellow-400 uppercase flex items-center leading-none">
          FUN<span class="not-italic font-medium text-white normal-case ml-0.5">topup</span>
        </span>
      </div>
      <p class="text-yellow-400/85 text-xs font-bold uppercase tracking-widest">
        Panel Admin
      </p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-xs mb-6 flex items-center gap-2">
            <span>⚠️</span>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-5">
      <?php echo csrf_field(); ?>
      <div class="text-center mb-1">
        <h2 class="text-xl font-bold text-zinc-50">Autentikasi Admin</h2>
        <p class="text-sm text-zinc-400 mt-1">Silakan masuk menggunakan akun administrator</p>
      </div>

      <!-- Username / Email -->
      <div class="grid gap-2">
        <label for="login-user" class="text-zinc-300 text-sm font-medium">Username / Email Admin</label>
        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </span>
          <input
            id="login-user"
            type="text"
            name="username"
            required
            placeholder="Masukkan username admin"
            class="w-full h-9 rounded-md border border-zinc-800 bg-zinc-950 pl-10 pr-3 py-1 text-sm text-zinc-50 placeholder:text-zinc-600 focus:outline-none focus:ring-1 focus:ring-yellow-400/30 focus:border-yellow-400/50 transition-colors"
          />
        </div>
      </div>

      <!-- Password -->
      <div class="grid gap-2">
        <label for="login-password" class="text-zinc-300 text-sm font-medium">Password</label>
        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </span>
          <input
            id="login-password"
            type="password"
            name="password"
            required
            placeholder="Masukkan password"
            class="w-full h-9 rounded-md border border-zinc-800 bg-zinc-950 pl-10 pr-10 py-1 text-sm text-zinc-50 placeholder:text-zinc-600 focus:outline-none focus:ring-1 focus:ring-yellow-400/30 focus:border-yellow-400/50 transition-colors"
          />
          <button
            id="toggle-login-pw"
            type="button"
            class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-md text-zinc-400 hover:text-yellow-400 transition-colors"
            onclick="togglePassword('login-password', 'toggle-login-pw')"
            aria-label="Tampilkan password"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <!-- Masuk Button -->
      <button type="submit" class="w-full h-11 rounded-lg bg-yellow-400 text-zinc-900 font-bold text-base hover:bg-yellow-300 transition-colors mt-2">
        Masuk Admin
      </button>
    </form>

    <div class="mt-6 text-center">
      <a href="<?php echo $base_url; ?>" class="text-xs text-zinc-400 hover:text-yellow-400 transition-colors">
        &larr; Kembali ke Beranda Utama
      </a>
    </div>
  </div>

  <script>
    function togglePassword(inputId, buttonId) {
      const input = document.getElementById(inputId);
      const btn = document.getElementById(buttonId);
      if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61M2 2l20 20"/></svg>`;
      } else {
        input.type = 'password';
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>`;
      }
    }
  </script>
</body>
</html>
