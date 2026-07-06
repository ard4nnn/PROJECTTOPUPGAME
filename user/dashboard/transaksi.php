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
$f_status  = $_GET['status']   ?? 'all';
$f_payment = $_GET['payment']  ?? 'all';
$date_from = $_GET['dari']     ?? '';
$date_to   = $_GET['sampai']   ?? '';
$search    = trim($_GET['cari'] ?? '');
$per_page  = max(1,(int)($_GET['per_page'] ?? 10));
$page      = max(1,(int)($_GET['page'] ?? 1));
$offset    = ($page-1)*$per_page;

$rows = [];
$total_rows = 0;
$total_pages = 1;
$payment_methods = [];

// Jika database online, ambil data dari MySQL
if ($db_connected && $pdo) {
    try {
        // Ambil opsi pembayaran
        $payment_methods = $pdo->query("SELECT * FROM metode_bayar ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);

        $where  = ["t.user_id = :uid"];
        $params = [':uid'=>$user_id];
        
        if ($date_from !== '') {
            $where[] = "DATE(t.created_at) >= :dari";
            $params[':dari'] = $date_from;
        }
        if ($date_to !== '') {
            $where[] = "DATE(t.created_at) <= :sampai";
            $params[':sampai'] = $date_to;
        }
        if ($f_status  !== 'all') { 
            // Map status agar sesuai DB (sukses, pending, gagal)
            $where[]="t.status = :status";   
            $params[':status'] = $f_status; 
        }
        if ($f_payment !== 'all') { 
            $where[]="t.metode_bayar_id = :pm";
            $params[':pm']=(int)$f_payment; 
        }
        if ($search !== '') { 
            $where[]="(t.id LIKE :s OR p.nama_produk LIKE :s OR t.id_game_user LIKE :s)"; 
            $params[':s']='%'.$search.'%'; 
        }
        $wsql = 'WHERE '.implode(' AND ',$where);

        // Hitung total baris
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM transaksi t
            LEFT JOIN produk p ON t.produk_id = p.id
            $wsql
        ");
        $stmt->execute($params);
        $total_rows  = (int)$stmt->fetchColumn();
        $total_pages = max(1,ceil($total_rows/$per_page));

        // Ambil data terpaginasi
        $stmt = $pdo->prepare("
            SELECT t.*, p.nama_produk, g.nama_game, m.nama as nama_metode
            FROM transaksi t
            LEFT JOIN produk p ON t.produk_id = p.id
            LEFT JOIN games g ON p.game_id = g.id
            LEFT JOIN metode_bayar m ON t.metode_bayar_id = m.id
            $wsql 
            ORDER BY t.created_at DESC 
            LIMIT :lim OFFSET :off
        ");
        foreach ($params as $k=>$v) {
            $stmt->bindValue($k,$v);
        }
        $stmt->bindValue(':lim',$per_page,PDO::PARAM_INT);
        $stmt->bindValue(':off',$offset,PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Fallback ke kosong jika error query
    }
}

function rupiah($n){return 'Rp '.number_format($n,0,',','.');}
function statusBadge($s){
    $s = strtolower($s);
    $m=[
        'pending'=>['Menunggu','pending'],
        'proses'=>['Dalam Proses','process'],
        'process'=>['Dalam Proses','process'],
        'sukses'=>['Sukses','success'],
        'success'=>['Sukses','success'],
        'gagal'=>['Gagal','failed'],
        'failed'=>['Gagal','failed']
    ];
    $d=$m[$s]??[ucfirst($s),'pending'];
    return "<span class=\"ft-badge {$d[1]}\">{$d[0]}</span>";
}
function pageUrl($p){
    $q=$_GET;
    $q['page']=$p;
    return '?'.http_build_query($q);
}

// Load header website
require_once '../../includes/header.php';
?>
<!-- Link dashboard styling -->
<link rel="stylesheet" href="dashboard.css">

<div class="ft-wrapper">
<?php include 'sidebar.php'; ?>
<main class="ft-main">

  <div class="ft-page-header">
    <h1 class="ft-page-title">Riwayat Transaksi</h1>
    <p class="ft-page-sub">
        Data transaksi selama periode yang dipilih.
        <?php if (!$db_connected): ?>
            <span style="color:#FBBF24; font-weight:800; margin-left:10px;">[🔌 Mode Demo Aktif]</span>
        <?php endif; ?>
    </p>
  </div>

  <div class="ft-filter-card">
    <form method="GET" id="filter-form">
      <div class="ft-filter-grid">
        <div>
          <label class="ft-label">Status</label>
          <select name="status" class="ft-select" id="filter-status">
            <option value="all"     <?=$f_status==='all'     ?'selected':''?>>Semua</option>
            <option value="pending" <?=$f_status==='pending' ?'selected':''?>>Menunggu</option>
            <option value="process" <?=$f_status==='process' ?'selected':''?>>Dalam Proses</option>
            <option value="success" <?=$f_status==='success' ?'selected':''?>>Sukses</option>
            <option value="failed"  <?=$f_status==='failed'  ?'selected':''?>>Gagal</option>
          </select>
        </div>
        <div>
          <label class="ft-label">Metode Pembayaran</label>
          <select name="payment" class="ft-select" id="filter-payment">
            <option value="all"  <?=$f_payment==='all'  ?'selected':''?>>Semua</option>
            <?php if ($db_connected && !empty($payment_methods)): ?>
              <?php foreach ($payment_methods as $pm): ?>
                <option value="<?=$pm['id']?>" <?=$f_payment==(string)$pm['id']?'selected':''?>><?=htmlspecialchars($pm['nama'])?></option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="dana"  <?=$f_payment==='dana' ?'selected':''?>>DANA</option>
              <option value="gopay" <?=$f_payment==='gopay'?'selected':''?>>GoPay</option>
              <option value="ovo"   <?=$f_payment==='ovo'  ?'selected':''?>>OVO</option>
              <option value="bca"   <?=$f_payment==='bca'  ?'selected':''?>>BCA</option>
            <?php endif; ?>
          </select>
        </div>
        <div>
          <label class="ft-label">Tanggal Mulai</label>
          <input type="date" name="dari" class="ft-input" id="filter-dari" value="<?=htmlspecialchars($date_from)?>">
        </div>
        <div>
          <label class="ft-label">Tanggal Akhir</label>
          <input type="date" name="sampai" class="ft-input" id="filter-sampai" value="<?=htmlspecialchars($date_to)?>">
        </div>
      </div>
      <div style="margin-top:0.75rem;">
        <label class="ft-label">Cari</label>
        <input type="text" name="cari" class="ft-input" id="filter-cari" style="max-width:400px;"
          placeholder="No. Invoice, Item, User Input" value="<?=htmlspecialchars($search)?>">
      </div>
      <div class="ft-filter-actions">
        <button type="submit" class="ft-btn ft-btn-primary">Cari</button>
        <a href="transaksi.php" class="ft-btn ft-btn-secondary">Reset</a>
        <select name="per_page" class="ft-select" id="filter-per-page" style="width:auto;" onchange="this.form.submit()">
          <?php foreach([10,25,50,100] as $n): ?>
            <option value="<?=$n?>" <?=$per_page==$n?'selected':''?>><?=$n?> Entri</option>
          <?php endforeach; ?>
        </select>
      </div>
    </form>
  </div>

  <div class="ft-table-wrap">
    <table class="ft-table">
      <thead>
        <tr>
          <th>Nomor Invoice</th><th>Item</th><th>User Input</th>
          <th>Harga</th><th>Metode</th><th>Tanggal</th><th>Status</th>
        </tr>
      </thead>
      <tbody id="transaksi-tbody">
        <?php if($db_connected && !empty($rows)): foreach($rows as $t): ?>
          <tr>
            <td><code style="color:#FBBF24;font-size:11px;">#<?=htmlspecialchars($t['id'])?></code></td>
            <td><?=htmlspecialchars($t['nama_produk']??$t['game_slug']??'-')?></td>
            <td><?=htmlspecialchars($t['id_game_user']??'-')?></td>
            <td><?=rupiah($t['nominal_transfer'])?></td>
            <td style="color:#888;"><?=strtoupper(htmlspecialchars($t['nama_metode']??'-'))?></td>
            <td style="color:#888;"><?=date('d/m/Y H:i',strtotime($t['created_at']))?></td>
            <td><?=statusBadge($t['status'])?></td>
          </tr>
        <?php endforeach; else: ?>
          <?php if ($db_connected): ?>
            <tr><td colspan="7">
              <div class="ft-empty">
                <div class="ft-empty-icon">📊</div>
                <p class="ft-empty-title">Data tidak ditemukan!</p>
                <p class="ft-empty-sub">Tidak ada transaksi sesuai filter.</p>
              </div>
            </td></tr>
          <?php endif; ?>
        <?php endif; ?>
      </tbody>
    </table>
    
    <div class="ft-pagination" id="pagination-box" style="display: <?= ($db_connected && $total_rows > 0) ? 'flex' : 'none' ?>;">
      <span id="pagination-info">Menampilkan <strong><?=number_format($offset+1)?></strong> sampai <strong><?=number_format(min($offset+$per_page,$total_rows))?></strong> dari <strong><?=number_format($total_rows)?></strong> hasil</span>
      <div style="display:flex;gap:0.4rem;" id="pagination-buttons">
        <?php if($page>1): ?>
          <a href="<?=pageUrl($page-1)?>" class="ft-btn ft-btn-secondary">← Sebelumnya</a>
        <?php else: ?>
          <span class="ft-btn ft-btn-secondary" style="opacity:0.4;cursor:default;">← Sebelumnya</span>
        <?php endif; ?>
        
        <?php if($page<$total_pages): ?>
          <a href="<?=pageUrl($page+1)?>" class="ft-btn ft-btn-secondary">Selanjutnya →</a>
        <?php else: ?>
          <span class="ft-btn ft-btn-secondary" style="opacity:0.4;cursor:default;">Selanjutnya →</span>
        <?php endif; ?>
      </div>
    </div>
  </div>

</main>
</div>

<!-- Script pemrosesan client-side jika MySQL offline -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dbConnected = <?= ($db_connected && $pdo) ? 'true' : 'false'; ?>;
    if (!dbConnected) {
        const orderHistory = JSON.parse(localStorage.getItem('order_history')) || [];
        
        // Baca filter dari URL query parameters
        const params = new URLSearchParams(window.location.search);
        const f_status = params.get('status') || 'all';
        const f_payment = params.get('payment') || 'all';
        const date_from = params.get('dari') || '';
        const date_to = params.get('sampai') || '';
        const search = (params.get('cari') || '').trim().toLowerCase();
        const per_page = parseInt(params.get('per_page')) || 10;
        const page = parseInt(params.get('page')) || 1;
        const offset = (page - 1) * per_page;
        
        // Filter data orderHistory
        let filtered = orderHistory.filter(item => {
            // Filter Status
            if (f_status !== 'all') {
                const itemStatus = (item.status || 'pending').toLowerCase();
                let mapped = 'pending';
                if (itemStatus === 'success' || itemStatus === 'sukses') mapped = 'success';
                else if (itemStatus === 'process' || itemStatus === 'proses') mapped = 'process';
                else if (itemStatus === 'failed' || itemStatus === 'gagal') mapped = 'failed';
                
                if (mapped !== f_status) return false;
            }
            
            // Filter Metode Pembayaran
            if (f_payment !== 'all') {
                const itemPayment = (item.payment || '').toLowerCase();
                if (!itemPayment.includes(f_payment.toLowerCase())) return false;
            }
            
            // Filter Rentang Tanggal (dari & sampai)
            if (date_from || date_to) {
                let itemDate = null;
                if (item.date) {
                    // Parsing format "DD/MM/YYYY, HH:MM:SS" ke Date object
                    const parts = item.date.split(',')[0].split(/[\/\-]/);
                    if (parts.length === 3) {
                        itemDate = new Date(parts[2], parts[1] - 1, parts[0]);
                    }
                }
                if (itemDate) {
                    if (date_from) {
                        const fromDate = new Date(date_from);
                        fromDate.setHours(0,0,0,0);
                        if (itemDate < fromDate) return false;
                    }
                    if (date_to) {
                        const toDate = new Date(date_to);
                        toDate.setHours(23,59,59,999);
                        if (itemDate > toDate) return false;
                    }
                } else {
                    return false; // Skip jika tanggal tidak dapat di-parse
                }
            }
            
            // Filter Pencarian
            if (search) {
                const idMatch = String(item.id).toLowerCase().includes(search);
                const productMatch = (item.product || '').toLowerCase().includes(search);
                const gameMatch = (item.game || '').toLowerCase().includes(search);
                const targetMatch = (item.targetId || '').toLowerCase().includes(search);
                if (!idMatch && !productMatch && !gameMatch && !targetMatch) return false;
            }
            
            return true;
        });
        
        const total_rows = filtered.length;
        const total_pages = Math.max(1, Math.ceil(total_rows / per_page));
        const paginated = filtered.slice(offset, offset + per_page);
        
        // Render tabel data
        const tbody = document.getElementById('transaksi-tbody');
        if (total_rows > 0) {
            tbody.innerHTML = '';
            
            paginated.forEach(order => {
                const tr = document.createElement('tr');
                
                const status = order.status ? order.status.toLowerCase() : 'pending';
                let badgeClass = 'pending';
                let badgeText = 'Menunggu';
                if (status === 'success' || status === 'sukses') {
                    badgeClass = 'success';
                    badgeText = 'Sukses';
                } else if (status === 'process' || status === 'proses') {
                    badgeClass = 'process';
                    badgeText = 'Dalam Proses';
                } else if (status === 'failed' || status === 'gagal') {
                    badgeClass = 'failed';
                    badgeText = 'Gagal';
                }
                
                const formattedPrice = 'Rp ' + parseFloat(order.price || 0).toLocaleString('id-ID');
                
                tr.innerHTML = `
                    <td><code style="color:#FBBF24;font-size:11px;">#${order.id} (Demo)</code></td>
                    <td>${order.product || order.game || '-'}</td>
                    <td>${order.targetId || '-'}</td>
                    <td>${formattedPrice}</td>
                    <td style="color:#888;">${(order.payment || '-').toUpperCase()}</td>
                    <td style="color:#888;">${order.date}</td>
                    <td><span class="ft-badge ${badgeClass}">${badgeText}</span></td>
                `;
                tbody.appendChild(tr);
            });
            
            // Tampilkan & Konfigurasi Box Paginasi
            const paginationBox = document.getElementById('pagination-box');
            paginationBox.style.display = 'flex';
            
            const startRange = offset + 1;
            const endRange = Math.min(offset + per_page, total_rows);
            document.getElementById('pagination-info').innerHTML = `Menampilkan <strong>${startRange.toLocaleString('id-ID')}</strong> sampai <strong>${endRange.toLocaleString('id-ID')}</strong> dari <strong>${total_rows.toLocaleString('id-ID')}</strong> hasil`;
            
            // Render Tombol Paginasi
            const btnBox = document.getElementById('pagination-buttons');
            let prevBtn = '';
            if (page > 1) {
                prevBtn = `<a href="${getPageUrl(page - 1)}" class="ft-btn ft-btn-secondary">← Sebelumnya</a>`;
            } else {
                prevBtn = `<span class="ft-btn ft-btn-secondary" style="opacity:0.4;cursor:default;">← Sebelumnya</span>`;
            }
            
            let nextBtn = '';
            if (page < total_pages) {
                nextBtn = `<a href="${getPageUrl(page + 1)}" class="ft-btn ft-btn-secondary">Selanjutnya →</a>`;
            } else {
                nextBtn = `<span class="ft-btn ft-btn-secondary" style="opacity:0.4;cursor:default;">Selanjutnya →</span>`;
            }
            
            btnBox.innerHTML = prevBtn + nextBtn;
        } else {
            // Render state kosong
            tbody.innerHTML = `
                <tr><td colspan="7">
                  <div class="ft-empty">
                    <div class="ft-empty-icon">📊</div>
                    <p class="ft-empty-title">Data tidak ditemukan!</p>
                    <p class="ft-empty-sub">Tidak ada transaksi demo sesuai filter.</p>
                  </div>
                </td></tr>
            `;
            document.getElementById('pagination-box').style.display = 'none';
        }
        
        function getPageUrl(p) {
            const q = new URLSearchParams(window.location.search);
            q.set('page', p);
            return '?' + q.toString();
        }
    }
});
</script>

<?php
require_once '../../includes/footer.php';
?>
