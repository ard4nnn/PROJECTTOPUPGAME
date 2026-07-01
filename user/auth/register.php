<?php
require_once '../../config/db.php';
require_once '../../includes/header.php';

if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '" . $base_url . "';</script>";
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($no_hp) || empty($password)) {
        $error = 'Semua data wajib diisi!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal terdiri dari 6 karakter!';
    } else {
        if ($db_connected && $pdo) {
            try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                
                if ($stmt->rowCount() > 0) {
                    $error = 'Username atau Email sudah terdaftar!';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, no_hp, saldo) VALUES (?, ?, ?, ?, 0.00)");
                    $stmt->execute([$username, $email, $hashed_password, $no_hp]);
                    
                    $success = 'Pendaftaran berhasil! Silakan login.';
                }
            } catch (PDOException $e) {
                $error = 'Kesalahan database. Menggunakan Mode Demo.';
            }
        }
        
        if (!$db_connected || !empty($error)) {
            $success = 'Pendaftaran Akun Demo Berhasil! Silakan masuk dengan akun baru Anda.';
        }
    }
}
?>

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

<section class="relative w-full min-h-[calc(100vh-70px)] bg-zinc-950 text-zinc-50 flex items-center justify-center px-4 py-12 overflow-hidden">
  <!-- Golden particles -->
  <canvas id="particles-canvas" class="absolute inset-0 w-full h-full opacity-50 pointer-events-none"></canvas>

  <!-- Subtle yellow center glow -->
  <div class="yellow-glow"></div>

  <!-- Accent grid lines -->
  <div class="accent-lines">
    <div class="hline"></div><div class="hline"></div><div class="hline"></div>
    <div class="vline"></div><div class="vline"></div><div class="vline"></div>
  </div>

  <!-- Auth Card -->
  <div class="card-animate relative z-10 w-full max-w-md border border-yellow-400/20 bg-zinc-900/85 backdrop-blur-md shadow-[0_0_50px_rgba(251,191,36,0.10)] rounded-xl p-6">
    <div class="space-y-1 text-center pb-6">
      <div class="flex items-center justify-center mb-2 gap-2 select-none">
        <svg viewBox="0 0 24 30" class="w-[20px] h-[26px] fill-yellow-400 text-yellow-400">
          <path d="M14 2 L2 15 L10 15 L7 28 L20 13 L12 13 Z" />
        </svg>
        <span class="text-2xl font-black italic tracking-tighter text-yellow-400 uppercase flex items-center leading-none">
          FUN<span class="not-italic font-medium text-white normal-case ml-0.5">topup</span>
        </span>
      </div>
      <p class="text-zinc-400 text-xs">
        Platform top up game murah, cepat &amp; aman
      </p>
    </div>

    <!-- TABS -->
    <div class="bg-[#0f0f10] border border-zinc-800 rounded-lg p-1 grid grid-cols-2 mb-6">
      <a href="login.php" class="text-center py-2 text-xs font-medium tracking-wide rounded-md transition-all text-zinc-400 hover:text-zinc-200">Masuk</a>
      <a href="register.php" class="text-center py-2 text-xs font-bold tracking-wide rounded-md transition-all bg-[#1c1c1e] text-yellow-400 shadow-[inset_0_0_0_1px_#FBBF24]">Daftar</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-xs mb-4 flex items-center gap-2">
            <span>⚠️</span>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-lg text-xs mb-4 flex items-center gap-2">
            <span>🎉</span>
            <span><?php echo htmlspecialchars($success); ?></span>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-4">
      <div class="text-center mb-1">
        <h2 class="text-xl font-bold text-zinc-50">Daftar Akun</h2>
        <p class="text-sm text-zinc-400 mt-1">Buat akun FUNtopup Anda sekarang</p>
      </div>

      <!-- Username -->
      <div class="grid gap-2">
        <label for="reg-username" class="text-zinc-300 text-sm font-medium">Username</label>
        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </span>
          <input
            id="reg-username"
            type="text"
            name="username"
            required
            placeholder="Pilih username unik"
            class="w-full h-9 rounded-md border border-zinc-800 bg-zinc-950 pl-10 pr-3 py-1 text-sm text-zinc-50 placeholder:text-zinc-600 focus:outline-none focus:ring-1 focus:ring-yellow-400/30 focus:border-yellow-400/50 transition-colors"
          />
        </div>
      </div>

      <!-- Email -->
      <div class="grid gap-2">
        <label for="reg-email" class="text-zinc-300 text-sm font-medium">Email</label>
        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
          </span>
          <input
            id="reg-email"
            type="email"
            name="email"
            required
            placeholder="Masukkan alamat email aktif"
            class="w-full h-9 rounded-md border border-zinc-800 bg-zinc-950 pl-10 pr-3 py-1 text-sm text-zinc-50 placeholder:text-zinc-600 focus:outline-none focus:ring-1 focus:ring-yellow-400/30 focus:border-yellow-400/50 transition-colors"
          />
        </div>
      </div>

      <!-- No. HP / WhatsApp -->
      <div class="grid gap-2">
        <label for="reg-phone" class="text-zinc-300 text-sm font-medium">No. HP / WhatsApp</label>
        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </span>
          <input
            id="reg-phone"
            type="tel"
            name="no_hp"
            required
            placeholder="Contoh: 0812345678"
            class="w-full h-9 rounded-md border border-zinc-800 bg-zinc-950 pl-10 pr-3 py-1 text-sm text-zinc-50 placeholder:text-zinc-600 focus:outline-none focus:ring-1 focus:ring-yellow-400/30 focus:border-yellow-400/50 transition-colors"
          />
        </div>
      </div>

      <!-- Password -->
      <div class="grid gap-2">
        <label for="reg-password" class="text-zinc-300 text-sm font-medium">Password</label>
        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </span>
          <input
            id="reg-password"
            type="password"
            name="password"
            required
            placeholder="Gunakan minimal 6 karakter"
            class="w-full h-9 rounded-md border border-zinc-800 bg-zinc-950 pl-10 pr-10 py-1 text-sm text-zinc-50 placeholder:text-zinc-600 focus:outline-none focus:ring-1 focus:ring-yellow-400/30 focus:border-yellow-400/50 transition-colors"
          />
          <button
            id="toggle-reg-pw"
            type="button"
            class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-md text-zinc-400 hover:text-yellow-400 transition-colors"
            onclick="togglePassword('reg-password', 'toggle-reg-pw')"
            aria-label="Tampilkan password"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <!-- Setuju Syarat & Ketentuan -->
      <div class="flex items-start gap-2 pt-1">
        <input
          type="checkbox"
          id="terms"
          required
          class="h-4 w-4 mt-0.5 rounded border-zinc-700 text-yellow-400 focus:ring-yellow-400/30 bg-zinc-950 accent-yellow-400 cursor-pointer"
        />
        <label for="terms" class="text-zinc-400 text-sm cursor-pointer select-none leading-snug">
          Saya setuju dengan <a href="/terms" class="text-yellow-400 hover:text-yellow-300 transition-colors">Syarat &amp; Ketentuan</a> FUNtopup
        </label>
      </div>

      <!-- Daftar Button -->
      <button type="submit" class="w-full h-11 rounded-lg bg-yellow-400 text-zinc-900 font-bold text-base hover:bg-yellow-300 transition-colors">
        Daftar Akun
      </button>
    </form>

    <div class="relative py-4">
      <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-zinc-800"></span></div>
      <div class="relative flex justify-center text-[11px] uppercase"><span class="bg-[#18181b] px-2 text-zinc-500 tracking-widest">atau</span></div>
    </div>

    <!-- Google Button -->
    <button
      type="button"
      class="w-full h-10 rounded-lg border border-zinc-700 bg-zinc-950 text-zinc-50 hover:bg-zinc-800 hover:border-yellow-400/40 transition-colors flex items-center justify-center gap-2 text-sm font-medium"
    >
      <img src="<?php echo $base_url; ?>assets/images/google.png" alt="Google" class="h-4 w-4 object-contain" />
      Daftar dengan Google
    </button>

    <div class="flex items-center justify-center text-sm text-zinc-400 pt-6">
      Butuh bantuan?&nbsp;
      <a class="text-yellow-400 hover:text-yellow-300 hover:underline transition-colors" href="/faqs">
        Lihat FAQ
      </a>
    </div>
  </div>
</section>

<script>
function togglePassword(id, buttonId) {
    const input = document.getElementById(id);
    const btn = document.getElementById(buttonId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>`;
        btn.setAttribute('aria-label', 'Sembunyikan password');
    } else {
        input.type = 'password';
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>`;
        btn.setAttribute('aria-label', 'Tampilkan password');
    }
}

// Particle Background Animation
(function() {
    const canvas = document.getElementById('particles-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    function setSize() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    }
    setSize();

    let ps = [];
    const make = () => ({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      v: Math.random() * 0.25 + 0.05,
      o: Math.random() * 0.3 + 0.08,
    });

    function init() {
      ps = [];
      const count = Math.floor((canvas.width * canvas.height) / 9000);
      for (let i = 0; i < count; i++) ps.push(make());
    }

    function draw() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ps.forEach((p) => {
        p.y -= p.v;
        if (p.y < 0) {
          p.x = Math.random() * canvas.width;
          p.y = canvas.height + Math.random() * 40;
          p.v = Math.random() * 0.25 + 0.05;
          p.o = Math.random() * 0.3 + 0.08;
        }
        ctx.fillStyle = `rgba(251,191,36,${p.o})`;
        ctx.fillRect(p.x, p.y, 0.8, 2.5);
      });
      requestAnimationFrame(draw);
    }

    window.addEventListener('resize', () => { setSize(); init(); });
    init();
    draw();
})();
</script>

<?php require_once '../../includes/footer.php'; ?>
