<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,viewport-fit=cover,maximum-scale=1">
<meta name="theme-color" content="#1A3012">
<title>Здравие — свежие продукты и надёжные поставки для бизнеса</title>
<meta name="description" content="МП Здравие — поставщик свежих продуктов для кафе, ресторанов, магазинов, столовых и оптовых клиентов. Овощи, фрукты, мясо, рыба, бакалея, специи и хозтовары.">
<meta name="keywords" content="поставка продуктов, продукты для ресторанов, продукты для кафе, овощи оптом, фрукты оптом, ФУД Сити, поставщик продуктов Москва">
<meta property="og:title" content="МП Здравие — свежие продукты для бизнеса">
<meta property="og:description" content="Поставки овощей, фруктов, мяса, рыбы, бакалеи, специй и сопутствующих товаров для HoReCa и оптовых клиентов.">
<meta property="og:image" content="assets/logo.png">
<meta property="og:type" content="website">
<link rel="icon" href="assets/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,400;1,600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css?v=20260528-1">
</head>
<body>

<header>
  <div class="hdr">
    <a href="#" class="logo-wrap">
      <img src="assets/logo.png" loading="lazy" alt="Здравие" class="logo-img">
      <div>
        <div class="logo-name" id="siteName">Здравие</div>
        <div class="logo-tag">Свежие продукты · Надёжные поставки</div>
      </div>
    </a>
    <nav class="hdr-nav">
      <a href="#about" class="nav-a">О компании</a>
      <a href="#price" class="nav-a">Прайс-лист</a>
    </nav>
    <div class="hdr-right">
      <a id="maxBtn" href="#" class="cbtn bmax" target="_blank"><span style="display:inline-flex;align-items:center;justify-content:center;background:#fff;color:#7B2FFF;font-family:Arial,sans-serif;font-size:9px;font-weight:900;border-radius:3px;padding:1px 4px;line-height:1.2;flex-shrink:0">MAX</span><span>MAX</span></a>
      <a id="tgBtn" href="#" class="cbtn btg" target="_blank"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.16 14.48l-2.95-.924c-.64-.204-.654-.64.136-.951l11.527-4.445c.535-.194 1.003.131.69 1.088z"/></svg><span>TG</span></a>
      <a id="waBtn" href="#" class="cbtn bwa" target="_blank"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.129.332.202c.043.073.043.423-.101.827z"/></svg><span>WA</span></a>
      <a id="phBtn" href="#" class="cbtn bph"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg><span id="hdrPhone">Позвонить</span></a>
      <button class="menu-toggle" id="menuToggle" onclick="toggleMobileNav()" aria-label="Меню">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
  <div class="mobile-nav" id="mobileNav">
    <a href="#about" class="nav-a" onclick="closeMobileNav()">О компании</a>
    <a href="#price" class="nav-a" onclick="closeMobileNav()">Прайс-лист</a>
    <div class="mob-contacts">
      <a id="maxBtnM" href="#" class="cbtn bmax" target="_blank"><span style="display:inline-flex;align-items:center;justify-content:center;background:#fff;color:#7B2FFF;font-family:Arial,sans-serif;font-size:9px;font-weight:900;border-radius:3px;padding:1px 4px;line-height:1.2;flex-shrink:0">MAX</span> MAX</a>
      <a id="tgBtnM" href="#" class="cbtn btg" target="_blank"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.16 14.48l-2.95-.924c-.64-.204-.654-.64.136-.951l11.527-4.445c.535-.194 1.003.131.69 1.088z"/></svg> Telegram</a>
      <a id="waBtnM" href="#" class="cbtn bwa" target="_blank"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.129.332.202c.043.073.043.423-.101.827z"/></svg> WhatsApp</a>
      <a id="phBtnM" href="#" class="cbtn bph"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg> Телефон</a>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-in">
    <div class="hero-left">
      <div class="hero-eyebrow">Поставщик продуктов для HoReCa и производств</div>
      <h1>Здравие</h1>
      <div class="hero-slogan">«Свежие продукты. Надёжные поставки.»</div>
      <div class="hero-stat-single">
        <div class="stat-n" id="s1">150+</div>
        <div class="stat-info">
          <div class="stat-l">постоянных</div>
          <div class="stat-l">клиентов</div>
        </div>
      </div>
    </div>
    <div class="hero-logo-block">
      <img src="assets/logo.png" loading="lazy" alt="Здравие" class="hero-logo">
    </div>
  </div>
</section>

<div class="sdiv"></div>

<!-- FEATURES -->
<div class="features-sec">
  <div class="features-in">
    <div class="fitem"><div class="fitem-icon">🏭</div><div class="fitem-t">Собственный склад на ФУД Сити</div><div class="fitem-d">Прямые поставки с нашего склада без посредников</div></div>
    <div class="fitem"><div class="fitem-icon">🚛</div><div class="fitem-t">Собственный грузовой автопарк</div><div class="fitem-d">Постоянные, отлаженные поставки без задержек</div></div>
    <div class="fitem"><div class="fitem-icon">📋</div><div class="fitem-t">Актуальный прайс</div><div class="fitem-d">Обновляется в режиме реального времени</div></div>
    <div class="fitem"><div class="fitem-icon">🤝</div><div class="fitem-t">Удобный формат взаимодействия</div><div class="fitem-d">Работаем под задачи вашего бизнеса</div></div>
  </div>
</div>

<div class="sdiv"></div>

<!-- ABOUT -->
<section class="about-sec" id="about">
  <div class="about-in">
    <div class="section-label">О компании</div>
    <h2 class="section-title">Поставщик, на которого<br>можно опереться</h2>
    <div class="about-grid">
      <p class="about-p">Здравие — поставщик свежих продуктов и сопутствующих товарных категорий для кафе, ресторанов, магазинов, столовых и оптовых клиентов.</p>
      <p class="about-p">Наша задача — обеспечить клиентов качественной продукцией, стабильными поставками и удобным сервисом для ежедневной работы бизнеса.</p>
    </div>
    <div class="about-quote">
      <p>Свежие продукты для бизнеса — стабильно, удобно и надёжно.</p>
      <span>Главная идея Здравие</span>
    </div>
  </div>
</section>

<div class="sdiv"></div>

<!-- CTA BANNER -->
<div class="cta-banner">
  <div class="cta-banner-in">
    <div class="cta-text"><strong>Соберите заказ</strong> и отправьте нам его<br>в удобном формате из корзины</div>
    <div class="cta-arrows">
      <div class="arrow-dn">↓</div>
      <div class="arrow-dn">↓</div>
      <div class="arrow-dn">↓</div>
    </div>
  </div>
</div>

<!-- PRICE LIST -->
<section class="price-sec" id="price">
  <div class="price-in">
    <div class="price-head">
      <div>
        <div class="section-label">Каталог</div>
        <h2 class="section-title" style="margin-bottom:0">Прайс-лист</h2>
      </div>
      <span class="upd" id="lastUpdated">Загрузка...</span>
    </div>
    <div class="ctrl-bar">
      <div class="search-wrap">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" class="sinput" id="searchInput" placeholder="Поиск по названию, стране, категории...">
      </div>
      <div class="price-actions">
        <select class="sort-select" id="sortSelect" aria-label="Сортировка прайса">
          <option value="name">По названию</option>
          <option value="priceAsc">Сначала дешевле</option>
          <option value="priceDesc">Сначала дороже</option>
          <option value="stock">Только в наличии</option>
        </select>
        <button class="download-btn" onclick="downloadCSV()">⬇ Скачать CSV</button>
      </div>
      <div class="cattabs" id="categoryTabs"></div>
    </div>
    <div class="tbl-outer">
      <table class="ptbl">
        <thead>
          <tr>
            <th>Наименование</th><th>Категория</th><th>Страна</th>
            <th>Наличие</th><th class="tr">Цена</th><th class="tc">В корзину</th>
          </tr>
        </thead>
        <tbody id="priceBody"></tbody>
      </table>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-in">
    <div>
      <div class="footer-brand">Здравие</div>
      <div class="footer-slogan">Свежие продукты. Надёжные поставки.</div>
      <div class="footer-legal">
        © 2025 ООО «МП Здравие» · Все права защищены<br>
        <a id="footerPhone" href="#">+7 (XXX) XXX-XX-XX</a>
      </div>
    </div>
    <div class="footer-contacts">
      <a id="maxBtnF" href="#" class="big-btn bmax-big" target="_blank">
        <span style="display:inline-flex;align-items:center;justify-content:center;background:#fff;color:#7B2FFF;font-family:Arial,sans-serif;font-size:11px;font-weight:900;border-radius:4px;padding:2px 6px;line-height:1.2">MAX</span>
        MAX
      </a>
      <a id="tgBtnF" href="#" class="big-btn btg-big" target="_blank"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.16 14.48l-2.95-.924c-.64-.204-.654-.64.136-.951l11.527-4.445c.535-.194 1.003.131.69 1.088z"/></svg> Telegram</a>
      <a id="waBtnF" href="#" class="big-btn bwa-big" target="_blank"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.129.332.202c.043.073.043.423-.101.827z"/></svg> WhatsApp</a>
      <a href="#" class="admin-link" onclick="openAdmin();return false;">⚙ Управление прайсом</a>
    </div>
  </div>
</footer>

<!-- FLOATING CART -->
<button class="float-cart" onclick="openCart()" aria-label="Корзина">
  🛒
  <span class="float-cart-badge" id="floatBadge">0</span>
</button>

<!-- CART DRAWER -->
<div class="overlay" id="cartOv" onclick="closeCart()"></div>
<div class="drawer" id="cartDrawer">
  <div class="drw-hdr"><h2>Корзина</h2><button class="drw-close" onclick="closeCart()">✕</button></div>
  <div class="drw-body" id="cartBody"></div>
  <div class="drw-foot" id="cartFoot" style="display:none">
    <div class="total-row">
      <div><div class="total-lbl">Предварительная сумма</div><div class="total-items" id="cTotItems"></div></div>
      <div class="total-val" id="cTotVal">0 ₽</div>
    </div>
    <button class="order-btn" onclick="openOrder()">Оформить заявку →</button>
  </div>
</div>

<!-- ORDER MODAL -->
<div class="modal-ov" id="orderOv">
  <div class="modal">
      <div class="modal-hdr"><h2>Оформление заявки</h2><button class="modal-x" onclick="closeOrder()">✕</button></div>
    <div class="modal-body">
      <div class="om-preview" id="orderPrev"></div>
      <div class="omfg"><label>Название заведения / компания *</label><input type="text" id="oName" placeholder="Кафе Солнышко / ООО Ромашка" autocomplete="organization"></div>
      <div class="omfg"><label>Контактное лицо</label><input type="text" id="oPerson" placeholder="Иван Петров" autocomplete="name"></div>
      <div class="omfg"><label>Телефон *</label><input type="tel" id="oPhone" placeholder="+7 (XXX) XXX-XX-XX" autocomplete="tel"></div>
      <div class="omfg"><label>Адрес доставки</label><input type="text" id="oAddress" placeholder="Москва, улица, дом"></div>
      <div class="omfg"><label>Дата и время доставки</label><input type="text" id="oDelivery" placeholder="Например: завтра с 9:00 до 12:00"></div>
      <div class="omfg"><label>Тип клиента</label><select id="oType" style="width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:7px;font-family:'Manrope',sans-serif;font-size:13px;color:var(--brown);background:var(--white);outline:none"><option>Кафе</option><option>Ресторан</option><option>Магазин</option><option>Столовая</option><option>Оптовый клиент</option><option>Другое</option></select></div>
      <div class="checkbox-row">
        <label><input type="checkbox" id="oContract"> Нужен договор</label>
        <label><input type="checkbox" id="oVat"> Работа с НДС</label>
        <label><input type="checkbox" id="oDocs"> Закрывающие документы</label>
      </div>
      <div class="omfg"><label>Комментарий</label><textarea id="oComment" rows="2" placeholder="Пожелания по заказу, замены, особенности разгрузки..." style="resize:vertical"></textarea></div>
      <div class="om-btns">
        <button class="btn-tg" onclick="sendTg()"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.16 14.48l-2.95-.924c-.64-.204-.654-.64.136-.951l11.527-4.445c.535-.194 1.003.131.69 1.088z"/></svg> Telegram</button>
        <button class="btn-wa" onclick="sendWa()"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.129.332.202c.043.073.043.423-.101.827z"/></svg> WhatsApp</button>
        <button class="btn-mx" onclick="sendMx()"><span style="background:#fff;color:#7B2FFF;font-family:Arial;font-size:9px;font-weight:900;border-radius:3px;padding:1px 4px">MAX</span> MAX</button>
        <button class="copy-btn" onclick="copyOrder()">📋 Скопировать</button>
      </div>
      <p class="om-note">Откроется мессенджер с готовым текстом заказа — нажмите «Отправить».</p>
    </div>
  </div>
</div>

<!-- ADMIN MODAL -->
<div class="modal-ov" id="adminOv">
  <div class="modal adm-modal">
    <div id="pwdView">
      <div class="modal-hdr"><h2>Управление прайсом</h2><button class="modal-x" onclick="closeAdmin()">✕</button></div>
      <div class="pwdsc">
        <div style="font-size:42px;margin-bottom:12px">🔐</div>
        <h3>Панель управления</h3>
        <p>Введите пароль для редактирования</p>
        <div class="pwdwrap">
          <input type="password" class="pwdinput" id="pwdInput" placeholder="Пароль" onkeydown="if(event.key==='Enter')checkPwd()">
          <button class="bprim" onclick="checkPwd()">Войти</button>
        </div>
        <div class="pwderr" id="pwdErr">Неверный пароль</div>
      </div>
    </div>
    <div id="adminView" style="display:none">
      <div class="modal-hdr"><h2>Управление прайсом</h2><button class="modal-x" onclick="closeAdmin()">✕</button></div>
      <div class="atabs-bar">
        <button class="atab on" onclick="aTab('list',this)">📋 Список</button>
        <button class="atab" onclick="aTab('add',this)">➕ Добавить</button>
        <button class="atab" onclick="aTab('cfg',this)">🌐 Настройки</button>
        <button class="atab" onclick="aTab('bkp',this)">💾 Бэкап</button>
        <button class="atab" onclick="logoutAdmin()">Выйти</button>
      </div>
      <div class="adm-body">
        <div id="tList">
          <div class="ah"><span id="aCnt"></span><select id="aCatF" onchange="rAList()"><option value="all">Все категории</option></select></div>
          <div class="admin-search-wrap"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg><input type="text" class="admin-sinput" id="adminSearch" placeholder="Поиск по названию или категории..."></div>
          <div class="alist-wrap"><div class="alist" id="aList"></div></div>
        </div>
        <div id="tAdd" style="display:none">
          <div class="fgrid">
            <div class="fg"><label>Название</label><input type="text" id="fNm" placeholder="Яблоки Голден"></div>
            <div class="fg"><label>Категория</label><select id="fCat"><option>Фрукты</option><option>Овощи</option><option>Ягоды</option><option>Зелень</option><option>Салаты</option><option>Мясо</option><option>Птица</option><option>Рыба</option><option>Морепродукты</option><option>Орехи</option><option>Специи</option><option>Бакалея</option><option>Хозбыт</option></select></div>
            <div class="fg"><label>Цена (₽)</label><input type="number" id="fPr" placeholder="100"></div>
            <div class="fg"><label>Единица</label><select id="fUn"><option>кг</option><option>шт</option><option>л</option><option>упак</option><option>г</option></select></div>
            <div class="fg"><label>Фасовка</label><input type="text" id="fPk" placeholder="коробка 20 кг"></div>
            <div class="fg"><label>Страна</label><input type="text" id="fOr" placeholder="Россия"></div>
            <div class="fg"><label>Флаг 🇷🇺</label><input type="text" id="fFl" placeholder="🇷🇺" maxlength="4"></div>
            <div class="fg"><label>Эмодзи 🍎</label><input type="text" id="fEm" placeholder="🍎" maxlength="4"></div>
            <div class="fg ck full"><input type="checkbox" id="fSt" checked><label for="fSt">В наличии</label></div>
          </div>
          <button class="bprim" onclick="addProd()">+ Добавить позицию</button>
        </div>
        <div id="tCfg" style="display:none">
          <div class="fgrid">
            <div class="fg full"><label>Название в шапке</label><input type="text" id="sCo" placeholder="Здравие"></div>
            <div class="fg"><label>Телефон</label><input type="text" id="sPh" placeholder="+7 (XXX) XXX-XX-XX"></div>
            <div class="fg"><label>Telegram</label><input type="text" id="sTg" placeholder="@zdravie или https://t.me/..."></div>
            <div class="fg"><label>WhatsApp (номер)</label><input type="text" id="sWa" placeholder="+79001234567"></div>
            <div class="fg full"><label>MAX (ссылка или @ник)</label><input type="text" id="sMax" placeholder="@zdravie или https://max.ru/..."></div>
            <div class="fg"><label>Клиентов (герой)</label><input type="text" id="ss1" placeholder="150+"></div>
          </div>
          <button class="bprim" onclick="saveCfg2()">Сохранить</button>
          <hr style="margin:14px 0;border:none;border-top:1px solid var(--border)">
          <div class="fg" style="max-width:300px;margin-bottom:12px"><label>Новый пароль (мин. 4 символа)</label><input type="password" id="newPwd" placeholder="Новый пароль"></div>
          <button class="bprim" onclick="changePwd()">Изменить пароль</button>
        </div>
        <div id="tBkp" style="display:none">
          <p style="font-size:13px;color:var(--mid);margin-bottom:14px;line-height:1.7">Бэкап сохраняет товары и настройки из базы. Импорт заменяет текущий список товаров в MySQL.</p>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <button class="bprim" onclick="expData()">📥 Скачать бэкап</button>
            <button class="bprim" onclick="exportProductsJson()">⬇ Экспорт products.json</button>
            <label class="bprim" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px">📤 Загрузить бэкап<input type="file" accept=".json" onchange="impData(this)" style="display:none"></label>
          </div>
          <p style="font-size:11px;color:var(--light);margin-top:10px">⚠ При импорте текущий список полностью заменяется.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-ov" id="editOv">
  <div class="modal" style="max-width:450px">
    <div class="modal-hdr"><h2>Редактировать позицию</h2><button class="modal-x" onclick="closeEdit()">✕</button></div>
    <div class="adm-body">
      <input type="hidden" id="eId">
      <div class="fgrid">
        <div class="fg"><label>Название</label><input type="text" id="eNm"></div>
        <div class="fg"><label>Категория</label><select id="eCat"><option>Фрукты</option><option>Овощи</option><option>Ягоды</option><option>Зелень</option><option>Салаты</option><option>Мясо</option><option>Птица</option><option>Рыба</option><option>Морепродукты</option><option>Орехи</option><option>Специи</option><option>Бакалея</option><option>Хозбыт</option></select></div>
        <div class="fg"><label>Цена (₽)</label><input type="number" id="ePr"></div>
        <div class="fg"><label>Единица</label><select id="eUn"><option>кг</option><option>шт</option><option>л</option><option>упак</option><option>г</option></select></div>
        <div class="fg"><label>Фасовка</label><input type="text" id="ePk"></div>
        <div class="fg"><label>Страна</label><input type="text" id="eOr"></div>
        <div class="fg"><label>Флаг</label><input type="text" id="eFl" maxlength="4"></div>
        <div class="fg"><label>Эмодзи</label><input type="text" id="eEm" maxlength="4"></div>
        <div class="fg ck full"><input type="checkbox" id="eSt"><label for="eSt">В наличии</label></div>
      </div>
      <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:14px">
        <button class="bsec" onclick="closeEdit()">Отмена</button>
        <button class="bprim" onclick="saveEdit()">Сохранить</button>
      </div>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<script src="js/app.js?v=20260528-1"></script>
</body>
</html>
