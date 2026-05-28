
(function(){
'use strict';

const LS_CART = 'zdravie_cart_v1';
const MIN_ORDER_AMOUNT = 10000;
const CATALOG_POLL_MS = 5000;
const API = {
  catalog: 'api/catalog.php',
  products: 'api/products.php',
  auth: 'api/auth.php',
  settings: 'api/settings.php'
};

const CATEGORY_EMOJI = {Фрукты:'🍎',Овощи:'🥦',Ягоды:'🍓',Зелень:'🌿',Салаты:'🥗',Мясо:'🥩',Птица:'🍗',Рыба:'🐟',Морепродукты:'🦐',Орехи:'🥜',Специи:'🌶',Бакалея:'🌾',Хозбыт:'🧹','Хозтовары':'🧹'};
const DEFAULT_CONFIG = {
  company:'Здравие',
  phone:'+7 (XXX) XXX-XX-XX',
  tg:'https://t.me/zdravie',
  wa:'+79001234567',
  mx:'https://max.ru/zdravie',
  stat1:'150+',
  stat2:'500+'
};

let currentConfig = Object.assign({}, DEFAULT_CONFIG);
let csrfToken = '';
let catalogUpdatedAt = '';
let pollingStarted = false;

const FALLBACK_PRODUCTS = [
  {
    "id": "1",
    "name": "Яблоки Голден",
    "category": "Фрукты",
    "price": 85,
    "unit": "кг",
    "packaging": "коробка 18 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🍎",
    "inStock": true
  },
  {
    "id": "2",
    "name": "Яблоки Гала",
    "category": "Фрукты",
    "price": 90,
    "unit": "кг",
    "packaging": "коробка 18 кг",
    "origin": "Сербия",
    "flag": "🇷🇸",
    "emoji": "🍎",
    "inStock": true
  },
  {
    "id": "3",
    "name": "Бананы",
    "category": "Фрукты",
    "price": 95,
    "unit": "кг",
    "packaging": "коробка 18 кг",
    "origin": "Эквадор",
    "flag": "🇪🇨",
    "emoji": "🍌",
    "inStock": true
  },
  {
    "id": "4",
    "name": "Апельсины",
    "category": "Фрукты",
    "price": 110,
    "unit": "кг",
    "packaging": "сетка 15 кг",
    "origin": "Египет",
    "flag": "🇪🇬",
    "emoji": "🍊",
    "inStock": true
  },
  {
    "id": "5",
    "name": "Мандарины",
    "category": "Фрукты",
    "price": 130,
    "unit": "кг",
    "packaging": "сетка 10 кг",
    "origin": "Марокко",
    "flag": "🇲🇦",
    "emoji": "🍊",
    "inStock": true
  },
  {
    "id": "6",
    "name": "Виноград Кишмиш",
    "category": "Фрукты",
    "price": 220,
    "unit": "кг",
    "packaging": "коробка 8 кг",
    "origin": "Узбекистан",
    "flag": "🇺🇿",
    "emoji": "🍇",
    "inStock": true
  },
  {
    "id": "7",
    "name": "Лимоны",
    "category": "Фрукты",
    "price": 150,
    "unit": "кг",
    "packaging": "коробка 15 кг",
    "origin": "Турция",
    "flag": "🇹🇷",
    "emoji": "🍋",
    "inStock": true
  },
  {
    "id": "8",
    "name": "Гранат",
    "category": "Фрукты",
    "price": 280,
    "unit": "кг",
    "packaging": "коробка 6 кг",
    "origin": "Иран",
    "flag": "🇮🇷",
    "emoji": "🍎",
    "inStock": true
  },
  {
    "id": "9",
    "name": "Персики",
    "category": "Фрукты",
    "price": 200,
    "unit": "кг",
    "packaging": "коробка 6 кг",
    "origin": "Испания",
    "flag": "🇪🇸",
    "emoji": "🍑",
    "inStock": false
  },
  {
    "id": "10",
    "name": "Томаты черри",
    "category": "Овощи",
    "price": 250,
    "unit": "кг",
    "packaging": "коробка 5 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🍅",
    "inStock": true
  },
  {
    "id": "11",
    "name": "Томаты сливка",
    "category": "Овощи",
    "price": 130,
    "unit": "кг",
    "packaging": "коробка 10 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🍅",
    "inStock": true
  },
  {
    "id": "12",
    "name": "Огурцы",
    "category": "Овощи",
    "price": 75,
    "unit": "кг",
    "packaging": "коробка 15 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🥒",
    "inStock": true
  },
  {
    "id": "13",
    "name": "Перец болгарский",
    "category": "Овощи",
    "price": 180,
    "unit": "кг",
    "packaging": "коробка 10 кг",
    "origin": "Белоруссия",
    "flag": "🇧🇾",
    "emoji": "🫑",
    "inStock": true
  },
  {
    "id": "14",
    "name": "Картофель мытый",
    "category": "Овощи",
    "price": 35,
    "unit": "кг",
    "packaging": "мешок 25 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🥔",
    "inStock": true
  },
  {
    "id": "15",
    "name": "Лук репчатый",
    "category": "Овощи",
    "price": 28,
    "unit": "кг",
    "packaging": "мешок 25 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🧅",
    "inStock": true
  },
  {
    "id": "16",
    "name": "Морковь",
    "category": "Овощи",
    "price": 32,
    "unit": "кг",
    "packaging": "мешок 25 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🥕",
    "inStock": true
  },
  {
    "id": "17",
    "name": "Чеснок",
    "category": "Овощи",
    "price": 280,
    "unit": "кг",
    "packaging": "сетка 10 кг",
    "origin": "Китай",
    "flag": "🇨🇳",
    "emoji": "🧄",
    "inStock": true
  },
  {
    "id": "18",
    "name": "Свёкла",
    "category": "Овощи",
    "price": 30,
    "unit": "кг",
    "packaging": "мешок 25 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🥕",
    "inStock": true
  },
  {
    "id": "19",
    "name": "Клубника",
    "category": "Ягоды",
    "price": 380,
    "unit": "кг",
    "packaging": "коробка 4 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🍓",
    "inStock": true
  },
  {
    "id": "20",
    "name": "Петрушка",
    "category": "Зелень",
    "price": 120,
    "unit": "кг",
    "packaging": "пучок 100 г",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🌿",
    "inStock": true
  },
  {
    "id": "21",
    "name": "Укроп",
    "category": "Зелень",
    "price": 110,
    "unit": "кг",
    "packaging": "пучок 100 г",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🌿",
    "inStock": true
  },
  {
    "id": "22",
    "name": "Базилик",
    "category": "Зелень",
    "price": 350,
    "unit": "кг",
    "packaging": "упак 100 г",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🌿",
    "inStock": true
  },
  {
    "id": "23",
    "name": "Говядина лопатка б/к",
    "category": "Мясо",
    "price": 580,
    "unit": "кг",
    "packaging": "от 5 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🥩",
    "inStock": true
  },
  {
    "id": "24",
    "name": "Свинина шея",
    "category": "Мясо",
    "price": 420,
    "unit": "кг",
    "packaging": "от 5 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🥩",
    "inStock": true
  },
  {
    "id": "25",
    "name": "Куриное филе",
    "category": "Птица",
    "price": 280,
    "unit": "кг",
    "packaging": "от 2 кг",
    "origin": "Россия",
    "flag": "🇷🇺",
    "emoji": "🍗",
    "inStock": true
  },
  {
    "id": "26",
    "name": "Лосось с/м",
    "category": "Рыба",
    "price": 850,
    "unit": "кг",
    "packaging": "от 3 кг",
    "origin": "Норвегия",
    "flag": "🇳🇴",
    "emoji": "🐟",
    "inStock": true
  },
  {
    "id": "27",
    "name": "Тигровые креветки 16/20",
    "category": "Морепродукты",
    "price": 1200,
    "unit": "кг",
    "packaging": "блок 1 кг",
    "origin": "Вьетнам",
    "flag": "🇻🇳",
    "emoji": "🦐",
    "inStock": true
  },
  {
    "id": "28",
    "name": "Грецкий орех",
    "category": "Орехи",
    "price": 850,
    "unit": "кг",
    "packaging": "от 5 кг",
    "origin": "Иран",
    "flag": "🇮🇷",
    "emoji": "🥜",
    "inStock": true
  },
  {
    "id": "29",
    "name": "Миндаль",
    "category": "Орехи",
    "price": 1100,
    "unit": "кг",
    "packaging": "от 5 кг",
    "origin": "США",
    "flag": "🇺🇸",
    "emoji": "🥜",
    "inStock": true
  },
  {
    "id": "30",
    "name": "Кешью",
    "category": "Орехи",
    "price": 1300,
    "unit": "кг",
    "packaging": "от 5 кг",
    "origin": "Индия",
    "flag": "🇮🇳",
    "emoji": "🥜",
    "inStock": true
  },
  {
    "id": "31",
    "name": "Фисташки",
    "category": "Орехи",
    "price": 1600,
    "unit": "кг",
    "packaging": "от 5 кг",
    "origin": "Иран",
    "flag": "🇮🇷",
    "emoji": "🥜",
    "inStock": true
  },
  {
    "id": "32",
    "name": "Арахис жареный",
    "category": "Орехи",
    "price": 320,
    "unit": "кг",
    "packaging": "от 5 кг",
    "origin": "Китай",
    "flag": "🇨🇳",
    "emoji": "🥜",
    "inStock": true
  },
  {
    "id": "33",
    "name": "Перец чёрный",
    "category": "Специи",
    "price": 450,
    "unit": "кг",
    "packaging": "уп 1 кг",
    "origin": "Вьетнам",
    "flag": "🇻🇳",
    "emoji": "🌶",
    "inStock": true
  }
];

let products = [];
let cart = loadJSON(LS_CART, {});
let currentCategory = 'all';
let currentQuery = '';
let currentSort = 'name';
let authed = false;
let adminQuery = '';

function $(id){ return document.getElementById(id); }
function loadJSON(key, fallback){ try{ const raw = localStorage.getItem(key); return raw ? JSON.parse(raw) : fallback; }catch(e){ return fallback; } }
function saveJSON(key, value){ localStorage.setItem(key, JSON.stringify(value)); }
async function apiRequest(url, options = {}){
  const headers = Object.assign({'Accept':'application/json'}, options.headers || {});
  const fetchOptions = {
    method: options.method || 'GET',
    credentials: 'same-origin',
    headers
  };
  if(csrfToken) headers['X-CSRF-Token'] = csrfToken;
  if(options.body !== undefined){
    headers['Content-Type'] = 'application/json';
    fetchOptions.body = JSON.stringify(options.body);
  }
  const res = await fetch(url, fetchOptions);
  const data = await res.json().catch(()=>({ok:false,error:'Сервер вернул некорректный ответ'}));
  if(data.csrf) csrfToken = data.csrf;
  if(!res.ok || data.ok === false) throw new Error(data.error || 'Ошибка запроса');
  return data;
}
function escapeHTML(value){ return String(value ?? '').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'",'&#039;'); }
function money(value){ return Number(value || 0).toLocaleString('ru-RU') + ' ₽'; }
function normalizeBool(value){ return value === true || String(value).toLowerCase().trim() === 'true' || String(value).trim() === '1' || String(value).toLowerCase().trim() === 'да'; }
function normalizeProduct(row, index){
  const price = Number(String(row.price ?? row['цена'] ?? '').replace(',', '.').replace(/[^0-9.]/g,''));
  const name = String(row.name ?? row['название'] ?? '').trim();
  if(!name || !Number.isFinite(price)) return null;
  return {
    id: String(row.id || index + 1),
    name,
    category: String(row.category ?? row['категория'] ?? 'Прочее').trim() || 'Прочее',
    price,
    unit: String(row.unit ?? row['единица'] ?? 'кг').trim() || 'кг',
    packaging: String(row.packaging ?? row.pack ?? row['фасовка'] ?? '').trim(),
    origin: String(row.origin ?? row.country ?? row['страна'] ?? '').trim(),
    flag: String(row.flag ?? row['флаг'] ?? '').trim(),
    emoji: String(row.emoji ?? row['эмодзи'] ?? '').trim(),
    inStock: row.inStock !== undefined ? normalizeBool(row.inStock) : (row.stock !== undefined ? normalizeBool(row.stock) : true)
  };
}
function setUpdatedText(text){ const el=$('lastUpdated'); if(el) el.textContent = text; }
function formatUpdated(source, value){
  const d = value ? new Date(value) : null;
  const ts = d && !Number.isNaN(d.getTime())
    ? d.toLocaleString('ru-RU',{day:'2-digit',month:'2-digit',year:'numeric',hour:'2-digit',minute:'2-digit'})
    : new Date().toLocaleString('ru-RU',{day:'2-digit',month:'2-digit',year:'numeric',hour:'2-digit',minute:'2-digit'});
  return (source ? source + ' · ' : '') + 'Обновлено: ' + ts;
}
function getConfig(){ return Object.assign({}, DEFAULT_CONFIG, currentConfig); }
async function saveConfig(cfg){
  const data = await apiRequest(API.settings, {method:'POST', body:cfg});
  currentConfig = Object.assign({}, DEFAULT_CONFIG, data.config || cfg);
  applyConfig();
  fillSettings();
  toast('✅ Настройки сохранены');
}
function updateProducts(nextProducts, source, updatedAt){
  products = nextProducts.map(normalizeProduct).filter(Boolean);
  if(!products.length) products = FALLBACK_PRODUCTS.map(normalizeProduct).filter(Boolean);
  if(updatedAt) setUpdatedText(formatUpdated(source || 'База данных', updatedAt));
  else if(source) setUpdatedText(source);
  renderAll();
}
function applyCatalog(data, source){
  if(data.csrf) csrfToken = data.csrf;
  if(data.config){ currentConfig = Object.assign({}, DEFAULT_CONFIG, data.config); applyConfig(); }
  if(Object.prototype.hasOwnProperty.call(data, 'admin')) authed = !!data.admin;
  catalogUpdatedAt = data.updatedAt || catalogUpdatedAt;
  updateProducts(data.products || [], source || 'База данных', catalogUpdatedAt);
}
async function initProducts(){
  try{
    const data = await apiRequest(API.catalog);
    applyCatalog(data, 'База данных');
    startCatalogPolling();
    return;
  }catch(e){
    console.warn('PHP API недоступен, fallback:', e);
  }
  try{
    const res = await fetch('data/products.json', {cache:'no-store'});
    if(!res.ok) throw new Error('products.json loading failed');
    const rows = await res.json();
    updateProducts(rows, 'JSON-файл', new Date().toISOString());
  }catch(e){
    console.warn('products.json недоступен, fallback:', e);
    products = FALLBACK_PRODUCTS.map(normalizeProduct).filter(Boolean);
    setUpdatedText('Резервный прайс');
    renderAll();
  }
}
function startCatalogPolling(){
  if(pollingStarted) return;
  pollingStarted = true;
  setInterval(refreshCatalog, CATALOG_POLL_MS);
}
async function refreshCatalog(){
  try{
    const oldUpdatedAt = catalogUpdatedAt;
    const data = await apiRequest(API.catalog);
    if(data.updatedAt !== oldUpdatedAt){
      applyCatalog(data, 'База данных');
      if(oldUpdatedAt) toast('Прайс обновлён');
    }else{
      if(data.config){ currentConfig = Object.assign({}, DEFAULT_CONFIG, data.config); applyConfig(); }
      authed = !!data.admin;
    }
  }catch(e){
    console.warn('Не удалось обновить каталог:', e);
  }
}
function csvToObjects(csv){
  const rows = parseCSV(csv).filter(r => r.some(c => String(c).trim() !== ''));
  const headers = (rows.shift() || []).map(h => String(h).trim());
  return rows.map(row => Object.fromEntries(headers.map((h,i)=>[h,row[i] ?? ''])));
}
function parseCSV(str){
  const rows=[]; let row=[]; let cur=''; let quote=false;
  for(let i=0;i<str.length;i++){
    const ch=str[i], next=str[i+1];
    if(ch==='"'){
      if(quote && next==='"'){ cur+='"'; i++; }
      else quote=!quote;
    }else if(ch===',' && !quote){ row.push(cur); cur=''; }
    else if((ch==='\n' || ch==='\r') && !quote){ if(ch==='\r' && next==='\n') i++; row.push(cur); rows.push(row); row=[]; cur=''; }
    else cur+=ch;
  }
  row.push(cur); rows.push(row); return rows;
}
function applyConfig(){
  const c=getConfig();
  const site=$('siteName'); if(site) site.textContent=c.company;
  const hp=$('hdrPhone'); if(hp) hp.textContent=c.phone;
  const fp=$('footerPhone'); if(fp){ fp.textContent=c.phone; fp.href='tel:'+c.phone.replace(/\D/g,''); }
  ['phBtn','phBtnM'].forEach(id=>{ const el=$(id); if(el) el.href='tel:'+c.phone.replace(/\D/g,''); });
  const s1=$('s1'); if(s1) s1.textContent=c.stat1;
  const tg = c.tg.startsWith('http') ? c.tg : 'https://t.me/' + c.tg.replace('@','');
  ['tgBtn','tgBtnM','tgBtnF'].forEach(id=>{ const el=$(id); if(el) el.href=tg; });
  const wa='https://wa.me/'+c.wa.replace(/\D/g,'');
  ['waBtn','waBtnM','waBtnF'].forEach(id=>{ const el=$(id); if(el) el.href=wa; });
  const mx=(c.mx || DEFAULT_CONFIG.mx).startsWith('http') ? c.mx : 'https://max.ru/' + String(c.mx || '').replace('@','');
  ['maxBtn','maxBtnM','maxBtnF','maxBtnO'].forEach(id=>{ const el=$(id); if(el) el.href=mx; });
}
function categories(){ return [...new Set(products.map(p=>p.category).filter(Boolean))].sort((a,b)=>a.localeCompare(b,'ru')); }
function filteredProducts(){
  let arr = [...products];
  if(currentCategory !== 'all') arr = arr.filter(p => p.category === currentCategory);
  if(currentQuery){
    const q = currentQuery.toLowerCase();
    arr = arr.filter(p => [p.name,p.category,p.origin,p.packaging].some(v => String(v||'').toLowerCase().includes(q)));
  }
  if(currentSort === 'stock') arr = arr.filter(p => p.inStock);
  if(currentSort === 'priceAsc') arr.sort((a,b)=>a.price-b.price);
  else if(currentSort === 'priceDesc') arr.sort((a,b)=>b.price-a.price);
  else arr.sort((a,b)=>a.name.localeCompare(b.name,'ru'));
  return arr;
}
function renderTabs(){
  const wrap=$('categoryTabs'); if(!wrap) return;
  wrap.innerHTML='';
  const all=document.createElement('button'); all.className='ctab'; all.dataset.c='all'; all.textContent='Все'; all.onclick=()=>{currentCategory='all'; renderTable(); highlightTabs();}; wrap.appendChild(all);
  categories().forEach(cat=>{ const b=document.createElement('button'); b.className='ctab'; b.dataset.c=cat; b.textContent=(CATEGORY_EMOJI[cat]||'📦')+' '+cat; b.onclick=()=>{currentCategory=cat; renderTable(); highlightTabs();}; wrap.appendChild(b); });
  highlightTabs();
}
function highlightTabs(){ document.querySelectorAll('.ctab').forEach(btn => btn.classList.toggle('active', btn.dataset.c === currentCategory)); }
function renderTable(){
  const tb=$('priceBody'); if(!tb) return;
  const arr = filteredProducts();
  if(!arr.length){ tb.innerHTML='<tr><td colspan="6"><div class="empty"><div class="ei">🔍</div><p>Ничего не найдено</p></div></td></tr>'; return; }
  tb.innerHTML = arr.map(p=>{
    const q = cart[p.id] || 0;
    const controls = q>0
      ? `<div class="qty-ctrl"><button class="qty-btn" onclick="remC('${escapeHTML(p.id)}')">−</button><div class="qty-v">${q}</div><button class="qty-btn" onclick="addC('${escapeHTML(p.id)}')">+</button></div>`
      : `<button class="add-btn" onclick="addC('${escapeHTML(p.id)}')" ${p.inStock?'':'disabled'}>🛒 В корзину</button>`;
    return `<tr class="${p.inStock?'':'out-of-stock'}"><td><div class="pcell"><div class="pimg">${escapeHTML(p.emoji||CATEGORY_EMOJI[p.category]||'📦')}</div><div><div class="pname">${escapeHTML(p.name)}</div><div class="ppack">${escapeHTML(p.packaging)}</div></div></div></td><td><span class="catbadge">${escapeHTML(p.category)}</span></td><td><div class="ctry"><span class="ctry-flag">${escapeHTML(p.flag)}</span><span class="ctry-name">${escapeHTML(p.origin||'—')}</span></div></td><td><span class="sbadge ${p.inStock?'yes':'no'}">${p.inStock?'● В наличии':'○ Нет в наличии'}</span></td><td><div class="pricecell">${money(p.price)}<br><span class="punit">/ ${escapeHTML(p.unit)}</span></div></td><td style="text-align:center">${controls}</td></tr>`;
  }).join('');
}
function renderAll(){ renderTabs(); renderTable(); updateBadge(); renderAdminCategoryFilter(); }
function saveCart(){ saveJSON(LS_CART, cart); }
function cartSummary(){ let total=0, count=0; Object.entries(cart).forEach(([id,qty])=>{ const p=products.find(x=>x.id===id); if(p){ total += p.price*qty; count += qty; }}); return {total,count}; }
function updateBadge(){ const s=cartSummary(); ['cartCount','floatBadge'].forEach(id=>{ const el=$(id); if(el){ el.textContent=s.count; el.classList.toggle('show', s.count>0); }}); }
window.addC=function(id){ const p=products.find(x=>x.id===id); if(!p || !p.inStock) return; cart[id]=(cart[id]||0)+1; saveCart(); updateBadge(); renderTable(); renderCart(); toast('Добавлено в корзину 🛒'); };
window.remC=function(id){ if(cart[id]>1) cart[id]--; else delete cart[id]; saveCart(); updateBadge(); renderTable(); renderCart(); };
window.openCart=function(){ renderCart(); $('cartOv').classList.add('on'); $('cartDrawer').classList.add('on'); document.body.style.overflow='hidden'; };
window.closeCart=function(){ $('cartOv').classList.remove('on'); $('cartDrawer').classList.remove('on'); document.body.style.overflow=''; };
window.cqA=function(id){ window.addC(id); };
window.cqR=function(id){ window.remC(id); };
window.cDel=function(id){ delete cart[id]; saveCart(); updateBadge(); renderCart(); renderTable(); };
function renderCart(){
  const body=$('cartBody'), foot=$('cartFoot'); if(!body || !foot) return;
  const ids=Object.keys(cart);
  if(!ids.length){ body.innerHTML='<div class="drw-empty"><div style="font-size:38px;margin-bottom:10px">🛒</div><p style="font-size:13px;color:var(--light)">Корзина пуста.<br>Добавьте позиции из прайса.</p></div>'; foot.style.display='none'; return; }
  body.innerHTML=ids.map(id=>{ const p=products.find(x=>x.id===id); if(!p) return ''; const q=cart[id]; return `<div class="ci"><div class="ci-e">${escapeHTML(p.emoji||CATEGORY_EMOJI[p.category]||'📦')}</div><div class="ci-inf"><div class="ci-nm">${escapeHTML(p.name)}</div><div class="ci-ct">${escapeHTML(p.category)} · ${escapeHTML(p.unit)}</div></div><div class="ciq"><button class="ciq-b" onclick="cqR('${escapeHTML(id)}')">−</button><div class="ciq-v">${q}</div><button class="ciq-b" onclick="cqA('${escapeHTML(id)}')">+</button></div><div class="ci-pr">${money(p.price*q)}</div><button class="ci-rm" onclick="cDel('${escapeHTML(id)}')">✕</button></div>`; }).join('');
  const s=cartSummary(); $('cTotVal').textContent=money(s.total); $('cTotItems').textContent=s.count+' позиций'; foot.style.display='block';
}
window.openOrder=function(){ buildPreview(); $('orderOv').classList.add('on'); updateOrderWarning(); };
window.closeOrder=function(){ $('orderOv').classList.remove('on'); };
function buildPreview(){
  const rows=[]; let total=0;
  Object.entries(cart).forEach(([id,qty])=>{ const p=products.find(x=>x.id===id); if(!p) return; const sum=p.price*qty; total+=sum; rows.push(`<div class="op-row"><span>${escapeHTML(p.emoji||'')} ${escapeHTML(p.name)} × ${qty} ${escapeHTML(p.unit)}</span><span>${money(sum)}</span></div>`); });
  rows.push(`<div class="op-row"><span>Предварительная сумма:</span><span>${money(total)}</span></div>`);
  const el=$('orderPrev'); if(el) el.innerHTML=rows.join('');
}
function updateOrderWarning(){
  const warn=$('orderWarn'); if(!warn) return;
  const s=cartSummary();
  if(s.total && s.total < MIN_ORDER_AMOUNT){ warn.textContent='Сумма ниже минимального заказа 10 000 ₽. Можно отправить заявку, менеджер отдельно подтвердит условия.'; warn.classList.add('on'); }
  else { warn.textContent=''; warn.classList.remove('on'); }
}
function phoneIsValid(phone){ return phone.replace(/\D/g,'').length >= 10; }
function formValue(id){ const el=$(id); return el ? el.value.trim() : ''; }
function formChecked(id){ const el=$(id); return !!(el && el.checked); }
function buildOrderText(){
  const client=formValue('oName'), person=formValue('oPerson'), phone=formValue('oPhone');
  if(!client || !phone){ alert('Заполните название/имя и телефон'); return null; }
  if(!phoneIsValid(phone)){ alert('Введите корректный номер телефона'); return null; }
  if(!Object.keys(cart).length){ alert('Корзина пуста'); return null; }
  const address=formValue('oAddress'), delivery=formValue('oDelivery'), type=formValue('oType'), comment=formValue('oComment');
  let total=0; const lines=['Новая заявка с сайта МП Здравие','',`Клиент: ${client}`];
  if(person) lines.push(`Контактное лицо: ${person}`);
  lines.push(`Телефон: ${phone}`);
  if(address) lines.push(`Адрес доставки: ${address}`);
  if(delivery) lines.push(`Дата/время доставки: ${delivery}`);
  if(type) lines.push(`Тип клиента: ${type}`);
  lines.push(`НДС: ${formChecked('oVat')?'да':'нет/не указано'}`);
  lines.push(`Договор: ${formChecked('oContract')?'да':'нет/не указано'}`);
  lines.push(`Документы: ${formChecked('oDocs')?'да':'нет/не указано'}`);
  lines.push('', 'Состав заказа:');
  let i=1;
  Object.entries(cart).forEach(([id,qty])=>{ const p=products.find(x=>x.id===id); if(!p) return; const sum=p.price*qty; total+=sum; lines.push(`${i++}. ${p.name} — ${qty} ${p.unit} × ${money(p.price)} = ${money(sum)}`); });
  lines.push('', `Предварительная сумма: ${money(total)}`);
  if(total < MIN_ORDER_AMOUNT) lines.push(`Комментарий по сумме: ниже минимального заказа ${money(MIN_ORDER_AMOUNT)}, требуется подтверждение менеджера.`);
  if(comment) lines.push('', 'Комментарий:', comment);
  return lines.join('\n');
}
function finish(channel){ $('orderOv').classList.remove('on'); window.closeCart(); cart={}; saveCart(); updateBadge(); renderTable(); toast('✅ Заявка подготовлена: '+channel); }
window.sendTg=function(){ const txt=buildOrderText(); if(!txt) return; const c=getConfig(); const tg=c.tg.startsWith('http')?c.tg:'https://t.me/'+c.tg.replace('@',''); navigator.clipboard?.writeText(txt).catch(()=>{}); window.open(tg,'_blank'); finish('Telegram'); };
window.sendWa=function(){ const txt=buildOrderText(); if(!txt) return; const c=getConfig(); window.open('https://wa.me/'+c.wa.replace(/\D/g,'')+'?text='+encodeURIComponent(txt),'_blank'); finish('WhatsApp'); };
window.sendMx=function(){ const txt=buildOrderText(); if(!txt) return; const c=getConfig(); const mx=(c.mx||DEFAULT_CONFIG.mx).startsWith('http')?c.mx:'https://max.ru/'+String(c.mx||'').replace('@',''); navigator.clipboard?.writeText(txt).catch(()=>{}); window.open(mx,'_blank'); finish('MAX'); };
window.copyOrder=function(){ const txt=buildOrderText(); if(!txt) return; navigator.clipboard?.writeText(txt).then(()=>toast('📋 Заявка скопирована')).catch(()=>{ prompt('Скопируйте заявку:', txt); }); };
window.downloadCSV=function(){
  const headers=['id','category','name','price','unit','packaging','origin','flag','emoji','inStock'];
  const rows=[headers.join(',')].concat(filteredProducts().map(p=>headers.map(h=>'"'+String(p[h]??'').replaceAll('"','""')+'"').join(',')));
  downloadBlob(rows.join('\n'), 'zdravie-price-'+new Date().toISOString().slice(0,10)+'.csv', 'text/csv;charset=utf-8');
};
function downloadBlob(content, filename, type){ const a=document.createElement('a'); a.href=URL.createObjectURL(new Blob([content],{type})); a.download=filename; a.click(); setTimeout(()=>URL.revokeObjectURL(a.href), 2000); }
window.toggleMobileNav=function(){ $('mobileNav')?.classList.toggle('open'); $('menuToggle')?.classList.toggle('open'); };
window.closeMobileNav=function(){ $('mobileNav')?.classList.remove('open'); $('menuToggle')?.classList.remove('open'); };
window.openAdmin=async function(){
  $('adminOv').classList.add('on');
  document.body.style.overflow='hidden';
  try{
    const data = await apiRequest(API.auth);
    authed = !!data.admin;
  }catch(e){
    console.warn('Не удалось проверить сессию:', e);
  }
  authed ? showAdmin() : showPwd();
};
window.closeAdmin=function(){ $('adminOv').classList.remove('on'); document.body.style.overflow=''; };
function showPwd(){ $('pwdView').style.display='block'; $('adminView').style.display='none'; }
function showAdmin(){ $('pwdView').style.display='none'; $('adminView').style.display='flex'; $('adminView').style.flexDirection='column'; renderAdminList(); fillSettings(); renderAdminCategoryFilter(); }
window.checkPwd=async function(){
  const v=formValue('pwdInput');
  try{
    await apiRequest(API.auth, {method:'POST', body:{action:'login', username:'admin', password:v}});
    authed=true;
    $('pwdErr').classList.remove('on');
    $('pwdInput').value='';
    showAdmin();
    toast('✅ Вход выполнен');
  }catch(e){
    $('pwdErr').textContent=e.message || 'Неверный пароль';
    $('pwdErr').classList.add('on');
  }
};
window.logoutAdmin=async function(){
  try{ await apiRequest(API.auth, {method:'POST', body:{action:'logout'}}); }catch(e){}
  authed=false;
  showPwd();
  toast('Вы вышли из админки');
};
window.aTab=function(tab, btn){ document.querySelectorAll('.atab').forEach(b=>b.classList.remove('on')); btn.classList.add('on'); const map={list:'tList',add:'tAdd',cfg:'tCfg',bkp:'tBkp'}; Object.values(map).forEach(id=>$(id).style.display='none'); $(map[tab]).style.display='block'; if(tab==='list') renderAdminList(); if(tab==='cfg') fillSettings(); };
function renderAdminCategoryFilter(){ const sel=$('aCatF'); if(!sel) return; const old=sel.value || 'all'; sel.innerHTML='<option value="all">Все</option>'+categories().map(c=>`<option value="${escapeHTML(c)}">${escapeHTML(c)}</option>`).join(''); sel.value=old; }
window.rAList=function(){ renderAdminList(); };
function renderAdminList(){
  const list=$('aList'); if(!list) return;
  let arr=[...products]; const cf=$('aCatF')?.value || 'all'; if(cf!=='all') arr=arr.filter(p=>p.category===cf);
  if(adminQuery){ const q=adminQuery.toLowerCase(); arr=arr.filter(p=>[p.name,p.category,p.origin].some(v=>String(v||'').toLowerCase().includes(q))); }
  const cnt=$('aCnt'); if(cnt) cnt.textContent=`Позиций: ${products.length}, показано: ${arr.length}`;
  if(!arr.length){ list.innerHTML='<p style="text-align:center;padding:20px;color:var(--light)">Нет позиций</p>'; return; }
  list.innerHTML=arr.map(p=>`<div class="arow"><div class="ae">${escapeHTML(p.emoji||CATEGORY_EMOJI[p.category]||'📦')}</div><div class="ai"><div class="an">${escapeHTML(p.name)}</div><div class="ac">${escapeHTML(p.category)} · ${escapeHTML(p.flag)} ${escapeHTML(p.origin)} · ${p.inStock?'В наличии':'Нет'}</div></div><div class="ap">${money(p.price)}/${escapeHTML(p.unit)}</div><button class="bed" onclick="openEdit('${escapeHTML(p.id)}')">✏</button><button class="bdd" onclick="delP('${escapeHTML(p.id)}')">✕</button></div>`).join('');
}
function productPayload(prefix, includeId){
  const payload = {
    name: formValue(prefix+'Nm'),
    category: formValue(prefix+'Cat'),
    price: Number(formValue(prefix+'Pr').replace(',','.')),
    unit: formValue(prefix+'Un'),
    packaging: formValue(prefix+'Pk'),
    origin: formValue(prefix+'Or'),
    flag: formValue(prefix+'Fl'),
    emoji: formValue(prefix+'Em'),
    inStock: formChecked(prefix+'St')
  };
  if(includeId) payload.id = formValue(prefix+'Id');
  return payload;
}
window.addProd=async function(){
  const payload = productPayload('f', false);
  if(!payload.name || !Number.isFinite(payload.price)){ alert('Заполните название и цену'); return; }
  try{
    const data = await apiRequest(API.products, {method:'POST', body:payload});
    applyCatalog(data, 'База данных');
    ['fNm','fPr','fPk','fOr','fFl','fEm'].forEach(id=>{ if($(id)) $(id).value=''; });
    if($('fSt')) $('fSt').checked=true;
    toast('✅ Позиция добавлена');
  }catch(e){ alert(e.message || 'Не удалось добавить товар'); }
};
window.delP=async function(id){
  if(!confirm('Удалить позицию?')) return;
  try{
    const data = await apiRequest(API.products, {method:'DELETE', body:{id}});
    delete cart[id];
    saveCart();
    applyCatalog(data, 'База данных');
    toast('Позиция удалена');
  }catch(e){ alert(e.message || 'Не удалось удалить товар'); }
};
window.openEdit=function(id){ const p=products.find(x=>x.id===id); if(!p) return; const map={eId:'id',eNm:'name',eCat:'category',ePr:'price',eUn:'unit',ePk:'packaging',eOr:'origin',eFl:'flag',eEm:'emoji'}; Object.entries(map).forEach(([fid,key])=>{ if($(fid)) $(fid).value=p[key] ?? ''; }); if($('eSt')) $('eSt').checked=p.inStock; $('editOv').classList.add('on'); };
window.closeEdit=function(){ $('editOv').classList.remove('on'); };
window.saveEdit=async function(){
  const payload = productPayload('e', true);
  if(!payload.id || !payload.name || !Number.isFinite(payload.price)){ alert('Проверьте название и цену'); return; }
  try{
    const data = await apiRequest(API.products, {method:'PUT', body:payload});
    applyCatalog(data, 'База данных');
    window.closeEdit();
    toast('✅ Сохранено');
  }catch(e){ alert(e.message || 'Не удалось сохранить товар'); }
};
function fillSettings(){ const c=getConfig(); [['sCo',c.company],['sPh',c.phone],['sTg',c.tg],['sWa',c.wa],['sMax',c.mx],['ss1',c.stat1]].forEach(([id,v])=>{ if($(id)) $(id).value=v || ''; }); }
window.saveCfg2=async function(){ const cfg={company:formValue('sCo'), phone:formValue('sPh'), tg:formValue('sTg'), wa:formValue('sWa'), mx:formValue('sMax'), stat1:formValue('ss1'), stat2:'500+'}; try{ await saveConfig(cfg); }catch(e){ alert(e.message || 'Не удалось сохранить настройки'); } };
window.changePwd=async function(){
  const v=formValue('newPwd');
  if(!v || v.length<4){ alert('Минимум 4 символа'); return; }
  try{
    await apiRequest(API.auth, {method:'POST', body:{action:'change_password', password:v}});
    $('newPwd').value='';
    toast('✅ Пароль изменён');
  }catch(e){ alert(e.message || 'Не удалось изменить пароль'); }
};
window.expData=function(){ const data={products, config:getConfig(), exported:new Date().toISOString(), note:'Бэкап товаров и настроек из MySQL.'}; downloadBlob(JSON.stringify(data,null,2),'zdravie-backup-'+new Date().toISOString().slice(0,10)+'.json','application/json;charset=utf-8'); };
window.exportProductsJson=function(){ downloadBlob(JSON.stringify(products,null,2),'products.json','application/json;charset=utf-8'); };
window.impData=function(input){
  const file=input.files?.[0]; if(!file) return;
  const reader=new FileReader();
  reader.onload=async e=>{
    try{
      const data=JSON.parse(e.target.result);
      const list=Array.isArray(data) ? data : data.products;
      if(!Array.isArray(list)) throw new Error('bad format');
      if(confirm('Импортировать '+list.length+' позиций?')){
        if(data.config) await saveConfig(data.config);
        const response = await apiRequest(API.products, {method:'POST', body:{action:'replace', products:list}});
        applyCatalog(response, 'База данных');
        toast('✅ Импорт выполнен');
      }
    }catch(err){ alert(err.message || 'Ошибка файла или неверный формат'); }
    input.value='';
  };
  reader.readAsText(file);
};
function toast(msg){ const t=$('toast'); if(!t) return; t.textContent=msg; t.classList.add('on'); setTimeout(()=>t.classList.remove('on'),2600); }
function bind(){
  $('searchInput')?.addEventListener('input', debounce(e=>{ currentQuery=e.target.value.trim(); renderTable(); }, 180));
  $('sortSelect')?.addEventListener('change', e=>{ currentSort=e.target.value; renderTable(); });
  $('adminSearch')?.addEventListener('input', e=>{ adminQuery=e.target.value; renderAdminList(); });
  ['adminOv','orderOv','editOv'].forEach(id=>{ const el=$(id); if(el) el.addEventListener('click', e=>{ if(e.target===el){ if(id==='adminOv') window.closeAdmin(); else if(id==='orderOv') window.closeOrder(); else window.closeEdit(); }}); });
  ['oName','oPhone','oPerson','oAddress','oDelivery','oType','oComment','oContract','oVat','oDocs'].forEach(id=>{ const el=$(id); if(el) el.addEventListener('input', updateOrderWarning); });
}
function debounce(fn, ms){ let timer; return (...args)=>{ clearTimeout(timer); timer=setTimeout(()=>fn(...args),ms); }; }

document.addEventListener('DOMContentLoaded', ()=>{ bind(); applyConfig(); updateBadge(); initProducts(); });
})();
