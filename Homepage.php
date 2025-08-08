<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Prevent browser from caching this page
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
    /* Add your custom styles here */
    /* Navigation Bar Style*/
    .navbar {
      background-color: #333;
      color: #fff;
      padding: 10px 20px;
    }
    .nav-logo img {
      max-height: 50px;
    }
    /* Address */
    .address {
      display: flex;
      align-items: center;
    }
    .address .deliver {
      margin-right: 10px;
    }
    .address .map-icon {
      margin-left: 5px;
    }
    /* Search */
    .nav-search {
      display: flex;
      align-items: center;
      margin-left: 20px;
    }
    .nav-search .select-search {
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-right: 10px;
    }
    .nav-search .search-input {
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      flex-grow: 1;
    }
    .nav-search .search-icon {
      margin-left: 5px;
    }
    /* Sign In, Returns, Cart */
    .sign-in, .returns, .cart {
      margin-left: 20px;
    }
    .sign-in a, .returns a, .cart a {
      color: #fff;
      text-decoration: none;
    }
    .sign-in a:hover, .returns a:hover, .cart a:hover {
      text-decoration: underline;
    }
    /* Banner */
    .banner {
      background-color: #f8f8f8;
      padding: 20px;
      text-align: center;
    }
    .banner-content {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .panel {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }
    .panel span {
      margin-right: 5px;
    }
    .links {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }
    .links li {
      margin: 0 10px;
    }
    .deals {
      margin-top: 10px;
    }

    /* Hero section */
    .hero-section {
      background-image: url('hero-background.jpg');
      background-size: cover;
      background-position: center;
      height: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-align: center;
    }
    .hero-section h1 {
      font-size: 3em;
      margin: 0;
    }
    .hero-section p {
      font-size: 1.5em;
    }
    /* Shop section */
    .shop-section {
      padding: 20px;
    }
    .shop-images {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }
    .shop-link {
      flex: 1 1 calc(25% - 10px);
      margin: 5px;
      text-align: center;
    }
    .shop-link img {
      max-width: 100%;
      height: auto;
    }
    .shop-link h3 {
      font-size: 1.2em;
      margin: 10px 0;
    }
    
    /* Footer */
    .footer {
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
    }
    .footer-title {
      font-size: 1.5em;
      margin-bottom: 10px;
    }
    .footer-items {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
    }
    .footer-items ul {
      list-style: none;
      padding: 0;
      margin: 0 20px;
    }
    .footer-items li {
      margin-bottom: 5px;
    }
    .footer-items a {
      color: #fff;
      text-decoration: none;
    }
    .footer-items a:hover {
      text-decoration: underline;
    }

  </style>
</head>
<body>
    <header>
     
      <nav class="navbar">
        <div class="nav-logo">
          <a href="#"><img src="shabeta-high-resolution-logo.png" alt="logo"></a>
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
          <input type="text" placeholder="Search SHABETA" class="search-input">
          <div class="search-icon">
            <span class="material-symbols-outlined">search</span>
          </div>
        </div>

        <div class="sign-in">
         <a href="#"> <p>Hello, sign in</p>
          <span>Account &amp; Lists</span></a>
        </div>

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

    <section class="hero-section"></section>
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
