const books=[
 {title:"Verity",author:"Colleen Hoover",cat:"Romansa",views:73,style:"c1",desc:"Sebuah kisah penuh misteri dan ketegangan tentang rahasia yang tersembunyi."},
 {title:"One Piece — Volume 12",author:"Eiichiro Oda",cat:"Komik",views:253,style:"c2",desc:"Petualangan kru Topi Jerami berlanjut dalam kisah persahabatan dan keberanian."},
 {title:"Dongkrak Omzet Miliaran",author:"Dewa Eka Prayoga",cat:"Bisnis",views:164,style:"c3",desc:"Ide dan strategi untuk mengembangkan usaha serta meningkatkan penjualan."},
 {title:"Kita Terus Buat Indonesia Menang",author:"Tim Penulis",cat:"Kisah Inspiratif",views:893,style:"c4",desc:"Cerita dan semangat untuk terus berkarya dan memberi dampak positif."},
 {title:"One Piece — Chapter 002",author:"Eiichiro Oda",cat:"Komik",views:126,style:"c5",desc:"Lanjutan petualangan seru dalam dunia One Piece."},
 {title:"Originals",author:"Adam Grant",cat:"Pengembangan Diri",views:241,style:"c6",desc:"Mengenal cara berpikir kreatif dan berani membawa gagasan baru."},
 {title:"Belajar Teknologi Digital",author:"RuangBaca",cat:"Teknologi",views:91,style:"c6",desc:"Pengantar praktis memahami perkembangan teknologi digital."},
 {title:"Strategi Sukses Belajar",author:"RuangBaca",cat:"Pendidikan",views:118,style:"c3",desc:"Panduan membangun kebiasaan belajar yang efektif dan konsisten."},
 {title:"Cerita Senja",author:"A. Pratama",cat:"Romansa",views:67,style:"c1",desc:"Kumpulan cerita ringan tentang pertemuan, harapan, dan perjalanan hidup."},
 {title:"Membangun Bisnis dari Nol",author:"RuangBaca",cat:"Bisnis",views:202,style:"c4",desc:"Langkah awal merancang dan menjalankan usaha secara bertahap."},
 {title:"Petualangan Si Kecil",author:"RuangBaca",cat:"Komik",views:84,style:"c2",desc:"Komik petualangan penuh warna untuk menemani waktu santai."},
 {title:"Berani Berkembang",author:"RuangBaca",cat:"Pengembangan Diri",views:153,style:"c5",desc:"Dorongan untuk mengenali potensi dan terus berkembang."}
];
const coverImages = ['images/sampul-anime-1.jpg','images/sampul-anime-2.jpg','images/sampul-anime-3.jpg','images/sampul-anime-4.jpg','images/sampul-anime-5.jpg','images/sampul-anime-6.jpg'];
books.forEach((book, index) => book.image = coverImages[index] || '');
let activeCat="Semua", query="";
function render(){const grid=document.getElementById('bookGrid');const filtered=books.filter(b=>(activeCat==="Semua"||b.cat===activeCat)&&(`${b.title} ${b.author} ${b.cat}`).toLowerCase().includes(query.toLowerCase()));grid.innerHTML=filtered.length?filtered.map((b,i)=>`<article class="book" onclick="showDetail(${books.indexOf(b)})"><div class="cover ${b.style}">${b.image?`<img src="${b.image}" alt="Sampul buku ${b.title}" loading="lazy">`:`<span>${b.title.toUpperCase()}</span>`}</div><h3>${b.title}</h3><p class="author">${b.author}</p><span class="tag">${b.cat}</span><div class="views">👁 ${b.views} x dilihat</div></article>`).join(''):'<div class="empty">Tidak ada buku yang cocok. Coba kata kunci lain.</div>'}
document.getElementById('searchForm').addEventListener('submit',e=>{e.preventDefault();query=document.getElementById('searchInput').value;render()});
document.getElementById('searchInput').addEventListener('input',e=>{query=e.target.value;render()});
document.getElementById('categories').addEventListener('click',e=>{if(!e.target.matches('.chip'))return;document.querySelectorAll('.chip').forEach(x=>x.classList.remove('active'));e.target.classList.add('active');activeCat=e.target.dataset.cat;render()});
function resetFilter(){activeCat='Semua';query='';document.getElementById('searchInput').value='';document.querySelectorAll('.chip').forEach(x=>x.classList.toggle('active',x.dataset.cat==='Semua'));render()}
function openLogin(){document.getElementById('loginModal').classList.add('show')}
function closeLogin(){document.getElementById('loginModal').classList.remove('show')}
function setTab(tab){let reg=tab==='register';document.getElementById('loginTab').classList.toggle('active',!reg);document.getElementById('registerTab').classList.toggle('active',reg);document.getElementById('nameField').style.display=reg?'block':'none';document.getElementById('formTitle').textContent=reg?'Buat Akun Baru':'Selamat Datang!';document.getElementById('formDesc').textContent=reg?'Daftar untuk mulai menjelajahi RuangBaca.':'Masuk untuk menikmati berbagai layanan RuangBaca.';document.getElementById('submitAccount').textContent=reg?'DAFTAR SEKARANG':'LOGIN SEKARANG'}
document.getElementById('accountForm').addEventListener('submit',e=>{e.preventDefault();showToast('Demo tampilan: autentikasi belum terhubung ke server.');closeLogin()});
function showDetail(i){let b=books[i];document.getElementById('detailCat').textContent=b.cat;document.getElementById('detailTitle').textContent=b.title;document.getElementById('detailAuthor').textContent='Penulis: '+b.author;document.getElementById('detailDesc').textContent=b.desc;document.getElementById('detailModal').classList.add('show')}
function closeDetail(){document.getElementById('detailModal').classList.remove('show')}
let toastTimer;function showToast(t){let el=document.getElementById('toast');el.textContent=t;el.classList.add('show');clearTimeout(toastTimer);toastTimer=setTimeout(()=>el.classList.remove('show'),2800)}
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeLogin();closeDetail()}});
document.querySelectorAll('.modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('show')}));
/* Toggle dark mode with Alt+D */
document.addEventListener('keydown',e=>{if(e.altKey&&e.key.toLowerCase()==='d')document.body.classList.toggle('dark')});
render();
document.addEventListener('DOMContentLoaded', function () {
  const btnDots = document.getElementById('btnDotsMenu');
  const dropdown = document.getElementById('dotsDropdown');

  if (btnDots && dropdown) {
    btnDots.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('show');
    });

    document.addEventListener('click', function () {
      dropdown.classList.remove('show');
    });
  }
});