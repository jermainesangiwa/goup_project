<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Prevent browser from caching this page
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT"); // Proxies
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Ailse24/7</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
  <!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Prevent browser from caching this page
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT"); // Proxies
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Ailse24/7</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
  <style>
    /* General Styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
    }

    /* Top Navbar */
    .top-nav {
      background-color: #131921; /* amazon dark navy */
      color: white;
      display: flex;
      align-items: center;
      padding: 8px 15px;
      gap: 15px;
    }

    /* Logo */
    .nav-logo img {
      width: 100px;
      cursor: pointer;
    }

    /* Location */
    .nav-location {
      display: flex;
      align-items: center;
      cursor: pointer;
      padding: 4px;
    }
    .nav-location span {
      font-size: 1.2rem;
      margin-right: 5px;
    }
    .nav-location-text {
      font-size: 0.75rem;
      flex-direction: column;
      display: flex;
    }
    .nav-location-text strong {
      font-size: 0.9rem;
    }

    /* Search bar */
    .nav-search {
      display: flex;
      flex: 1;
      height: 40px;
    }
    .nav-search select {
      background-color: #f3f3f3;
      border: none;
      padding: 0 8px;
      font-size: 0.9rem;
    }
    .nav-search input {
      flex: 1;
      border: none;
      padding: 0 10px;
      font-size: 0.9rem;
    }
    .nav-search button {
      background-color: #febd69; /* amazon yellow */
      border: none;
      padding: 0 12px;
      cursor: pointer;
    }

    /* Account section */
    

    /* Hero section placeholder */
    .hero-section {
      height: 200px;
      background: url('hero-image.jpg') center/cover no-repeat;
      margin-bottom: 30px;
    }

    /* Shop section */
    .shop-section {
      padding: 30px;
      background-color: #f4f4f4;
    }

    .shop-images {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      gap: 20px;
    }

    .shop-link {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 220px;
      padding: 15px;
      text-align: center;
      transition: transform 0.3s;
    }

    .shop-link:hover {
      transform: translateY(-5px);
    }

    .shop-link img {
      width: 100%;
      height: 140px;
      object-fit: cover;
      border-radius: 8px;
    }

    .shop-link h3 {
      margin-bottom: 10px;
    }

    .shop-link a {
      color: #007185;
      font-weight: bold;
    }

    /* Product section */
    .product-section {
      padding: 30px;
    }

    .product-section h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }

    .product-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 20px;
      text-align: center;
      transition: transform 0.3s;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 10px;
    }

    .product-card h3 {
      font-size: 18px;
      margin: 10px 0 5px;
    }

    .product-card p {
      font-size: 14px;
      color: #666;
      margin-bottom: 10px;
    }

    .price {
      display: block;
      font-size: 16px;
      color: #e60023;
      margin-bottom: 10px;
    }

    .product-card button {
      padding: 10px 20px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
    }

    .product-card button:hover {
      background-color: #218838;
    }

    /* Footer */
    footer {
      background-color: #1a1a1a;
      color: #ccc;
      padding: 40px 20px;
      margin-top: 40px;
    }

    .footer-title {
      text-align: center;
      display: block;
      color: #fff;
      margin-bottom: 30px;
    }

    .footer-items {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
    }

    .footer-items ul {
      list-style: none;
      width: 200px;
    }

    .footer-items h3 {
      font-size: 16px;
      color: #fff;
      margin-bottom: 10px;
    }

    .footer-items li {
      margin: 8px 0;
    }

    .footer-items a {
      color: #ccc;
      text-decoration: none;
      font-size: 14px;
    }

    .footer-items a:hover {
      color: #fff;
    }
  </style>
</head>
<body>
    <header>
      <nav class="navbar">
        <div class="nav-logo">
          <a href="#"><img src="icons8-buy-100.png" alt="logo"></a>
        </div>
        <div class="address">
          <a href="#" class="deliver">Deliver</a>
          <div class="map-icon">
            <span class="material-symbols-outlined">location_on</span>
            <a href="#" class="location">INDIA</a>
          </div>
        </div>

        <div class="nav-search">
          <select class="select-search">
            <option>All</option>
            <option>All Categories</option>
            <option>Amazon Devices</option>
          </select>
          <input type="text" placeholder="Search Ailse24/7" class="search-input">
          <div class="search-icon">
            <span class="material-symbols-outlined">search</span>
          </div>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="sign-in">
          <a href="#"><p>Hello, <?php echo htmlspecialchars($_SESSION['email']); ?></p>
          <a href="logout.php">Logout</a>
          </div>
          <?php else: ?>
            <div class="sign-in">
              <a href="user_login.php">Sign In</a>
            </div>
          <?php endif; ?>

        <div class="returns">
          <a href="#"><p>Returns</p>
            <span>&amp; Orders</span></a>
        </div>

        <div class="cart">
          <a href="#">
            <span class="material-symbols-outlined cart-icon">shopping_cart</span>
            </a>
            <p>Cart</p>
        </div>
      </nav>
      
      <div class="banner">
        <div class="banner-content">
          <div class="panel">
            <span class="material-symbols-outlined">menu</span>
            <a href="#">All</a>
          </div>
  
          <ul class="links">
            <li><a href="#">Today's Deals</a></li>
            <li><a href="#">Customer Service</a></li>
            <li><a href="#">Registry</a></li>
            <li><a href="#">Gift Cards</a></li>
            <li><a href="#">Sell</a></li>
          </ul>
          <div class="deals">
            <a href="#">Shop according to Diet</a>
          </div>
        </div>
      </div>
    </header>

    <section class="hero-section">
      
    </section>
    <section class="shop-section">
      <div class="shop-images">
        <div class="shop-link">
          <h3>Snacks</h3>
          <img src="sweets_snacks_expo_2022_floor.jpg" alt="card">
          <a href="snacks.html">Shop now</a>
        </div>
        <div class="shop-link">
          <h3>Baked Goods</h3>
          <img src="mpg_march_2022-6.jpg" alt="card">
          <a href="bakerygoods.html">Shop now</a>
        </div>
        <div class="shop-link">
          <h3>Fruits</h3>
          <img src="2-2-2-3foodgroups_fruits_detailfeature.jpg" alt="card">
          <a href="fruits.html">Shop now</a>
        </div>
        <div class="shop-link">
          <h3>Drinks</h3>
          <img src="cold-drink-recipe-formulation.jpeg" alt="card">
          <a href="#">Shop now</a>
        </div>
      </div>
    </section>
    <section class="product-section">
      <h2>Top pick for You</h2>
      <div class="product-grid">
        <!-- Product items will be dynamically generated here -->
         <div class="product-card">
          <img src="sweets_snacks_expo_2022_floor.jpg" alt="Snacks">
          <h3>Product 1</h3>
          <p>Short description of Product 1.</p>
          <span class="price">₹499</span>
          <button>Add to Cart</button>
         </div>
         <div class="product-card">
          <img src="product2.jpg" alt="Product 2">
          <h3>Product 2</h3>
          <p>Short description of Product 2.</p>
          <span class="price">₹199</span>
          <button>Add to Cart</button>
         </div>
      </div>
    </section>

    <footer>
      <a href="#" class="footer-title">
        Back to top
      </a>
      <div class="footer-items">
        <ul>
          <h3>Get to Know Us</h3>
          <li><a href="#">About us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Press Release</a></li>
   
        </ul>
        <ul>
          <h3>Connect with Us</h3>
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Twitter</a></li>
          <li><a href="#">Instagram</a></li>
        </ul>
        <ul>
          <h3>Make Money with Us</h3>
          <li><a href="#">Sell on Shabeta</a></li>
          <li><a href="#">Sell under Shabeta Accelerator</a></li>
          <li><a href="#">Protect and Build Your Brand</a></li>
          <li><a href="#">Shabeta Global Selling</a></li>
          <li><a href="#">Become an Affiliate</a></li>
          <li><a href="#">Fulfillment by Shabeta</a></li>
          <li><a href="#">Advertise Your Products</a></li>
          <li><a href="#">Shabeta Pay on Merchants</a></li>
        </ul>
        <ul>
          <h3>Let Us Help You</h3>
          
          <li><a href="#">Your Account</a></li>
          <li><a href="#">Return Centre</a></li>
          <li><a href="#">100% Purchase Protection</a></li>
          <li><a href="#">Shabeta App Download</a></li>
          <li><a href="#">Help</a></li>
        </ul>
      </div>
    </footer>
</body>
</html>
