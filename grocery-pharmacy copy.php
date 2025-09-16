<?php 
    session_start();
    include ('config.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Grocery Store - Pharmacy</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; color: #000; background: #fff; }
    a { color: inherit; text-decoration: none; }

    /* Topbar */
    .topbar { position: sticky; top: 0; z-index: 50; width: 100%; height: 83px; background: rgba(0,0,0,0.68); display: flex; align-items: center; }
    .container { width: 100%; max-width: 1440px; margin: 0 auto; padding: 0 24px; }
    .topbar-content { display: grid; grid-template-columns: 220px 1fr 280px 180px; align-items: center; gap: 16px; }

    .location { display: flex; align-items: center; gap: 12px; color: #fff; }
    .location-text { font-weight: 700; font-size: 14px; }

    .search-wrap { display: flex; align-items: center; justify-content: center; }
    .search { width: 100%; max-width: 604px; height: 40px; background: rgba(217,217,217,0.62); border: 1px solid rgba(0,0,0,0.37); border-radius: 50px; display: grid; grid-template-columns: 1fr 48px; overflow: hidden; }
    .search input { border: none; background: transparent; padding: 0 20px; color: #000; font-size: 18px; outline: none; }
    .search-btn { display: flex; align-items: center; justify-content: center; color: #fff; }

    .cart-wrap { display: flex; align-items: center; justify-content: flex-end; gap: 12px; color: #fff; cursor: pointer; }
    .cart-icon { position: relative; font-size: 28px; }
    .cart-badge { position: absolute; right: -6px; top: -6px; width: 16px; height: 16px; background: #BA0B34; color: #fff; font-size: 12px; font-weight: 700; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .cart-text { font-weight: 700; font-size: 24px; }

    .auth { display: flex; justify-content: flex-end; }
    .auth a { color: #fff; font-weight: 700; font-size: 16px; }

    /* Nav */
    .sectionbar { width: 100%; background: #252F3D; height: 40px; display: flex; align-items: center; }
    .sectionbar-inner { display: flex; align-items: center; gap: 28px; color: #fff; }
    .nav-link { font-weight: 500; font-size: 16px; cursor: pointer; }
    .nav-link.active { text-decoration: underline; text-underline-offset: 4px; }

    /* Promo Banner */
    .promo { width: 100%; max-width: 1463px; margin: 24px auto; position: relative; overflow: hidden; border-radius: 8px; }
    .slides { display: flex; transition: transform 0.5s ease; }
    .slide { min-width: 100%; height: 278px; display: flex; justify-content: center; align-items: center; background: #eee; }
    .slide img { width: 100%; height: 100%; object-fit: contain; }
    .carousel-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; border: 2px solid #fff; color: #fff; border-radius: 50%; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .carousel-btn.left { left: 10px; }
    .carousel-btn.right { right: 10px; }

    /* Categories */
    .cats { width: 100%; height: 154px; background: rgba(217,217,217,0.65); display: flex; align-items: center; justify-content: center; gap: 40px; padding: 0 24px; }
    .cat-card { width: 128px; height: 115px; display: flex; flex-direction: column; align-items: center; cursor: pointer; position: relative; }
    .cat-card .label { font-weight: 700; font-size: 20px; }
    .cat-card.active::before { content: ''; position: absolute; top: -10px; left: 50%; transform: translateX(-50%); width: 166px; height: 154px; background: rgba(0,0,0,0.68); border-radius: 50%; z-index: -1; }

    /* Grid */
    .grid { display: grid; grid-template-columns: repeat(6, 200px); gap: 24px; padding: 0 24px 40px; }
    .card { width: 200px; border-radius: 10px; background: #d9d9d9; overflow: hidden; position: relative; }
    .card .thumb { width: 100%; height: 200px; background: #ccc center/cover no-repeat; }
    .card .meta { background: #fff; padding: 10px; }
    .card .name { font-weight: 700; font-size: 20px; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .card .price { font-weight: 700; font-size: 15px; color: #DE3925; }
    .card .add { position: absolute; left: 10px; bottom: 10px; height: 30px; padding: 0 12px 0 36px; border-radius: 100px; background: #F9A41E; font-weight: 700; font-size: 16px; border: none; cursor: pointer; }
    .card .add::before { content: '+'; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 22px; height: 22px; border-radius: 999px; background: rgba(0,0,0,0.15); display: grid; place-items: center; }

    /* Footer */
    .footer { margin-top: 24px; background: #1A1A1A; color: #fff; }
    .back-top { text-align: center; padding: 16px; font-weight: 700; font-size: 24px; cursor: pointer; }
    .footer-inner { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; max-width: 1440px; margin: 0 auto; padding: 40px 24px; }

    /* Toast */
    .toast { position: fixed; top: 20px; right: 20px; background: #252F3D; color: #fff; padding: 12px 16px; border-radius: 8px; transform: translateX(120%); transition: transform .3s ease; z-index: 1000; }
    .toast.show { transform: translateX(0); }

    /* Responsive */
    @media (max-width: 1200px) {
      .topbar-content { grid-template-columns: 220px 1fr 220px 0; }
      .auth { display: none; }
    }
    @media (max-width: 768px) {
      .cats { flex-wrap: wrap; height: auto; row-gap: 20px; }
      .grid { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="topbar">
    <div class="container topbar-content">
      <div class="location">
        <i class="material-icons">place</i>
        <div class="location-text">Location 140301 RD</div>
      </div>
      <div class="search-wrap">
        <div class="search" role="search">
          <input id="searchInput" type="text" placeholder="Search here....." aria-label="Search here" />
          <div class="search-btn"><i class="material-icons">search</i></div>
        </div>
      </div>
      <div class="cart-wrap" onclick="openCart()">
        <div class="cart-icon">
          <i class="material-icons">shopping_cart</i>
          <span id="cartBadge" class="cart-badge">0</span>
        </div>
        <div class="cart-text">Cart</div>
      </div>
      <div class="auth">
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['first_name'])): ?>
          <span style="color:#fff;font-weight:700;font-size:16px;">
            <i class="material-icons">account_circle</i>
            Hello, <?php echo htmlspecialchars($_SESSION['first_name']); ?>
          </span>
          <a href="logout.php" style="margin-left:15px;color:#fff;font-size:14px;">
            <i class="material-icons">logout</i> Logout
          </a>
        <?php else: ?>
          <a href="user_login.php"><i class="material-icons">login</i> Login/sign up · Account</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- Navigation -->
  <nav class="sectionbar">
    <div class="container sectionbar-inner">
      <i class="material-icons">menu</i>
      <div class="nav-link active" data-filter="all">All</div>
      <div class="nav-link" data-filter="deal">Today's deal</div>
      <div class="nav-link" data-filter="best">Best selling</div>
      <div class="nav-link" data-filter="gift">Gift cards</div>
      <div class="nav-link" data-filter="service">Customer service</div>
    </div>
  </nav>

  <!-- Promo -->
  <section class="promo container">
    <div id="slides" class="slides">
      <div class="slide"><img src="assets/grocery_banner 5.png" alt="Banner 1"></div>
      <div class="slide"><img src="assets/grocery_banner 3.png" alt="Banner 2"></div>
      <div class="slide"><img src="assets/grocery_banner 4.png" alt="Banner 3"></div>
      <div class="slide"><img src="assets/grocery_banner 1.png" alt="Banner 4"></div>
      <div class="slide"><img src="assets/grocery_banner 2.png" alt="Banner 5"></div>
    </div>
    <div class="carousel-btn left" id="btnPrev">◀</div>
    <div class="carousel-btn right" id="btnNext">▶</div>
  </section>

  <!-- Categories -->
  <section class="cats container" id="cats">
    <div class="cat-card" onclick="window.location.href='grocery copy.php'">
      <i class="material-icons" style="font-size:40px;color:#2b7a78;">emoji_food_beverage</i>
      <div class="label">Food & Fruits</div>
    </div>
    <div class="cat-card" onclick="window.location.href='grocery s&d.php'">
      <i class="material-icons" style="font-size:40px;color:#2b7a78;">local_drink</i>
      <div class="label">Snacks & Drinks</div>
    </div>
    <div class="cat-card" onclick="window.location.href='grocery stationary.php'">
      <i class="material-icons" style="font-size:40px;color:#2b7a78;">edit</i>
      <div class="label">Stationary</div>
    </div>
    <div class="cat-card active" onclick="window.location.href='grocery pharmacy.php'">
      <i class="material-icons" style="font-size:40px;color:#2b7a78;">medical_services</i>
      <div class="label">Pharmacy</div>
    </div>
  </section>

  <h3 class="section-title container">Essential Health & First Aid</h3>
  <section class="grid container" id="grid"></section>

  <!-- Footer -->
  <footer class="footer">
    <div class="back-top" id="backTop">Back to top</div>
    <div class="footer-inner">
      <div><h4>Get to Know Us</h4><ul><li>About us</li><li>Careers</li><li>Press</li></ul></div>
      <div><h4>Connect with us</h4><ul><li>Twitter</li><li>Instagram</li><li>Facebook</li></ul></div>
      <div><h4>Make Money with Us</h4><ul><li>Sell on Aisle247</li><li>Sell under Aisle247</li><li>Protect and build your brand</li></ul></div>
    </div>
  </footer>

  <div id="toast" class="toast"></div>

  <script>
    // --- Cart setup ---
    let cart = JSON.parse(localStorage.getItem('groceryCart')) || [];
    let cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);

    // --- Pharmacy products ---
    const PRODUCTS = [
      { name: 'Band-Aids', price: 3.50, img: 'assets/pharmacy_bandaids.png', cat: 'pharmacy' },
      { name: 'Pain Reliever', price: 8.99, img: 'assets/pharmacy_painrelief.png', cat: 'pharmacy' },
      { name: 'Antiseptic', price: 4.25, img: 'assets/pharmacy_antiseptic.png', cat: 'pharmacy' },
      { name: 'Thermometer', price: 12.50, img: 'assets/pharmacy_thermometer.png', cat: 'pharmacy' },
      { name: 'First Aid Kit', price: 25.00, img: 'assets/pharmacy_firstaidkit.png', cat: 'pharmacy' },
      { name: 'Cough Syrup', price: 6.75, img: 'assets/pharmacy_coughsyrup.png', cat: 'pharmacy' },
      { name: 'Vitamins', price: 15.99, img: 'assets/pharmacy_vitamins.png', cat: 'pharmacy' },
      { name: 'Gauze Pads', price: 5.25, img: 'assets/pharmacy_gauze.png', cat: 'pharmacy' },
      { name: 'Aspirin', price: 4.50, img: 'assets/pharmacy_aspirin.png', cat: 'pharmacy' },
      { name: 'Ibuprofen', price: 5.25, img: 'assets/pharmacy_ibuprofen.png', cat: 'pharmacy' },
      { name: 'Antibiotic Ointment', price: 6.50, img: 'assets/pharmacy_antibiotic.png', cat: 'pharmacy' },
      { name: 'Cold Medicine', price: 7.25, img: 'assets/pharmacy_cold.png', cat: 'pharmacy' },
      { name: 'Allergy Relief', price: 8.75, img: 'assets/pharmacy_allergy.png', cat: 'pharmacy' },
      { name: 'Digestive Aid', price: 6.00, img: 'assets/pharmacy_digestive.png', cat: 'pharmacy' },
      { name: 'Eye Drops', price: 5.50, img: 'assets/pharmacy_eyedrops.png', cat: 'pharmacy' },
      { name: 'Hand Sanitizer', price: 3.25, img: 'assets/pharmacy_sanitizer.png', cat: 'pharmacy' },
    ];

    // --- DOM refs ---
    const grid = document.getElementById('grid');
    const searchInput = document.getElementById('searchInput');
    const cartBadge = document.getElementById('cartBadge');
    const toast = document.getElementById('toast');

    let currentFilter = 'pharmacy';

    // --- Render products ---
    function renderProducts() {
      grid.innerHTML = '';
      const q = (searchInput?.value || '').toLowerCase();
      PRODUCTS.filter(p => (currentFilter === 'all' || p.cat === currentFilter) && (!q || p.name.toLowerCase().includes(q)))
        .forEach(p => {
          const card = document.createElement('div');
          card.className = 'card';
          card.innerHTML = `
            <div class="thumb" style="background-image:url('${p.img}')"></div>
            <div class="meta">
              <div class="name">${p.name}</div>
              <div class="price">$${p.price.toFixed(2)}</div>
            </div>
            <button class="add" onclick="addToCart('${p.name}')">Add</button>
          `;
          grid.appendChild(card);
        });
    }

    // --- Cart handling ---
    function addToCart(name) {
      const item = cart.find(i => i.name === name);
      if (item) item.quantity++;
      else cart.push({ name, quantity: 1 });
      localStorage.setItem('groceryCart', JSON.stringify(cart));
      cartCount++;
      cartBadge.textContent = cartCount;
      showToast(`${name} added to cart`);
    }

    function showToast(msg) {
      toast.textContent = msg;
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 2000);
    }

    function openCart() {
      alert('Cart functionality goes here.');
    }

    // --- Nav ---
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        currentFilter = link.dataset.filter;
        renderProducts();
      });
    });

    // --- Back to top ---
    document.getElementById('backTop').addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // --- Banner carousel ---
    const slides = document.getElementById('slides');
    const slideCount = slides.children.length;
    let slideIndex = 0;

    function go(i) {
      slideIndex = (i + slideCount) % slideCount;
      slides.style.transform = `translateX(-${slideIndex * 100}%)`;
    }

    document.getElementById('btnPrev').addEventListener('click', () => go(slideIndex - 1));
    document.getElementById('btnNext').addEventListener('click', () => go(slideIndex + 1));
    setInterval(() => go(slideIndex + 1), 5000);
    go(0);

    // --- Init ---
    renderProducts();
    cartBadge.textContent = cartCount;
    if (searchInput) searchInput.addEventListener('input', renderProducts);
  </script>
</body>
</html>
