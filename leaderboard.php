<?php
require_once 'config/db.php';
require_once 'includes/header.php';

$leaderboard = [];
$db_loaded = false;

if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->query("
            SELECT u.username, SUM(t.nominal_transfer) as total_spent
            FROM transaksi t
            JOIN users u ON t.user_id = u.id
            WHERE t.status = 'sukses'
            GROUP BY t.user_id
            ORDER BY total_spent DESC
            LIMIT 10
        ");
        $leaderboard = $stmt->fetchAll();
        if (count($leaderboard) > 0) {
            $db_loaded = true;
        }
    } catch (PDOException $e) {
        $db_loaded = false;
    }
}

// Fallback Mock Data if DB empty or offline
if (!$db_loaded) {
    $leaderboard = [
        ['username' => 'SultanGamer99', 'total_spent' => 5450000],
        ['username' => 'RajaTopup_ML', 'total_spent' => 3820000],
        ['username' => 'WibuSultan', 'total_spent' => 2900000],
        ['username' => 'LordGamer_21', 'total_spent' => 1750000],
        ['username' => 'NontonAjaDeh', 'total_spent' => 1200000],
        ['username' => 'PlayerGratisan', 'total_spent' => 950000],
        ['username' => 'CobaCobaTopup', 'total_spent' => 450000]
    ];
}

// Function to map spendings to VIP titles
if (!function_exists('getVipLevel')) {
    function getVipLevel($spent) {
        if ($spent >= 5000000) return 'Legendary VIP';
        if ($spent >= 2500000) return 'Mythic VIP';
        if ($spent >= 1000000) return 'Platinum VIP';
        if ($spent >= 500000) return 'Gold VIP';
        return 'Silver Member';
    }
}

if (!function_exists('getVipColor')) {
    function getVipColor($spent) {
        if ($spent >= 5000000) return '#f59e0b'; // Gold
        if ($spent >= 2500000) return '#a855f7'; // Purple
        if ($spent >= 1000000) return '#3b82f6'; // Blue
        if ($spent >= 500000) return '#10b981'; // Green
        return '#64748b'; // Gray
    }
}
?>

<div class="container leaderboard-container">
    
    <div class="leaderboard-header">
        <span class="podium-medal-crown" style="display: block;">🏆</span>
        <h2 class="leaderboard-title"><?php echo __('leaderboard_title'); ?></h2>
        <p class="leaderboard-desc">
            <?php echo __('leaderboard_desc'); ?>
        </p>
    </div>

    <!-- Podiums for Top 3 Spenders -->
    <div class="podium-container">
        <!-- Rank 2 -->
        <?php if (isset($leaderboard[1])): ?>
            <div class="podium-rank-2-3">
                <span class="podium-medal">🥈</span>
                <span class="podium-username"><?php echo htmlspecialchars($leaderboard[1]['username']); ?></span>
                <span class="podium-spent">Rp <?php echo number_format($leaderboard[1]['total_spent'], 0, ',', '.'); ?></span>
                <div class="podium-box-2">
                    <strong class="podium-number">2</strong>
                </div>
            </div>
        <?php endif; ?>

        <!-- Rank 1 -->
        <?php if (isset($leaderboard[0])): ?>
            <div class="podium-rank-1">
                <span class="podium-medal-crown">👑</span>
                <span class="podium-username-1"><?php echo htmlspecialchars($leaderboard[0]['username']); ?></span>
                <span class="podium-spent-1">Rp <?php echo number_format($leaderboard[0]['total_spent'], 0, ',', '.'); ?></span>
                <div class="podium-box-1">
                    <strong class="podium-number-1">1</strong>
                </div>
            </div>
        <?php endif; ?>

        <!-- Rank 3 -->
        <?php if (isset($leaderboard[2])): ?>
            <div class="podium-rank-2-3" style="order: 3;">
                <span class="podium-medal">🥉</span>
                <span class="podium-username"><?php echo htmlspecialchars($leaderboard[2]['username']); ?></span>
                <span class="podium-spent">Rp <?php echo number_format($leaderboard[2]['total_spent'], 0, ',', '.'); ?></span>
                <div class="podium-box-3">
                    <strong class="podium-number-3">3</strong>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Leaderboard Table List -->
    <div class="card table-wrapper-card">
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th class="col-rank-header"><?php echo __('rank'); ?></th>
                    <th><?php echo __('user'); ?></th>
                    <th><?php echo __('level'); ?></th>
                    <th class="col-spent-header"><?php echo __('total_spent'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaderboard as $index => $row): 
                    $rank = $index + 1;
                    $rankClass = 'rank-other';
                    if ($rank === 1) $rankClass = 'rank-1';
                    else if ($rank === 2) $rankClass = 'rank-2';
                    else if ($rank === 3) $rankClass = 'rank-3';
                    
                    $vip = getVipLevel($row['total_spent']);
                    $vipColor = getVipColor($row['total_spent']);
                ?>
                    <tr class="leaderboard-row">
                        <td class="col-rank">
                            <span class="rank-badge <?php echo $rankClass; ?>"><?php echo $rank; ?></span>
                        </td>
                        <td>
                            <strong class="leaderboard-username">
                                <?php echo htmlspecialchars($row['username']); ?>
                            </strong>
                        </td>
                        <td>
                            <span class="vip-badge" style="background-color: <?php echo $vipColor; ?>;">
                                <?php echo $vip; ?>
                            </span>
                        </td>
                        <td class="col-spent">
                            Rp <?php echo number_format($row['total_spent'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
