<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= htmlspecialchars($title ?? 'Admin RUANGMP') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASEURL ?>/../css/admin.css">
</head>
<body>
<div class="app">
  <aside class="side" id="side">
    <div class="brand">RUANGMP</div>
    <nav class="nav">
      <a class="on"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/></svg>Dasbor</a>
      <a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5a2 2 0 0 1 2-2h12v16H6a2 2 0 0 0-2 2z"/><path d="M4 21V5M9 7h5"/></svg>Buku</a>
      <a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 7h11l-2 9H8zM7 7 6 3H3M10 20h.01M16 20h.01"/></svg>Peminjaman</a>
      <a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3.5"/><path d="M3 20c0-3.5 3-6 6-6s6 2.5 6 6M16 5a3.5 3.5 0 0 1 0 7M21 20c0-2.5-1.5-4.5-4-5.3"/></svg>Anggota</a>
      <a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 3v9h9"/></svg>Statistik</a>
      <a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 12 12 20 3 11V3h8z"/><circle cx="7.5" cy="7.5" r="1"/></svg>Denda</a>
    </nav>
    <small>Admin RUANGMP</small>
  </aside>
  <div class="drawer-scrim" id="dscrim"></div>

  <main class="main">
    <div class="top">
      <button class="ibtn menu" id="menu" aria-label="Buka menu"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
      <div class="ttl"><h1 id="ttl">Dasboard</h1><p id="count">Ringkasan perpustakaan</p></div>

      
      <div class="tools" style="display:flex;align-items:center;gap:10px;">
        <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 12px;border-radius:999px;background:var(--card);border:1px solid var(--line);font-size:13px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20c0-4 4-6 8-6s8 2 8 6"/>
          </svg>
          <span style="font-weight:500;">
            <?= htmlspecialchars($_SESSION['user']['full_name'] ?? $_SESSION['user']['username'] ?? 'Administrator') ?>
          </span>
          <span style="background:rgba(37,99,235,0.18);color:#60a5fa;font-size:11px;font-weight:600;padding:2px 8px;border-radius:999px;text-transform:capitalize;">
            <?= htmlspecialchars($_SESSION['user']['role'] ?? 'admin') ?>
          </span>
        </div>

        <a href="<?= BASEURL ?>/auth/logout" class="btn" style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;font-size:13px;text-decoration:none;border-radius:10px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          Logout
        </a>
      </div>
    </div>

    <!-- Tampilan Tab Buku -->
    <div id="v-buku" style="display:none">
      <div class="bar">
        <div class="tabs" id="tabs">
          <button class="on" data-f="semua">Semua buku</button>
          <button data-f="tersedia">Tersedia</button>
          <button data-f="habis">Habis</button>
        </div>
        <div class="acts">
          <input class="search" id="q" type="search" placeholder="Cari judul atau penulis" aria-label="Cari buku">
          <button class="btn" id="add">+ Tambah buku</button>
        </div>
      </div>

      <div class="cols thead"><span>ID</span><span>Judul</span><span>Penulis</span><span>Kategori</span><span>Tahun</span><span>Stok</span><span>Status</span><span>Aksi</span></div>
      <div id="list"></div>

      <div class="foot"><span id="info"></span><div class="pg" id="pg"></div></div>
    </div>

    <!-- Tampilan Tab Dasbor -->
    <div id="v-x"></div>
  </main>
</div>

<div class="toast" id="toast" role="status"></div>

<script>
// 1. Data dari Controller PHP
const DB_BOOKS = <?= json_encode($books ?? []) ?>;
const DB_USERS = <?= json_encode($users ?? []) ?>;
const DB_TRANSACTIONS = <?= json_encode($transactions ?? []) ?>;
const BASEURL = <?= json_encode(BASEURL) ?>;

// 2. Mapping data buku dari database
let books = DB_BOOKS.map(b => ({
  id: b.kode_buku || ('BKU' + String(b.id).padStart(3, '0')),
  db_id: b.id,
  judul: b.judul || '-',
  penulis: b.penulis || '-',
  kat: b.nama_kategori || 'Umum',
  thn: b.tahun_terbit || '-',
  stk: parseInt(b.stok_tersedia ?? 0, 10)
}));

let filter = 'semua', q = '', page = 1, perPage = 8;

// 3. Render Tab Dasbor
function renderDasbor() {
  document.getElementById('ttl').textContent = 'Dasboard';
  document.getElementById('count').textContent = 'Ringkasan perpustakaan';
  document.getElementById('v-buku').style.display = 'none';
  const vx = document.getElementById('v-x');
  vx.style.display = 'block';

  const totalBuku = books.length;
  const totalAnggota = DB_USERS.filter(u => u.role === 'anggota').length;
  const sedangDipinjam = DB_TRANSACTIONS.filter(t => ['dipinjam', 'terlambat'].includes(t.status)).length;
  const totalDenda = DB_TRANSACTIONS.reduce((sum, t) => sum + parseFloat(t.denda || 0), 0);

  const recentRows = DB_TRANSACTIONS.slice(0, 5).map(t => {
    let statusHtml = '<span style="color:#3b82f6">● Dipinjam</span>';
    if (t.status === 'terlambat') statusHtml = '<span style="color:#ef4444">● Terlambat</span>';
    else if (t.status === 'dikembalikan') statusHtml = '<span style="color:#22c55e">● Dikembalikan</span>';

    return `
      <div class="cols row" style="grid-template-columns:2fr 1.4fr 1fr 1fr 1.2fr 1fr;">
        <span><b>${t.judul || '-'}</b></span>
        <span>${t.nama_peminjam || '-'}</span>
        <span>${t.tgl_pinjam || '-'}</span>
        <span>${t.tgl_jatuh_tempo || '-'}</span>
        <span>${statusHtml}</span>
        <span>${['dipinjam', 'terlambat'].includes(t.status) ? `<a href="${BASEURL}/transactions" class="btn" style="padding:5px 12px;font-size:12px;text-decoration:none;">Kembalikan</a>` : ''}</span>
      </div>
    `;
  }).join('') || '<div style="padding:20px;text-align:center;color:var(--mute);background:var(--card);border-radius:12px;">Belum ada data peminjaman terbaru.</div>';

  vx.innerHTML = `
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:28px;">
      <div style="background:var(--card);padding:22px;border-radius:16px;border:1px solid var(--line);">
        <div style="font-size:28px;font-weight:600;margin-bottom:4px;">${totalBuku}</div>
        <div style="color:var(--mute);font-size:13px;">Judul buku</div>
      </div>
      <div style="background:var(--card);padding:22px;border-radius:16px;border:1px solid var(--line);">
        <div style="font-size:28px;font-weight:600;margin-bottom:4px;">${totalAnggota}</div>
        <div style="color:var(--mute);font-size:13px;">Anggota</div>
      </div>
      <div style="background:var(--card);padding:22px;border-radius:16px;border:1px solid var(--line);">
        <div style="font-size:28px;font-weight:600;margin-bottom:4px;">${sedangDipinjam}</div>
        <div style="color:var(--mute);font-size:13px;">Sedang dipinjam</div>
      </div>
      <div style="background:var(--card);padding:22px;border-radius:16px;border:1px solid var(--line);">
        <div style="font-size:28px;font-weight:600;margin-bottom:4px;">Rp${totalDenda.toLocaleString('id-ID')}</div>
        <div style="color:var(--mute);font-size:13px;">Denda belum lunas</div>
      </div>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
      <h3 style="font-size:16px;font-weight:600;">Peminjaman terbaru</h3>
      <div style="display:flex;gap:8px;">
        <a href="${BASEURL}/items" class="btn ghost" style="padding:6px 12px;font-size:12px;text-decoration:none;">Kelola Buku</a>
        <a href="${BASEURL}/categories" class="btn ghost" style="padding:6px 12px;font-size:12px;text-decoration:none;">Kategori</a>
        <a href="${BASEURL}/logs" class="btn ghost" style="padding:6px 12px;font-size:12px;text-decoration:none;">Log Aktivitas</a>
      </div>
    </div>

    <div class="cols thead" style="grid-template-columns:2fr 1.4fr 1fr 1fr 1.2fr 1fr;">
      <span>Buku</span><span>Peminjam</span><span>Dipinjam</span><span>Jatuh tempo</span><span>Status</span><span>Aksi</span>
    </div>
    <div>${recentRows}</div>
  `;
}

// 4. Render Tab Buku
function renderBooks() {
  document.getElementById('ttl').textContent = 'Buku';
  document.getElementById('v-x').style.display = 'none';
  document.getElementById('v-buku').style.display = 'block';

  const filtered = books.filter(b => {
    const matchF = filter === 'semua' || (filter === 'tersedia' && b.stk > 0) || (filter === 'habis' && b.stk === 0);
    const matchQ = b.judul.toLowerCase().includes(q) || b.penulis.toLowerCase().includes(q);
    return matchF && matchQ;
  });

  const totalPages = Math.max(1, Math.ceil(filtered.length / perPage));
  if (page > totalPages) page = totalPages;

  document.getElementById('count').textContent = `${filtered.length} judul ditemukan`;
  const start = (page - 1) * perPage;
  const slice = filtered.slice(start, start + perPage);

  document.getElementById('list').innerHTML = slice.map(b => `
    <div class="cols row">
      <span>${b.id}</span>
      <span><b>${b.judul}</b></span>
      <span>${b.penulis}</span>
      <span>${b.kat}</span>
      <span>${b.thn}</span>
      <span>${b.stk}</span>
      <span>${b.stk > 0 ? '<b style="color:#22c55e">Tersedia</b>' : '<b style="color:#ef4444">Habis</b>'}</span>
      <span>
        <a href="${BASEURL}/items/edit/${b.db_id}" class="btn ghost" style="padding:4px 10px;font-size:12px;text-decoration:none;">Edit</a>
      </span>
    </div>
  `).join('') || '<div style="padding:20px;text-align:center;color:var(--mute);">Belum ada data buku.</div>';

  document.getElementById('info').textContent = `Menampilkan ${slice.length} dari ${filtered.length} buku`;

  let pgHtml = '';
  for (let i = 1; i <= totalPages; i++) {
    pgHtml += `<button class="${i === page ? 'on' : ''}" onclick="goPage(${i})">${i}</button>`;
  }
  document.getElementById('pg').innerHTML = pgHtml;
}

function goPage(p) {
  page = p;
  renderBooks();
}

// 5. Navigasi Sidebar
const navLinks = document.querySelectorAll('.nav a');
navLinks.forEach((link, idx) => {
  link.style.cursor = 'pointer';
  link.addEventListener('click', () => {
    navLinks.forEach(n => n.classList.remove('on'));
    link.classList.add('on');
    document.getElementById('side').classList.remove('open');

    if (idx === 0) renderDasbor();
    else if (idx === 1) renderBooks();
    else if (idx === 2) window.location.href = `${BASEURL}/transactions`;
    else if (idx === 3) window.location.href = `${BASEURL}/users`;
    else if (idx === 4) window.location.href = `${BASEURL}/reports`;
    else if (idx === 5) window.location.href = `${BASEURL}/transactions`;
  });
});

// 6. Toggle Sidebar Mobile
document.getElementById('menu')?.addEventListener('click', () => {
  document.getElementById('side').classList.toggle('open');
});
document.getElementById('dscrim')?.addEventListener('click', () => {
  document.getElementById('side').classList.remove('open');
});

// 7. Filter Tab & Pencarian Buku
document.querySelectorAll('#tabs button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('#tabs button').forEach(b => b.classList.remove('on'));
    btn.classList.add('on');
    filter = btn.dataset.f;
    page = 1;
    renderBooks();
  });
});

document.getElementById('q')?.addEventListener('input', e => {
  q = e.target.value.toLowerCase().trim();
  page = 1;
  renderBooks();
});

document.getElementById('add')?.addEventListener('click', () => {
  window.location.href = `${BASEURL}/items/create`;
});

// Jalankan tampilan Dasbor pertama kali saat halaman terbuka
renderDasbor();
</script>
</body>
</html>