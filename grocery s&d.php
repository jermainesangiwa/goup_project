<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Grocery Store - Drinks</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; color: #000; background: #fff; }
        a { color: inherit; text-decoration: none; }

        .topbar { position: sticky; top: 0; z-index: 50; width: 100%; height: 83px; background: rgba(0,0,0,0.68); display: flex; align-items: center; }
        .container { width: 100%; max-width: 1440px; margin: 0 auto; padding: 0 24px; }
        .topbar-content { display: grid; grid-template-columns: 220px 1fr 280px 180px; align-items: center; gap: 16px; }

        .location { display: flex; align-items: center; gap: 12px; color: #fff; }
        .location-icon { width: 24px; height: 24px; border: 2px solid #fff; border-radius: 50%; position: relative; }
        .location-icon::after { content: ''; position: absolute; left: 50%; top: 50%; width: 8px; height: 12px; border: 2px solid #fff; border-top: none; border-left: none; transform: translate(-50%,-35%) rotate(45deg); border-radius: 2px; }
        .location-text { font-weight: 700; font-size: 14px; letter-spacing: 0.01em; }

        .search-wrap { display: flex; align-items: center; justify-content: center; }
        .search { width: 100%; max-width: 604px; height: 40px; background: rgba(217,217,217,0.62); border: 1px solid rgba(0,0,0,0.37); border-radius: 50px; display: grid; grid-template-columns: 1fr 48px; overflow: hidden; }
        .search input { border: none; background: transparent; padding: 0 20px; color: #000; font-size: 18px; outline: none; }
        .search-btn { display: flex; align-items: center; justify-content: center; color: #fff; }
        .search-btn .lens { width: 22px; height: 22px; border: 3px solid #fff; border-radius: 50%; position: relative; opacity: 0.9; }
        .search-btn .lens::after { content: ''; position: absolute; right: -8px; bottom: -6px; width: 10px; height: 3px; background: #fff; transform: rotate(45deg); border-radius: 2px; }

        .cart-wrap { display: flex; align-items: center; justify-content: flex-end; gap: 12px; color: #fff; cursor: pointer; }
        .cart-icon { position: relative; width: 36px; height: 36px; border: 2px solid #fff; border-radius: 6px; }
        .cart-badge { position: absolute; right: -6px; top: -6px; width: 16px; height: 16px; background: #BA0B34; color: #fff; font-size: 12px; font-weight: 700; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .cart-text { font-weight: 700; font-size: 24px; }

        .auth { display: flex; justify-content: flex-end; }
        .auth a { color: #fff; font-weight: 700; font-size: 16px; }

        .sectionbar { width: 100%; background: #252F3D; height: 40px; display: flex; align-items: center; }
        .sectionbar-inner { display: flex; align-items: center; gap: 28px; color: #fff; }
        .menu-icon { width: 24px; height: 24px; border: 2px solid #fff; border-radius: 4px; position: relative; }
        .menu-icon::before, .menu-icon::after { content: ''; position: absolute; left: 4px; right: 4px; height: 2px; background: #fff; }
        .menu-icon::before { top: 7px; }
        .menu-icon::after { bottom: 7px; }
        .nav-link { font-weight: 500; font-size: 16px; opacity: 0.95; cursor: pointer; }
        .nav-link.active { text-decoration: underline; text-underline-offset: 4px; }

        .promo { width: 100%; max-width: 1463px; margin: 24px auto; position: relative; overflow: hidden; border-radius: 8px; }
        .slides { display: flex; transition: transform 0.5s ease; }
        .slide { min-width: 100%; height: 165px; background: #eee center/cover no-repeat; }
        .carousel-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; border: 2px solid #fff; color: #fff; border-radius: 50%; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center; cursor: pointer; user-select: none; }
        .carousel-btn.left { left: 10px; }
        .carousel-btn.right { right: 10px; }

        .cats { width: 100%; height: 154px; background: rgba(217,217,217,0.65); display: flex; align-items: center; justify-content: center; gap: 40px; padding: 0 24px; position: relative; }
        .cat-card { width: 128px; height: 115px; display: flex; flex-direction: column; align-items: center; text-align: center; cursor: pointer; position: relative; }
        .cat-card .img { width: 100px; height: 100px; border-radius: 12px; background: #d9d9d9 center/cover no-repeat; margin-bottom: 15px; }
        .cat-card .label { font-weight: 700; font-size: 20px; color: #000; }
        .cat-card.active .label { color: #000; }
        .cat-card.active::before { content: ''; position: absolute; top: -10px; left: 50%; transform: translateX(-50%); width: 166px; height: 154px; background: rgba(0,0,0,0.68); border-radius: 50%; z-index: -1; }

        .section-title { font-weight: 700; font-size: 20px; margin: 24px; }

        .grid { display: grid; grid-template-columns: repeat(6, 200px); gap: 24px; padding: 0 24px 40px; justify-content: start; }
        .card { width: 200px; border-radius: 10px; background: #d9d9d9; overflow: hidden; position: relative; }
        .card .thumb { width: 100%; height: 200px; background: #ccc center/cover no-repeat; }
        .card .meta { background: #fff; padding: 10px 10px 14px; }
        .card .name { font-weight: 700; font-size: 20px; color: #000; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .card .price { font-weight: 700; font-size: 15px; color: #DE3925; }
        .card .add { position: absolute; left: 10px; bottom: 10px; height: 30px; padding: 0 12px 0 36px; border-radius: 100px; background: #F9A41E; color: #000; font-weight: 700; font-size: 16px; display: inline-flex; align-items: center; cursor: pointer; border: none; }
        .card .add::before { content: '+'; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 22px; height: 22px; border-radius: 999px; background: rgba(0,0,0,0.15); display: grid; place-items: center; font-size: 16px; font-weight: 700; }

        .footer { margin-top: 24px; background: #1A1A1A; color: #fff; }
        .back-top { text-align: center; padding: 16px; font-weight: 700; font-size: 24px; cursor: pointer; }
        .footer-inner { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; max-width: 1440px; margin: 0 auto; padding: 40px 24px; }
        .footer h4 { font-size: 20px; margin-bottom: 12px; }
        .footer ul { list-style: none; }
        .footer li { margin: 6px 0; opacity: 0.95; }

        @media (max-width: 1400px) { .grid { grid-template-columns: repeat(5, 200px); } }
        @media (max-width: 1200px) {
            .topbar-content { grid-template-columns: 220px 1fr 220px 0; }
            .auth { display: none; }
            .grid { grid-template-columns: repeat(4, 200px); }
        }
        @media (max-width: 992px) { .grid { grid-template-columns: repeat(3, 200px); } }
        @media (max-width: 768px) {
            .topbar { height: auto; padding: 12px 0; }
            .topbar-content { grid-template-columns: 1fr; gap: 12px; }
            .sectionbar-inner { gap: 16px; overflow-x: auto; padding: 0 8px; }
            .cats { grid-template-columns: repeat(3, 1fr); height: auto; row-gap: 20px; }
            .grid { grid-template-columns: repeat(2, 1fr); justify-items: center; }
            .card { width: 100%; max-width: 220px; }
        }
    </style>
    <style>
        .toast { position: fixed; top: 20px; right: 20px; background: #252F3D; color: #fff; padding: 12px 16px; border-radius: 8px; box-shadow: 0 4px 14px rgba(0,0,0,0.35); transform: translateX(120%); transition: transform .3s ease; z-index: 1000; }
        .toast.show { transform: translateX(0); }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="container topbar-content">
            <div class="location">
                <div class="location-icon" aria-hidden="true"></div>
                <div class="location-text">Location 140301 RD</div>
            </div>
            <div class="search-wrap">
                <div class="search" role="search">
                    <input id="searchInput" type="text" placeholder="Search here....." aria-label="Search here" />
                    <div class="search-btn"><span class="lens" aria-hidden="true"></span></div>
                </div>
            </div>
            <div class="cart-wrap" onclick="openCart()">
                <div class="cart-icon" aria-hidden="true">
                    <span id="cartBadge" class="cart-badge">0</span>
                </div>
                <div class="cart-text">Cart</div>
            </div>
            <div class="auth">
                <a href="#">Login/sign up · Account</a>
            </div>
        </div>
    </header>

    <nav class="sectionbar">
        <div class="container sectionbar-inner">
            <div class="menu-icon" aria-hidden="true"></div>
            <div class="nav-link active" data-filter="all">All</div>
            <div class="nav-link" data-filter="deal">Today's deal</div>
            <div class="nav-link" data-filter="best">Best sellig</div>
            <div class="nav-link" data-filter="gift">Gift cards</div>
            <div class="nav-link" data-filter="service">Customer service</div>
        </div>
    </nav>

    <section class="promo container">
        <div id="slides" class="slides">
            <div class="slide" style="background-image:url('assets/grocery_banner_5-31f79d.png');"></div>
            <div class="slide" style="background-image:url('assets/grocery_banner_3-30d908.png');"></div>
            <div class="slide" style="background-image:url('assets/grocery_banner_4-156c44.png');"></div>
            <div class="slide" style="background-image:url('assets/grocery_banner_1.png');"></div>
            <div class="slide" style="background-image:url('assets/grocery_banner_2.png');"></div>
        </div>
        <div class="carousel-btn left" id="btnPrev" aria-label="Previous">◀</div>
        <div class="carousel-btn right" id="btnNext" aria-label="Next">▶</div>
    </section>

    <section class="cats container" id="cats">
        <div class="cat-card" data-cat="fruits" onclick="navigateToCategory('fruits')">
            <div class="img" style="background-image:url('assets/cat_fruits.png');"></div>
            <div class="label">Fruits</div>
        </div>
        <div class="cat-card active" data-cat="snacks-drinks" onclick="navigateToCategory('snacks-drinks')">
            <div class="img" style="background-image:url('assets/cat_drinks.png');"></div>
            <div class="label">Snacks & Drinks</div>
        </div>
        <div class="cat-card" data-cat="stationary" onclick="navigateToCategory('stationary')">
            <div class="img" style="background-image:url('assets/cat_stationary.png');"></div>
            <div class="label">Stationary</div>
        </div>
        <div class="cat-card" data-cat="pharmacy" onclick="navigateToCategory('pharmacy')">
            <div class="img" style="background-image:url('assets/cat_pharmacy.png');"></div>
            <div class="label">Pharmacy</div>
        </div>
    </section>

    <h3 class="section-title container">Refreshing Drinks</h3>

    <section class="grid container" id="grid"></section>

    <footer class="footer">
        <div class="back-top" id="backTop">Back to top</div>
        <div class="footer-inner">
            <div>
                <h4>Get to Know Us</h4>
                <ul>
                    <li>About us</li>
                    <li>Careers</li>
                    <li>Press</li>
                </ul>
            </div>
            <div>
                <h4>Connect with us</h4>
                <ul>
                    <li>Twitter</li>
                    <li>Instagram</li>
                    <li>Facebook</li>
                </ul>
            </div>
            <div>
                <h4>Make Money with Us</h4>
                <ul>
                    <li>Sell on Ailse247</li>
                    <li>Sell under Ailse247</li>
                    <li>Protect and build your brand</li>
                </ul>
            </div>
        </div>
    </footer>

    <div id="toast" class="toast" role="status" aria-live="polite"></div>

    <script>
        let cart = JSON.parse(localStorage.getItem('groceryCart')) || [];
        let cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);

        const PRODUCTS = [
            { name: 'Fanta', price: 1.20, img: 'assets/drinks_fanta.png', cat: 'drinks' },
            { name: 'Coca cola', price: 1.50, img: 'assets/drinks_cocacola.png', cat: 'drinks' },
            { name: 'Sprite', price: 1.50, img: 'assets/drinks_sprite.png', cat: 'drinks' },
            { name: 'Pepsi', price: 1.50, img: 'assets/drinks_pepsi.png', cat: 'drinks' },
            { name: 'Monster', price: 1.50, img: 'assets/drinks_monster.png', cat: 'drinks' },
            { name: 'Red Bull', price: 1.50, img: 'assets/drinks_redbull.png', cat: 'drinks' },
            { name: 'Water', price: 1.50, img: 'assets/drinks_water.png', cat: 'drinks' },
            { name: 'Juice', price: 1.50, img: 'assets/drinks_juice.png', cat: 'drinks' },
            { name: 'Tea', price: 1.50, img: 'assets/drinks_tea.png', cat: 'drinks' },
            { name: 'Coffee', price: 1.50, img: 'assets/drinks_coffee.png', cat: 'drinks' },
            { name: 'Energy Drink', price: 1.50, img: 'assets/drinks_energy.png', cat: 'drinks' },
            { name: 'Soda', price: 1.50, img: 'assets/drinks_soda.png', cat: 'drinks' },
            { name: 'Sports Drink', price: 1.50, img: 'assets/drinks_sports.png', cat: 'drinks' },
            { name: 'Milk', price: 1.50, img: 'assets/drinks_milk.png', cat: 'drinks' },
            { name: 'Smoothie', price: 1.50, img: 'assets/drinks_smoothie.png', cat: 'drinks' },
            { name: 'Iced Tea', price: 1.50, img: 'assets/drinks_icedtea.png', cat: 'drinks' },
        ];

        const grid = document.getElementById('grid');
        const searchInput = document.getElementById('searchInput');
        const cartBadge = document.getElementById('cartBadge');
        const toast = document.getElementById('toast');
        let currentFilter = 'drinks';

        function updateCartBadge() {
            cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartBadge.textContent = cartCount;
        }

        function addToCart(product) {
            const existingItem = cart.find(item => item.name === product.name);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ ...product, quantity: 1 });
            }
            localStorage.setItem('groceryCart', JSON.stringify(cart));
            updateCartBadge();
            showToast(`${product.name} added to cart`);
        }

        function openCart() {
            window.open('cart.html', '_blank');
        }

        function navigateToCategory(category) {
            const pages = {
                'fruits': 'grocery-fruits.html',
                'snacks-drinks': 'grocery-main.html',
                'stationary': 'grocery-stationary.html',
                'pharmacy': 'grocery-pharmacy.html'
            };
            if (pages[category]) {
                window.location.href = pages[category];
            }
        }

        function renderProducts() {
            grid.innerHTML = '';
            const q = (searchInput.value || '').trim().toLowerCase();
            PRODUCTS.filter(p => (currentFilter==='all' || p.cat===currentFilter) && (!q || p.name.toLowerCase().includes(q)))
                .forEach(p => grid.appendChild(cardEl(p)));
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
            price.textContent = `$${p.price.toFixed(2)}`;
            const add = document.createElement('button');
            add.className = 'add';
            add.type = 'button';
            add.textContent = 'Add to cart';
            add.addEventListener('click', () => addToCart(p));
            meta.appendChild(name);
            meta.appendChild(price);
            el.appendChild(thumb);
            el.appendChild(meta);
            el.appendChild(add);
            el.setAttribute('data-cat', p.cat);
            el.setAttribute('data-name', p.name);
            return el;
        }

        function showToast(msg) {
            toast.textContent = msg;
            toast.classList.add('show');
            clearTimeout(showToast._t);
            showToast._t = setTimeout(() => toast.classList.remove('show'), 2200);
        }

        searchInput.addEventListener('input', renderProducts);

        document.getElementById('cats').addEventListener('click', (e) => {
            const card = e.target.closest('.cat-card');
            if (!card) return;
            document.querySelectorAll('.cat-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            currentFilter = card.dataset.cat || 'all';
            renderProducts();
        });

        document.querySelectorAll('.nav-link').forEach(n => n.addEventListener('click', () => {
            document.querySelectorAll('.nav-link').forEach(x => x.classList.remove('active'));
            n.classList.add('active');
        }));

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

        document.getElementById('backTop').addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        updateCartBadge();
        renderProducts();
    </script>
</body>
</html>
