<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Dark App</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Raleway:wght@600&family=Nunito:wght@500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
        
            color: #FFFFFF;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .cart-container {
            width: 100%;
            max-width: 375px;
            height: 667px;
            background: linear-gradient(145deg, #ffffff 0%, #f8f8f8 100%);
            border-radius: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.4);
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Styles */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 45px 25px 20px;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
        }

        .back-button {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #000000 0%, #333333 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.22;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .back-button:hover {
            opacity: 0.8;
            transform: scale(1.1);
        }

        .back-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }

        .back-button:hover::before {
            width: 100%;
            height: 100%;
        }

        .back-arrow {
            width: 7px;
            height: 14px;
            border: 2px solid #FFFFFF;
            border-right: none;
            border-bottom: none;
            transform: rotate(-45deg);
            transition: transform 0.3s ease;
        }

        .back-button:hover .back-arrow {
            transform: rotate(-45deg) scale(1.2);
        }

        .cart-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 20px;
            line-height: 1.5;
            color: #000000;
            text-align: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            animation: fadeInDown 0.6s ease-out 0.2s both;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .header-spacer {
            width: 30px;
        }

        /* Product Section */
        .product-section {
            padding: 0 25px;
            margin-bottom: 20px;
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-item {
            background: linear-gradient(135deg, #9D9D9D 0%, #7A7A7A 100%);
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .product-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .product-item:hover::before {
            left: 100%;
        }

        .product-item:hover {
            transform: translateY(-2px);
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.2);
        }

        .product-image {
            width: 100px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-image:hover img {
            transform: scale(1.05);
        }

        .product-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .product-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 16px;
            line-height: 1.5;
            color: #FFFFFF;
            margin: 0;
            transition: color 0.3s ease;
        }

        .product-item:hover .product-name {
            color: #f0f0f0;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .quantity-btn {
            width: 25px;
            height: 25px;
            border: 1px solid rgba(0, 0, 0, 0.21);
            border-radius: 50%;
            background: linear-gradient(135deg, #9D9D9D 0%, #7A7A7A 100%);
            color: #FFFFFF;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .quantity-btn:hover {
            background: linear-gradient(135deg, #7A7A7A 0%, #5A5A5A 100%);
            transform: scale(1.1);
        }

        .quantity-btn:active {
            transform: scale(0.95);
        }

        .quantity-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }

        .quantity-btn:hover::before {
            width: 100%;
            height: 100%;
        }

        .quantity {
            font-family: 'Raleway', sans-serif;
            font-weight: 600;
            font-size: 14px;
            color: #FFFFFF;
            min-width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover + .quantity {
            transform: scale(1.1);
        }

        .product-price {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 14px;
            line-height: 1.5;
            color: #FFFFFF;
            text-align: right;
            transition: all 0.3s ease;
        }

        .product-item:hover .product-price {
            transform: scale(1.05);
        }

        /* Navigation Sections */
        .nav-sections {
            padding: 0 25px;
            margin-bottom: 20px;
            animation: fadeInUp 0.6s ease-out 0.6s both;
        }

        .nav-item {
            background: linear-gradient(135deg, #9D9D9D 0%, #7A7A7A 100%);
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 10px;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .nav-item:hover {
            background: linear-gradient(135deg, #7A7A7A 0%, #5A5A5A 100%);
            transform: translateX(5px);
        }

        .nav-item:active {
            transform: translateX(2px) scale(0.98);
        }

        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .nav-item:hover::before {
            left: 100%;
        }

        .nav-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 16px;
            line-height: 1.5;
            color: #FFFFFF;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
            position: relative;
            z-index: 1;
        }

        .nav-underline {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #FFFFFF;
            opacity: 0.5;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .nav-item:hover .nav-underline {
            transform: scaleX(1);
        }

        /* Order Summary */
        .order-summary {
            padding: 0 25px;
            margin-bottom: 20px;
            animation: fadeInUp 0.6s ease-out 0.8s both;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #FFFFFF;
            transition: all 0.3s ease;
        }

        .summary-item:hover {
            background: rgba(255, 255, 255, 0.05);
            padding-left: 10px;
            border-radius: 8px;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item.total {
            font-weight: 600;
            text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 15px 10px;
            margin-top: 10px;
        }

        .summary-label {
            font-family: 'Nunito', sans-serif;
            font-weight: 500;
            font-size: 16px;
            line-height: 1.36;
            color: #FFFFFF;
            transition: color 0.3s ease;
        }

        .summary-value {
            font-family: 'Nunito', sans-serif;
            font-weight: 500;
            font-size: 16px;
            line-height: 1.36;
            color: #FFFFFF;
            text-align: right;
            transition: all 0.3s ease;
        }

        .summary-item:hover .summary-value {
            transform: scale(1.05);
        }

        /* Add Code Section */
        .add-code-section {
            padding: 0 25px;
            margin-bottom: 20px;
            text-align: right;
            animation: fadeInUp 0.6s ease-out 1s both;
        }

        .add-code-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 16px;
            line-height: 1.5;
            color: #FFFFFF;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .add-code-text:hover {
            color: #f0f0f0;
            transform: translateY(-2px);
        }

        .add-code-underline {
            position: absolute;
            bottom: -2px;
            right: 0;
            width: 85px;
            height: 1px;
            background-color: #FFFFFF;
            border: 1px dashed #FFFFFF;
            transition: all 0.3s ease;
        }

        .add-code-text:hover .add-code-underline {
            width: 100%;
            background-color: #f0f0f0;
        }

        /* Address Section */
        .address-section {
            padding: 0 25px;
            margin-bottom: 30px;
            text-align: right;
            animation: fadeInUp 0.6s ease-out 1.2s both;
        }

        .address-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 14px;
            line-height: 1.5;
            color: #FFFFFF;
            transition: all 0.3s ease;
        }

        .address-text:hover {
            color: #f0f0f0;
            transform: translateX(-5px);
        }

        /* Checkout Button */
        .checkout-btn {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 325px;
            height: 45px;
            background: linear-gradient(135deg, #9D9D9D 0%, #7A7A7A 100%);
            border: 3px solid rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            color: #FFFFFF;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 18px;
            line-height: 1.5;
            cursor: pointer;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out 1.4s both;
            position: relative;
            overflow: hidden;
        }

        .checkout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .checkout-btn:hover::before {
            left: 100%;
        }

        .checkout-btn:hover {
            background: linear-gradient(135deg, #7A7A7A 0%, #5A5A5A 100%);
            transform: translateX(-50%) translateY(-3px);
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.4);
        }

        .checkout-btn:active {
            transform: translateX(-50%) translateY(-1px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
        }

        /* Loading Animation */
        .loading {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #FFFFFF;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Success Animation */
        .success-message {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 20px 40px;
            border-radius: 15px;
            font-weight: 600;
            text-align: center;
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.3);
            animation: successPop 0.5s ease-out;
        }

        @keyframes successPop {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.5);
            }
            100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        /* Responsive Design for Desktop */
        @media (min-width: 768px) {
            body {
                padding: 40px;
            }
            
            .cart-container {
                max-width: 500px;
                height: 800px;
                box-shadow: 0px 25px 50px rgba(0, 0, 0, 0.5);
            }
            
            .header {
                padding: 60px 40px 30px;
            }
            
            .cart-title {
                font-size: 24px;
            }
            
            .product-section,
            .nav-sections,
            .order-summary,
            .add-code-section,
            .address-section {
                padding: 0 40px;
            }
            
            .product-item {
                padding: 25px;
            }
            
            .product-name {
                font-size: 18px;
            }
            
            .nav-text,
            .summary-label,
            .summary-value {
                font-size: 18px;
            }
            
            .checkout-btn {
                width: 400px;
                height: 55px;
                font-size: 20px;
                bottom: 30px;
            }
        }

        @media (min-width: 1024px) {
            .cart-container {
                max-width: 600px;
                height: 900px;
            }
            
            .header {
                padding: 80px 50px 40px;
            }
            
            .cart-title {
                font-size: 28px;
            }
            
            .product-section,
            .nav-sections,
            .order-summary,
            .add-code-section,
            .address-section {
                padding: 0 50px;
            }
            
            .product-item {
                padding: 30px;
            }
            
            .product-name {
                font-size: 20px;
            }
            
            .nav-text,
            .summary-label,
            .summary-value {
                font-size: 20px;
            }
            
            .checkout-btn {
                width: 450px;
                height: 60px;
                font-size: 22px;
                bottom: 40px;
            }
        }

        /* Notification Toast */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            transform: translateX(400px);
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.error {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <!-- Loading Animation -->
        <div class="loading" id="loading"></div>
        
        <!-- Success Message -->
        <div class="success-message" id="successMessage">
            Order Placed Successfully! 🎉
        </div>

        <!-- Header Section -->
        <header class="header">
            <div class="back-button" onclick="goBack()">
                <div class="back-arrow"></div>
            </div>
            <h1 class="cart-title">My Cart</h1>
            <div class="header-spacer"></div>
        </header>

        <!-- Product Item Section -->
        <div class="product-section">
            <div class="product-item">
                <div class="product-image">
                    <img src="https://via.placeholder.com/100x80/9D9D9D/FFFFFF?text=Pencil" alt="Pencil" />
                </div>
                <div class="product-details">
                    <h3 class="product-name">Pencil</h3>
                    <div class="quantity-controls">
                        <button class="quantity-btn minus" onclick="updateQuantity(-1)">-</button>
                        <span class="quantity" id="quantity">1</span>
                        <button class="quantity-btn plus" onclick="updateQuantity(1)">+</button>
                    </div>
                </div>
                <div class="product-price" id="productPrice">$0.50</div>
            </div>
        </div>

        <!-- Navigation Sections -->
        <div class="nav-sections">
            <div class="nav-item" onclick="showSection('offers')">
                <span class="nav-text">Offers</span>
                <div class="nav-underline"></div>
            </div>
            <div class="nav-item" onclick="showSection('summary')">
                <span class="nav-text">Order Summary</span>
                <div class="nav-underline"></div>
            </div>
            <div class="nav-item" onclick="showSection('address')">
                <span class="nav-text">Address</span>
                <div class="nav-underline"></div>
            </div>
        </div>

        <!-- Order Summary Section -->
        <div class="order-summary">
            <div class="summary-item">
                <span class="summary-label">Order</span>
                <span class="summary-value" id="orderTotal">$0.50</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Delivery</span>
                <span class="summary-value" id="deliveryFee">$0</span>
            </div>
            <div class="summary-item total">
                <span class="summary-label">Total</span>
                <span class="summary-value" id="grandTotal">$0.50</span>
            </div>
        </div>

        <!-- Add Code Section -->
        <div class="add-code-section">
            <span class="add-code-text" onclick="showPromoCode()">Add code</span>
            <div class="add-code-underline"></div>
        </div>

        <!-- Address Section -->
        <div class="address-section">
            <span class="address-text" onclick="editAddress()">Sukna hostel</span>
        </div>

        <!-- Checkout Button -->
        <button class="checkout-btn" onclick="checkout()">Check Out</button>
    </div>

    <script>
        // Global variables
        let quantity = 1;
        let unitPrice = 0.50;
        let deliveryFee = 0;
        let promoCode = null;
        let promoDiscount = 0;

        // Initialize the cart
        document.addEventListener('DOMContentLoaded', function() {
            updateTotals();
            addParticleEffect();
        });

        // Quantity control functions
        function updateQuantity(change) {
            const newQuantity = quantity + change;
            if (newQuantity >= 1 && newQuantity <= 99) {
                quantity = newQuantity;
                document.getElementById('quantity').textContent = quantity;
                updateTotals();
                animateQuantityChange();
            }
        }

        // Update all totals
        function updateTotals() {
            const orderTotal = quantity * unitPrice;
            const grandTotal = orderTotal + deliveryFee - promoDiscount;
            
            document.getElementById('productPrice').textContent = `$${orderTotal.toFixed(2)}`;
            document.getElementById('orderTotal').textContent = `$${orderTotal.toFixed(2)}`;
            document.getElementById('deliveryFee').textContent = `$${deliveryFee.toFixed(2)}`;
            document.getElementById('grandTotal').textContent = `$${grandTotal.toFixed(2)}`;
        }

        // Animate quantity change
        function animateQuantityChange() {
            const quantityElement = document.getElementById('quantity');
            quantityElement.style.transform = 'scale(1.2)';
            quantityElement.style.color = '#4CAF50';
            
            setTimeout(() => {
                quantityElement.style.transform = 'scale(1)';
                quantityElement.style.color = '#FFFFFF';
            }, 200);
        }

        // Navigation functions
        function showSection(section) {
            showToast(`Opening ${section.charAt(0).toUpperCase() + section.slice(1)} section...`);
            
            // Add visual feedback
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.style.background = 'linear-gradient(135deg, #9D9D9D 0%, #7A7A7A 100%)';
            });
            
            event.target.closest('.nav-item').style.background = 'linear-gradient(135deg, #7A7A7A 0%, #5A5A5A 100%)';
        }

        // Back button function
        function goBack() {
            showToast('Going back...');
            setTimeout(() => {
                window.history.back();
            }, 500);
        }

        // Promo code function
        function showPromoCode() {
            const code = prompt('Enter promo code:');
            if (code) {
                if (code.toLowerCase() === 'save10') {
                    promoCode = code;
                    promoDiscount = 0.10;
                    updateTotals();
                    showToast('Promo code applied! 10% discount added.');
                } else if (code.toLowerCase() === 'free') {
                    promoCode = code;
                    deliveryFee = 0;
                    updateTotals();
                    showToast('Free delivery applied!');
                } else {
                    showToast('Invalid promo code', 'error');
                }
            }
        }

        // Edit address function
        function editAddress() {
            const newAddress = prompt('Enter new address:', 'Sukna hostel');
            if (newAddress && newAddress.trim() !== '') {
                document.querySelector('.address-text').textContent = newAddress.trim();
                showToast('Address updated successfully!');
            }
        }

        // Checkout function
        function checkout() {
            const loading = document.getElementById('loading');
            const successMessage = document.getElementById('success-message');
            
            // Show loading
            loading.style.display = 'block';
            document.querySelector('.checkout-btn').style.opacity = '0.5';
            document.querySelector('.checkout-btn').disabled = true;
            
            // Simulate processing
            setTimeout(() => {
                loading.style.display = 'none';
                successMessage.style.display = 'block';
                
                // Hide success message after 3 seconds
                setTimeout(() => {
                    successMessage.style.display = 'none';
                    document.querySelector('.checkout-btn').style.opacity = '1';
                    document.querySelector('.checkout-btn').disabled = false;
                    showToast('Order completed! Redirecting...');
                }, 3000);
            }, 2000);
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            // Remove existing toast
            const existingToast = document.querySelector('.toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create new toast
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            // Show toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            // Hide toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        // Add particle effect
        function addParticleEffect() {
            const container = document.querySelector('.cart-container');
            
            // Create floating particles
            for (let i = 0; i < 5; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'absolute';
                particle.style.width = '4px';
                particle.style.height = '4px';
                particle.style.background = 'rgba(255, 255, 255, 0.3)';
                particle.style.borderRadius = '50%';
                particle.style.pointerEvents = 'none';
                particle.style.animation = `float ${3 + Math.random() * 4}s ease-in-out infinite`;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 2 + 's';
                
                container.appendChild(particle);
            }
        }

        // Add floating animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
                50% { transform: translateY(-20px) rotate(180deg); opacity: 0.8; }
            }
        `;
        document.head.appendChild(style);

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === '+' || e.key === '=') {
                updateQuantity(1);
            } else if (e.key === '-') {
                updateQuantity(-1);
            } else if (e.key === 'Enter' && e.target.classList.contains('checkout-btn')) {
                checkout();
            } else if (e.key === 'Escape') {
                goBack();
            }
        });

        // Add touch gestures for mobile
        let startY = 0;
        let startX = 0;

        document.addEventListener('touchstart', function(e) {
            startY = e.touches[0].clientY;
            startX = e.touches[0].clientX;
        });

        document.addEventListener('touchend', function(e) {
            const endY = e.changedTouches[0].clientY;
            const endX = e.changedTouches[0].clientX;
            const diffY = startY - endY;
            const diffX = startX - endX;

            // Swipe up to go back
            if (Math.abs(diffY) > Math.abs(diffX) && diffY > 50) {
                goBack();
            }
        });

        // Add hover effects for desktop
        if (window.innerWidth > 768) {
            document.querySelectorAll('.nav-item, .product-item, .summary-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        }
    </script>
</body>
</html>
