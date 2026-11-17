<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Perpustakaan digital — temukan buku, komik, dan bacaan favoritmu.">
<title>RuangBaca — Perpustakaan Digital</title>
<link rel="stylesheet" href="<?= BASEURL ?>/../css/style.css">
</head>
<body>
<div class="topline"></div>
<header>
 <div class="head">
    <a class="brand" href="<?= BASEURL ?>/dashboard">
        <div class="logo">📖</div>
        <div>
            <b>RUANGBACA</b><small>Perpustakaan Digital</small>
        </div>
    </a>
    <form class="search" id="searchForm">
        <input id="searchInput" type="search" placeholder="🔎  Cari judul buku, penulis..." aria-label="Cari buku">
        <button aria-label="Cari">⌕</button>
    </form>

    <div class="action">
        <div class="user-badge-box">
            <span class="user-icon">👤</span>
            <span class="user-name"><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? $_SESSION['user']['username'] ?? 'Administrator'); ?></span>
            <span class="role-pill"><?= htmlspecialchars($_SESSION['user']['role'] ?? 'admin'); ?></span>
        </div>
    <div class="menu-dots=wrapper">
        <button type="button" class="btn-dots" id="btnDotsMenu" aria-label="Menu Pengelola" title="Menu Pengelola">⋮</button>
        <div class="dots-dropdown" id="dotsDropown">
            <a href="<?= BASEURL ?>/items/create">➕ Tambah Buku</a>
            <a href="<?= BASEURL ?>/taransactions">🔄Peminjaman &amp; Denda</a>
        </div>
    </div>

    <a href="<? BASEURL ?>/auth/logout" class="btn-logout">↪ Logout</a>
</div>
</div>
 <nav class="nav">
    <a href="#komik">Komik</a>
    <a href="#bisnis">Bisnis dan Ekonomi</a>
    <a href="#pengembangan">Pengembangan Diri</a>
    <a href="#inspirasi">Kisah Inspiratif</a>
    <a href="#gratis">Ebook Gratis</a>
    <a href="#digital">Produk Digital</a>
    <a href="#game">Game</a>
    <a href="#trending">🔥 Trending</a>
</nav>
</header>

<main>
    <section class="hero">
        <div>
            <h1>Temukan bacaan favoritmu di RuangBaca</h1>
            <p>Jelajahi ribuan inspirasi, pengetahuan, dan cerita menarik. Semua berawal dari satu halaman.</p>
            <button onclick="document.getElementById('koleksi').scrollIntoView({behavior:'smooth'})">Jelajahi Koleksi →</button>
            <div class="hero-note">📚 Baca kapan saja • Belajar tanpa batas</div>
        </div>
    </section>
    <div class="section-head">
        <div><div class="eyebrow">JELAJAHI KOLEKSI</div>
        <h2>Kategori Buku</h2>
    </div>
</div>
<div class="categories" id="categories">
    <button class="chip active" data-cat="Semua">Semua</button>
    <button class="chip" data-cat="Komik">Komik</button>
    <button class="chip" data-cat="Bisnis">Bisnis dan Ekonomi</button>
    <button class="chip" data-cat="Pengembangan Diri">Pengembangan Diri</button>
    <button class="chip" data-cat="Romansa">Romansa</button>
    <button class="chip" data-cat="Teknologi">Teknologi</button>
    <button class="chip" data-cat="Pendidikan">Pendidikan</button>
</div>
<section id="koleksi">
    <div class="section-head">
        <div>
            <div class="eyebrow">RILIS TERBARU</div>
            <h2>📘 Buku Pilihan</h2>
        </div>
        <button class="see" onclick="resetFilter()">Lihat semua →</button>
    </div>
    <div class="books" id="bookGrid"></div>
</section>
</main>

<footer class="footer">© 2026 RuangBaca — Perpustakaan Digital. Belajar dan bertumbuh bersama.</footer>
<div class="modal" id="loginModal" role="dialog" aria-modal="true" aria-label="Login">
    <div class="panel">
        <button type="button" class="close" onclick="closeLogin()">×</button>
        <div class="detail-cover">📚</div>
        <div class="eyebrow" id="detailCat"></div>
        <h2 id="detailTitle"></h2>
        <p id="detailAuthor"></p>
        <a href="<?= BASEURL ?>/transactions" class="primary" style="display:inline-block; text-align:center; text-decoration:none">Pinjam Buku Ini</a>
        <div>
    </div>
    <div class="toast" id="toast"></div>
    <script src="<?= BASEURL ?>/../js/script.js"></script>
</body>
</html>