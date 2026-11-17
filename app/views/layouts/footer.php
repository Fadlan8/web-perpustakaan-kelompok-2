<footer class="footer">© 2026 RuangBaca — Perpustakaan Digital. Belajar dan bertumbuh bersama.</footer>
<div class="modal" id="loginModal" role="dialog" aria-modal="true" aria-label="Login">
 <div class="panel"><button class="close" onclick="closeLogin()">×</button><h2 id="formTitle">Selamat Datang!</h2><p id="formDesc">Masuk untuk menikmati berbagai layanan RuangBaca.</p>
  <div class="tabs"><button id="loginTab" class="active" onclick="setTab('login')">Login</button><button id="registerTab" onclick="setTab('register')">Daftar</button></div>
  <form id="accountForm"><div class="field" id="nameField" style="display:none"><label>Nama lengkap</label><input id="name" placeholder="Nama kamu"></div><div class="field"><label>Email address</label><input id="email" type="email" placeholder="Email" required></div><div class="field"><label>Password</label><input id="password" type="password" placeholder="Password" required minlength="4"></div><button class="primary" type="submit" id="submitAccount">LOGIN SEKARANG</button></form>
  <div style="text-align:center;color:var(--blue);margin:15px 0 5px">atau</div><button class="google" onclick="showToast('Login Google perlu dihubungkan ke layanan autentikasi.')">🌐 Login via Google</button>
 </div>
</div>
<div class="modal" id="detailModal" role="dialog" aria-modal="true">
 <div class="panel"><button class="close" onclick="closeDetail()">×</button><div class="detail-cover">📚</div><div class="eyebrow" id="detailCat"></div><h2 id="detailTitle"></h2><p id="detailAuthor"></p><p id="detailDesc"></p><button class="primary" onclick="showToast('Silakan login untuk membaca buku ini.')">Baca Sekarang</button></div>
</div>
<div class="toast" id="toast"></div>
<script src="js/script.js"></script>