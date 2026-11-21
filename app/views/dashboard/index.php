<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="Perpustakaan digital — temukan buku, komik, dan bacaan favoritmu.">
    <title><?= htmlspecialchars($title ?? 'RUANGMP — Perpustakaan Digital') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASEURL ?>/../css/admin.css">
    <link rel="stylesheet" href="<?= BASEURL ?>/../css/style.css">
</head>
<body>
    <div class="user-shell">
        <!-- Topbar Seragam dengan Admin -->
        <header class="user-topbar">
            <a class="user-brand" href="<?= BASEURL ?>/dashboard">
                <div class="user-brand-logo">📖</div>
                <div class="user-brand-text">
                    <b>RUANGMP</b>
                    <small>Perpustakaan Digital</small>
                </div>
            </a>

            <form class="user-search" id="searchForm" onsubmit="return false;">
                <input id="searchInput" type="search" placeholder="Cari judul buku atau penulis..." aria-label="Cari buku">
            </form>

            <!-- Pojok Kanan Atas: Icon + Nama User + Role + Logout -->
            <div class="user-tools">
                <?php if (isset($_SESSION['user'])) : ?>
                    <div class="user-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 20c0-4 4-6 8-6s8 2 8 6"/>
                        </svg>
                        <span style="font-weight:500;">
                            <?= htmlspecialchars($_SESSION['user']['full_name'] ?? $_SESSION['user']['nama_lengkap'] ?? $_SESSION['user']['username'] ?? 'Anggota') ?>
                        </span>
                        <span class="user-role-tag">
                            <?= htmlspecialchars($_SESSION['user']['role'] ?? 'anggota') ?>
                        </span>
                    </div>

                    <a href="<?= rtrim(BASEURL, '/') ?>/auth/logout" class="btn user-logout-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Logout
                    </a>
                <?php endif; ?>
            </div>
        </header>

        <!-- Hero Banner -->
        <section class="user-hero">
            <div>
                <h1>Temukan bacaan favoritmu di RuangMP</h1>
                <p>Jelajahi ribuan inspirasi, pengetahuan, dan cerita menarik. Semua berawal dari satu halaman.</p>
                <button type="button" class="hero-btn" onclick="document.getElementById('koleksi').scrollIntoView({behavior:'smooth'})">
                    Jelajahi Koleksi →
                </button>
            </div>
        </section>

        <!-- Filter Kategori -->
        <div class="section-head">
            <div>
                <div class="eyebrow">JELAJAHI KOLEKSI</div>
                <h2>Kategori Buku</h2>
            </div>
        </div>

        <div class="categories" id="categories">
            <button type="button" class="chip active" data-cat="Semua">Semua</button>
            <?php if (!empty($categories)) : ?>
                <?php foreach ($categories as $kat) : ?>
                    <button type="button" class="chip" data-cat="<?= htmlspecialchars($kat['nama_kategori']) ?>">
                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Grid Buku -->
        <section id="koleksi">
            <div class="section-head">
                <div>
                    <div class="eyebrow">RILIS TERBARU</div>
                    <h2>📘 Buku Pilihan</h2>
                </div>
                <button type="button" class="btn ghost" style="padding:6px 12px;font-size:12px;" onclick="resetFilter()">Reset filter</button>
            </div>

            <div class="books-grid" id="bookGrid">
                <?php if (!empty($books)) : ?>
                    <?php foreach ($books as $buku) : ?>
                        <div class="book-card"
                             data-cat="<?= htmlspecialchars($buku['nama_kategori'] ?? 'Umum') ?>"
                             data-title="<?= htmlspecialchars(strtolower($buku['judul'] ?? '')) ?>"
                             data-author="<?= htmlspecialchars(strtolower($buku['penulis'] ?? '')) ?>"
                             onclick="openBookDetail(
                                 '<?= htmlspecialchars(addslashes($buku['judul'] ?? '-')) ?>',
                                 '<?= htmlspecialchars(addslashes($buku['penulis'] ?? 'Anonim')) ?>',
                                 '<?= htmlspecialchars(addslashes($buku['nama_kategori'] ?? 'Umum')) ?>',
                                 '<?= htmlspecialchars(addslashes($buku['deskripsi'] ?? 'Tidak ada deskripsi buku.')) ?>',
                                 '<?= (int)($buku['stok_tersedia'] ?? 0) ?>'
                             )">
                            <div class="book-cover">
                                <?php if (!empty($buku['cover']) && file_exists(ROOT_PATH . '/images/' . $buku['cover'])) : ?>
                                    <img src="<?= BASEURL ?>/../images/<?= htmlspecialchars($buku['cover']) ?>" alt="<?= htmlspecialchars($buku['judul']) ?>">
                                <?php else : ?>
                                    <div class="cover-placeholder">📘</div>
                                <?php endif; ?>
                            </div>
                            <div class="book-info">
                                <span class="book-cat"><?= htmlspecialchars($buku['nama_kategori'] ?? 'Umum') ?></span>
                                <h3 class="book-title"><?= htmlspecialchars($buku['judul']) ?></h3>
                                <p class="book-author"><?= htmlspecialchars($buku['penulis'] ?? '-') ?></p>
                                <small class="book-stock <?= ($buku['stok_tersedia'] ?? 0) > 0 ? 'available' : 'empty' ?>">
                                    <?= ($buku['stok_tersedia'] ?? 0) > 0 ? 'Tersedia: ' . $buku['stok_tersedia'] . ' eks' : 'Stok Habis' ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p style="color: var(--mute, #8b98b2); padding: 20px 0;">Belum ada koleksi buku yang tersedia.</p>
                <?php endif; ?>
            </div>
        </section>

        <footer class="user-footer">© 2026 RuangMP — Perpustakaan Digital. Belajar dan bertumbuh bersama.</footer>
    </div>

    <!-- Modal Detail Buku -->
    <div class="scrim" id="detailModal" role="dialog" aria-modal="true">
        <div class="modal">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span class="eyebrow" id="detailCat"></span>
                <button type="button" class="btn ghost" style="padding:4px 10px;" onclick="closeDetail()">✕</button>
            </div>
            <h2 id="detailTitle" style="margin:0 0 6px;"></h2>
            <p id="detailAuthor" style="color:var(--mute, #8b98b2);font-size:13.5px;margin:0 0 12px;"></p>
            <p id="detailDesc" style="font-size:13.5px;line-height:1.6;margin:0 0 14px;"></p>
            <p id="detailStock" style="font-size:13px;color:#60a5fa;font-weight:600;margin:0 0 18px;"></p>
            <div class="mact">
                <button type="button" class="btn ghost" onclick="closeDetail()">Tutup</button>
                <button type="button" class="btn" onclick="showToast('Silakan hubungi petugas perpustakaan untuk meminjam buku ini.')">Ajukan Peminjaman</button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast" role="status"></div>

    <script>
        const chips = document.querySelectorAll('.chip');
        const cards = document.querySelectorAll('.book-card');
        const searchInput = document.getElementById('searchInput');

        function applyFilter(selectedCat, keyword = '') {
            cards.forEach(card => {
                const cardCat = card.getAttribute('data-cat');
                const title = card.getAttribute('data-title');
                const author = card.getAttribute('data-author');

                const matchCat = (selectedCat === 'Semua' || cardCat === selectedCat);
                const matchText = (title.includes(keyword) || author.includes(keyword));

                card.style.display = (matchCat && matchText) ? '' : 'none';
            });
        }

        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                chips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                applyFilter(chip.getAttribute('data-cat'), searchInput.value.toLowerCase().trim());
            });
        });

        function resetFilter() {
            searchInput.value = '';
            chips.forEach(c => c.classList.toggle('active', c.getAttribute('data-cat') === 'Semua'));
            applyFilter('Semua', '');
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