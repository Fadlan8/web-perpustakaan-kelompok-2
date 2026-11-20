<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Perpustakaan digital — temukan buku, komik, dan bacaan favoritmu.">
<title>RuangMP — Perpustakaan Digital</title>
<link rel="stylesheet" href="<?= BASEURL ?>/../css/style.css">
</head>
<body>
<div class="topline"></div>
<header>
 <div class="head">
    <a class="brand" href="<?= BASEURL ?>/dashboard">
        <div class="logo">📖</div>
        <div>
            <b>RUANGMP</b>
            <small>Perpustakaan Digital</small>
        </div>
    </a>
    <form class="search" id="searchForm" onsubmit="return false;">
        <input id="searchInput" type="search" placeholder="🔎  Cari judul buku, penulis..." aria-label="Cari buku">
        <button type="submit" aria-label="Cari">⌕</button>
    </form>

    <div class="action">
        <div class="user-badge-box">
            <span class="user-icon">👤</span>
            <span class="user-name"><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? $_SESSION['user']['username'] ?? 'Administrator'); ?></span>
            <span class="role-pill"><?= htmlspecialchars($_SESSION['user']['role'] ?? 'admin'); ?></span>
        </div>

    <a href="<?= rtrim(BASEURL, '/') ?>/auth/logout" class="btn-logout">↪ Logout</a>
</div>
</div>
 <nav class="nav">
    <a href="#koleksi" onclick="filterNav('Semua')">Semua Koleksi</a>
    <?php if (!empty($data['categories'])) : ?>
        <?php foreach ($data['categories'] as $kat) : ?>
            <a href="#koleksi" onclick="filterNav('<?= htmlspecialchars($kat['nama_kategori']) ?>')">
                <?= htmlspecialchars($kat['nama_kategori']) ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</nav>
</header>

<main>
    <section class="hero">
        <div>
            <h1>Temukan bacaan favoritmu di RuangMP</h1>
            <p>Jelajahi ribuan inspirasi, pengetahuan, dan cerita menarik. Semua berawal dari satu halaman.</p>
            <button type="button" onclick="document.getElementById('koleksi').scrollIntoView({behavior:'smooth'})">Jelajahi Koleksi →</button>
            <div class="hero-note">📚 Baca kapan saja • Belajar tanpa batas</div>
        </div>
    </section>

    <div class="section-head">
        <div><div class="eyebrow">JELAJAHI KOLEKSI</div>
        <h2>Kategori Buku</h2>
    </div>
</div>
<div class="categories" id="categories">
    <button type="button" class="chip active" data-cat="Semua">Semua</button>
    <?php if (!empty($data['categories'])) : ?>
        <?php foreach ($data['categories'] as $kat) : ?>
            <button type="button" class="chip active" data-cat="<?= htmlspecialchars($kat['nama_kategori']) ?>">
                <?= htmlspecialchars($kat['nama_kategori']) ?>
            </button>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<section id="koleksi">
    <div class="section-head">
        <div>
            <div class="eyebrow">RILIS TERBARU</div>
            <h2>📘 Buku Pilihan</h2>
        </div>
        <button type="button" class="see" onclick="resetFilter()">Lihat semua →</button>
    </div>

    <div class="books" id="bookGrid">
        <?php if (!empty($data['books'])) : ?>
            <?php foreach ($data['books'] as $buku) : ?>
                <div class="book-card"
                    data-cat="<?= htmlspecialchars($buku['nama_kategori'] ?? 'Umum') ?>"
                    data-title="<?= htmlspecialchars(strolower($buku['judul'])) ?>" 
                    data-author="<?= htmlspecialchars(strolower($buku['penulis'] ?? '')) ?>"
                    onclick="openBookDetail(
                        '<?= htmlspecialchars(addslashes($buku['judul'])) ?>',
                        '<?= htmlspecialchars(addslashes($buku['penulis'] ?? 'Anonim')) ?>',
                        '<?= htmlspecialchars(addslashes($buku['nama_kategori'] ?? 'Umum')) ?>',
                        '<?= htmlspecialchars(addslashes($buku['deskripsi'] ?? 'Tidak ada deskripsi buku')) ?>',
                        '<?= (int)($buku['stok_tersedia'] ?? 0) ?>')">
                    <div class="book-cover">
                        <?php if (!empty($buku['cover']) && file_exists(ROOT_PATH . '/images/' . $buku['cover'])) : ?>
                            <img src="<?= BASEURL ?>/../images/<?= htmlspecialchars($buku['cover']) ?>" alt="<?= htmlspecialchars($buku['judul']) ?>">
                        <?php else : ?>
                            <div class="cover-placeholder">📘</div>
                        <?php endif; ?>
                    </div>
                    <div class="book-info">
                        <span class="book-cat"><?= htmlspecialchars($buku['nsms_kategori'] ?? 'Umum') ?></span>
                        <h3 class="book-title"><?= htmlspecialchars($buku['judul']) ?></h3>
                        <p class="book-author"><?= htmlspecialchars($buku['penulis'] ?? '-') ?></p>
                        <small class="book-stock <?= ($buku['stok_tersedia'] ?? 0) > 0 ? 'available' : 'empty' ?>">
                            <?= ($buku['stok_tersedia'] ?? 0) > 0 ? 'Tersedia: ' . $buku['stok_tersedia'] . ' eks' : 'Stok Habis' ?>
                        </small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p style="color: #64748b; padding: 20px 0;">Belum ada koleksi buku yang tersedia.</p>
        <?php endif; ?>
    </div>
</section>
</main>

<footer class="footer">© 2026 RuangBaca — Perpustakaan Digital. Belajar dan bertumbuh bersama.</footer>
<div class="modal" id="detailModal" role="dialog" aria-modal="true">
    <div class="panel">
        <button type="button" class="close" onclick="closeLogin()">×</button>
        <div class="detail-cover">📚</div>
        <div class="eyebrow" id="detailCat"></div>
        <h2 id="detailTitle"></h2>
        <p id="detailAuthor" style="font-weight: 600; color: #334155;"></p>
        <p id="detailDesc" style="margin: 12px 0; line-height: 1.5;"></p>
        <p id="detailStock" style="font-size: 13px; color: #2563eb; font-weight: 600; margin-bottom: 16px"></p>
        <button type="button" class="primary" onclick="showToast('Silakan hubungi petugas perpustakaan untuk meminjam buku ini.')">Ajukan Peminjaman</button>
    </div>
</div>
<div class="toast" id="toast"></div>

<script>
    //filter kategori dan pencarian langsung dari data database
    const chips = document.querySelectorAll('.chip');
    const cards = document.querySelectorAll('.bok-card');
    const searchInput = document.getElementbyId('searchInput');

    function applyFilter(selectedCat, keyword = ''){
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-cat');
            const title = card.getAttribute('data-title');
            const author = card.getAttribute('data-author');

            const matchCat = (selectedCat === 'Semua' || cardCat === selectedCat);
            const matchText = (title.includes(keyword) || author.includes(keyword));

            card.style..display = (matchCat && matchText) ? '' : 'none' ;
        });
    }

    chips.forEach(chip => {
        chip.addEventListener('click' , () => {
            chips.forEach(c => c.classList.remove('active'));
            chips.classList.add('active');
            applyFilter(chip.getAttribute('data-cat'), searchInput.value.toLowercase().trim());
        });
    });
    function filterNav(cat) {
        chips.forEach(c => {
            c.classList.toggle('active', c.getAttribute('data-cat') === cat);
        });
        applyFilter(cat, searchInput.value.toLowerCase().trim());
    }

    function resetFilter() {
        searchInput.value = '';
        filterNav('Semua');
    }

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const activeChip = document.querySelector('.chip.active');
            const currentCat = activeChip ? activeChip.getAttribute('data-cat') : 'Semua';
            applyFilter(currentCat, searchInput.value.toLowerCase().trim());
        });
    }

    function openBookDetail(title, author, cat, desc, stock) {
        document.getElementById('detailTitle').textContent = title;
        document.getElementById('detailAuthor').textContent = 'Penulis: ' + author;
        document.getElementById('detailCat').textContent = cat;
        document.getElementById('detailDesc').textContent = desc;
        document.getElementById('detailStock').textContent = 'Sisa stok tersedia: ' + stock + ' eksemplar';
        document.getElementById('detailModal').classList.add('open');
    }

    function closeDetail() {
        document.getElementById('detailModal').classList.remove('open');
    }

    function showToast(msg) {
        const toast = document.getElementById('toast');
        toast.textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
    </script>
</body>
</html>