<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$base_url = "/PROJECTTOPUPGAME/";

if (!isset($_SESSION['user_id'])) {
    $_SESSION['mock_user'] = true;
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUNtopup - Top Up Game Instan, Murah & Aman</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #0f172a;
            --text-muted: #64748b;
            --card-bg: #f8fafc;
            --card-border: #e2e8f0;
            --nav-bg: #0f172a;
            --nav-text: #ffffff;
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.5);
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        [data-theme="dark"] {
            --bg-color: #090d16;
            --text-color: #f1f5f9;
            --text-muted: #94a3b8;
            --card-bg: #131b2e;
            --card-border: #1e293b;
            --nav-bg: #030712;
            --nav-text: #ffffff;
            --primary-color: #818cf8;
            --primary-hover: #6366f1;
            --accent-color: #fbbf24;
            --glass-bg: rgba(15, 23, 42, 0.6);
            --glass-border: rgba(255, 255, 255, 0.05);
        }

        * {
            box-sizing: border-box;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: var(--nav-bg);
            color: var(--nav-text);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-md);
            padding: 15px 30px;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo a {
            color: var(--nav-text);
            text-decoration: none;
        }

        .logo span {
            color: var(--primary-color);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links a {
            color: var(--nav-text);
            text-decoration: none;
            font-weight: 500;
            opacity: 0.85;
            transition: opacity 0.2s, color 0.2s;
        }

        .nav-links a:hover {
            opacity: 1;
            color: var(--primary-color);
        }

        .theme-toggle-btn {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 20px;
            font-family: inherit;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .theme-toggle-btn:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        .container {
            max-width: 1200px;
            width: 100%;
            margin: 30px auto;
            padding: 0 20px;
            flex: 1;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            font-size: 15px;
            font-weight: 600;
            border-radius: var(--radius-md);
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: #fff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--card-border);
            color: var(--text-color);
        }

        .btn-outline:hover {
            background-color: var(--card-bg);
            border-color: var(--text-muted);
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
        }

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.15);
            color: var(--accent-color);
        }

        .badge-sukses {
            background-color: rgba(16, 185, 129, 0.15);
            color: var(--success-color);
        }

        .badge-gagal {
            background-color: rgba(239, 68, 68, 0.15);
            color: var(--danger-color);
        }
    </style>
</head>
<body>

<header>
    <div class="nav-container">
        <div class="logo">
            <a href="<?php echo $base_url; ?>">FUN<span>topup</span></a>
        </div>
        <nav class="nav-links">
            <a href="<?php echo $base_url; ?>">Beranda</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo $base_url; ?>user/riwayat.php">Riwayat</a>
                <span style="color: var(--accent-color); font-weight: 600; font-size: 15px;">
                    Saldo: Rp <?php echo number_format($_SESSION['saldo'] ?? 100000, 0, ',', '.'); ?>
                </span>
                <span style="font-weight: 500; font-size: 15px;">Halo, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                <a href="<?php echo $base_url; ?>user/auth/logout.php" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px;">Logout</a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>user/riwayat.php">Riwayat (Demo)</a>
                <a href="<?php echo $base_url; ?>user/auth/login.php">Login</a>
                <a href="<?php echo $base_url; ?>user/auth/register.php" class="btn btn-primary" style="padding: 8px 16px; font-size: 14px; color: white;">Daftar</a>
            <?php endif; ?>

            <button class="theme-toggle-btn" id="theme-toggle">
                <span id="theme-icon">🌙</span> <span id="theme-text">Dark</span>
            </button>
        </nav>
    </div>
</header>
<main style="flex: 1; display: flex; flex-direction: column;">
