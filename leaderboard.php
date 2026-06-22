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
function getVipLevel($spent) {
    if ($spent >= 5000000) return 'Legendary VIP';
    if ($spent >= 2500000) return 'Mythic VIP';
    if ($spent >= 1000000) return 'Platinum VIP';
    if ($spent >= 500000) return 'Gold VIP';
    return 'Silver Member';
}

function getVipColor($spent) {
    if ($spent >= 5000000) return '#f59e0b'; // Gold
    if ($spent >= 2500000) return '#a855f7'; // Purple
    if ($spent >= 1000000) return '#3b82f6'; // Blue
    if ($spent >= 500000) return '#10b981'; // Green
    return '#64748b'; // Gray
}
?>

<style>
    .leaderboard-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .leaderboard-table th {
        text-align: left;
        padding: 14px 16px;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 13.5px;
        text-transform: uppercase;
        border-bottom: 2px solid var(--card-border);
    }

    .leaderboard-table td {
        padding: 16px;
        font-size: 14.5px;
        border-bottom: 1px solid var(--card-border);
        vertical-align: middle;
    }

    .leaderboard-row {
        transition: background-color 0.2s;
    }

    .leaderboard-row:hover {
        background-color: rgba(252, 213, 53, 0.03);
    }

    .rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        color: #0b0e11;
    }

    .rank-1 {
        background-color: #fcd535;
        box-shadow: 0 0 10px rgba(252, 213, 53, 0.4);
    }

    .rank-2 {
        background-color: #c0c0c0;
        box-shadow: 0 0 10px rgba(192, 192, 192, 0.3);
    }

    .rank-3 {
        background-color: #cd7f32;
        box-shadow: 0 0 10px rgba(205, 127, 50, 0.3);
    }

    .rank-other {
        background-color: var(--card-border);
        color: var(--text-color);
    }

    .vip-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        color: white;
    }
</style>

<div class="container" style="max-width: 800px; margin-top: 40px; margin-bottom: 60px;">
    
    <div style="text-align: center; margin-bottom: 35px;">
        <span style="font-size: 44px; display: block; margin-bottom: 10px;">🏆</span>
        <h2 style="margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;"><?php echo __('leaderboard_title'); ?></h2>
        <p style="color: var(--text-muted); font-size: 14.5px; margin-top: 8px; line-height: 1.6; max-width: 500px; margin-left: auto; margin-right: auto;">
            <?php echo __('leaderboard_desc'); ?>
        </p>
    </div>

    <!-- Podiums for Top 3 Spenders -->
    <div style="display: flex; justify-content: center; align-items: flex-end; gap: 15px; margin-bottom: 40px; padding: 0 10px; flex-wrap: wrap;">
        <!-- Rank 2 -->
        <?php if (isset($leaderboard[1])): ?>
            <div style="flex: 1; min-width: 140px; max-width: 180px; display: flex; flex-direction: column; align-items: center; order: 1;">
                <span style="font-size: 24px; margin-bottom: 4px;">🥈</span>
                <span style="font-weight: 700; font-size: 14px; text-align: center; width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($leaderboard[1]['username']); ?></span>
                <span style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">Rp <?php echo number_format($leaderboard[1]['total_spent'], 0, ',', '.'); ?></span>
                <div style="background-color: var(--card-bg); border: 2px solid var(--card-border); border-bottom: none; border-radius: 8px 8px 0 0; width: 100%; height: 90px; display: flex; align-items: center; justify-content: center;">
                    <strong style="font-size: 32px; color: var(--text-muted);">2</strong>
                </div>
            </div>
        <?php endif; ?>

        <!-- Rank 1 -->
        <?php if (isset($leaderboard[0])): ?>
            <div style="flex: 1; min-width: 150px; max-width: 200px; display: flex; flex-direction: column; align-items: center; order: 2; transform: translateY(-10px);">
                <span style="font-size: 32px; margin-bottom: 2px; filter: drop-shadow(0 0 5px rgba(252,213,53,0.5));">👑</span>
                <span style="font-weight: 800; font-size: 16px; color: var(--primary-color); text-align: center; width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($leaderboard[0]['username']); ?></span>
                <span style="font-size: 13.5px; font-weight: 700; color: #eaecef; margin-bottom: 12px;">Rp <?php echo number_format($leaderboard[0]['total_spent'], 0, ',', '.'); ?></span>
                <div style="background: linear-gradient(185deg, var(--card-bg) 0%, rgba(252, 213, 53, 0.05) 100%); border: 2px solid var(--primary-color); border-bottom: none; border-radius: 12px 12px 0 0; width: 100%; height: 120px; display: flex; align-items: center; justify-content: center; box-shadow: 0 -4px 15px rgba(252, 213, 53, 0.15);">
                    <strong style="font-size: 42px; color: var(--primary-color); text-shadow: 0 0 10px rgba(252,213,53,0.3);">1</strong>
                </div>
            </div>
        <?php endif; ?>

        <!-- Rank 3 -->
        <?php if (isset($leaderboard[2])): ?>
            <div style="flex: 1; min-width: 140px; max-width: 180px; display: flex; flex-direction: column; align-items: center; order: 3;">
                <span style="font-size: 24px; margin-bottom: 4px;">🥉</span>
                <span style="font-weight: 700; font-size: 14px; text-align: center; width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($leaderboard[2]['username']); ?></span>
                <span style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">Rp <?php echo number_format($leaderboard[2]['total_spent'], 0, ',', '.'); ?></span>
                <div style="background-color: var(--card-bg); border: 2px solid var(--card-border); border-bottom: none; border-radius: 8px 8px 0 0; width: 100%; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <strong style="font-size: 28px; color: #cd7f32;">3</strong>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Leaderboard Table List -->
    <div class="card" style="box-shadow: var(--shadow-md); border-radius: var(--radius-xl); padding: 10px; overflow-x: auto;">
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th style="width: 100px; text-align: center;"><?php echo __('rank'); ?></th>
                    <th><?php echo __('user'); ?></th>
                    <th><?php echo __('level'); ?></th>
                    <th style="text-align: right;"><?php echo __('total_spent'); ?></th>
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
                        <td style="text-align: center; font-weight: bold;">
                            <span class="rank-badge <?php echo $rankClass; ?>"><?php echo $rank; ?></span>
                        </td>
                        <td>
                            <strong style="color: var(--text-color); font-size: 15px;">
                                <?php echo htmlspecialchars($row['username']); ?>
                            </strong>
                        </td>
                        <td>
                            <span class="vip-badge" style="background-color: <?php echo $vipColor; ?>;">
                                <?php echo $vip; ?>
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: 700; color: var(--primary-color);">
                            Rp <?php echo number_format($row['total_spent'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
