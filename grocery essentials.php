<?php
session_start();
include("config.php"); // DB connection

// --- normalize category slug helper (used server-side) ---
function slugify($s){
    $s = strtolower(trim((string)$s));
    $s = preg_replace('/\s+/', '-', $s);       // spaces -> hyphen
    $s = preg_replace('/[^a-z0-9\-]/', '', $s); // remove unsafe chars
    return $s;
}

// Cart count for badge
$cartCount = 0;
if (!empty($_SESSION['cart'])){
    foreach ($_SESSION['cart'] as $qty){
        $cartCount += (int)($qty['qty'] ?? 0);
    }
}

// Determine current category: prefer ?cat=..., otherwise default for this page = 'essential'
$currentCat = 'essential';
if (!empty($_GET['cat'])) {
    $currentCat = slugify($_GET['cat']);
}

// Fetch products from DB
$sql = "SELECT product_id, product_name, category, price, product_image FROM Products";
$result = $conn->query($sql);

// Store results in JSON array (normalize server-side category -> slug)
$products = [];
if ($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $products[] = [
            "id" => $row['product_id'],
            "name" => $row['product_name'],
            "cat" => slugify($row['category']), // normalized slug
            "price" => (float)$row['price'],
            "img" => $row['product_image'] // Store as path e.g. assets/xxx.png
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Grocery Store - Essentials</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    /* ... your existing CSS (unchanged) ... */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; color: #000; background: #fff; }
    a { color: inherit; text-decoration: none; }
    /* (rest of CSS omitted here for brevity — paste your existing style block) */
  </style>
</head>
<!-- note: echo a normalized slug into data-current-cat -->
<body data-current-cat="<?php echo htmlspecialchars($currentCat, ENT_QUOTES); ?>">
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
      <div class="cart-wrap" onclick="window.location.href='cart.php'">
          <div class="cart-icon">
              <i class="material-icons">shopping_cart</i>
              <span id="cartBadge" class="cart-badge"><?php echo (int)($cartCount ?? 0); ?></span>
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
    <!-- NOTE: use hyphenated slugs in data-cat -->
    <div class="cat-card" data-cat="food" onclick="window.location.href='?cat=food'">
        <i class="material-icons" style="font-size:40px;color:#2b7a78;">restaurant</i>
        <div class="label">Food</div>
    </div>
    <div class="cat-card" data-cat="fruit" onclick="window.location.href='?cat=fruit'">
        <i class="material-icons" style="font-size:40px;color:#2b7a78;">fruit_emoji</i>
        <div class="label">Fruits</div>
    </div>
    <div class="cat-card" data-cat="snack-drink" onclick="window.location.href='?cat=snack-drink'">
        <i class="material-icons" style="font-size:40px;color:#2b7a78;">local_drink</i>
        <div class="label">Snacks & Drinks</div>
    </div>
    <div class="cat-card" data-cat="stationery" onclick="window.location.href='?cat=stationery'">
        <i class="material-icons" style="font-size:40px;color:#2b7a78;">edit</i>
        <div class="label">Stationery</div>
    </div>
    <div class="cat-card" data-cat="essential" onclick="window.location.href='?cat=essential'">
        <i class="material-icons" style="font-size:40px;color:#2b7a78;">umbrella</i>
        <div class="label">Essentials</div>
    </div>
  </section>

  <h3 class="section-title container">Essentials for everyday stuff</h3>
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
        // Data mapped from database
        const PRODUCTS = <?php echo json_encode($products);?>;

        const grid = document.getElementById('grid');
        const searchInput = document.getElementById('searchInput');
        const cartBadge = document.getElementById('cartBadge');
        const toast = document.getElementById('toast');

        // Prefer URL ?cat=... first (override data-current-cat), then data-current-cat fallback
        function getQueryParam(name) {
            const params = new URLSearchParams(window.location.search);
            return params.get(name);
        }
        const urlCatRaw = getQueryParam('cat') || '';
        // normalize url cat to match server-side slug rules (lowercase & spaces->hyphen)
        const urlCat = urlCatRaw.trim().toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9\-]/g,'');
        let currentFilter = urlCat || document.body.dataset.currentCat || 'all';

        let cartCount = <?php echo (int)($cartCount ?? 0); ?>;
        document.getElementById('cartBadge').textContent = String(cartCount);

        function renderProducts() {
            grid.innerHTML = '';
            const q = (searchInput.value || '').trim().toLowerCase();
            PRODUCTS.filter(p =>
                (currentFilter === 'all' || p.cat === currentFilter) &&
                (!q || p.name.toLowerCase().includes(q))
            ).forEach(p => grid.appendChild(cardEl(p)));
        }

        function cardEl(p) {
            const el = document.createElement('article');
            el.className = 'card';

            const thumb = document.createElement('div');
            thumb.className = 'thumb';
            thumb.style.backgroundImage = `url('${p.img || ''}')`;

            const meta = document.createElement('div');
            meta.className = 'meta';

            const name = document.createElement('div');
            name.className = 'name';
            name.textContent = p.name;

            const price = document.createElement('div');
            price.className = 'price';
            price.textContent = "₹" + Number(p.price).toFixed(2);


            const add = document.createElement('a');
            add.className = 'add';
            add.href = `add_to_cart.php?product_id=${encodeURIComponent(p.id)}`;
            add.textContent = 'Add to cart';

            meta.appendChild(name);
            meta.appendChild(price);
            el.appendChild(thumb);
            el.appendChild(meta);
            el.appendChild(add);
            return el;
        }

        function showToast(msg) {
            toast.textContent = msg;
            toast.classList.add('show');
            clearTimeout(showToast._t);
            showToast._t = setTimeout(() => toast.classList.remove('show'), 2200);
        }

        // Search
        searchInput.addEventListener('input', renderProducts);

        // Category chips (client-side highlight + update filter without full page reload)
        document.getElementById('cats').addEventListener('click', (e) => {
            const card = e.target.closest('.cat-card');
            if (!card) return;
            document.querySelectorAll('.cat-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            // use data-cat (already hyphenated from server)
            currentFilter = card.dataset.cat || 'all';
            // update URL without reloading (so bookmarkable)
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('cat', currentFilter);
            window.history.replaceState({}, '', newUrl);
            renderProducts();
        });

        // Highlight correct category card on page load
        document.querySelectorAll('.cat-card').forEach(c => {
            if (c.dataset.cat === currentFilter) {
                c.classList.add('active');
            } else {
                c.classList.remove('active');
            }
        });

        // Section bar quick filters
        document.querySelectorAll('.nav-link').forEach(n => n.addEventListener('click', () => {
            document.querySelectorAll('.nav-link').forEach(x => x.classList.remove('active'));
            n.classList.add('active');
        }));

        // Carousel
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

        // Back to top
        document.getElementById('backTop').addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        // Initial render
        renderProducts();
    </script>
</body>
</html>
