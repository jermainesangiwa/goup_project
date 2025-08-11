<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ailse 24/7</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="branding">
                <h1>Ailse 24/7</h1>
                
            </div>
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </header>
    <section class="hero-section"></section>
    <div class="container">
    <div class="hero-text">
        <h1> SHABETA</h1>
        <div id="snack-list" class="grid-container"></div>
        </div>
    </div>

    <script>
        const snacks = [
            { name: "APPLES", price: 0.69, image: 'pexels-pixabay-209439.jpg' },
            { name: "BANANAS", price: 0.99, image: "0420EIOLIBananas_SC.jpg" },
            { name: "ORANGES", price: 0.69, image: "GettyImages-1205638014-2000-d0fbf9170f2d43eeb046f56eec65319c.jpg" },
            { name: "GRAPES", price: 1.49, image: "k_Photo_Series_2022-11-types-of-grapes-explainer_types-of-grapes_009.jpg" },
            { name: "STRAWBERRIES", price: 3.99, image: "download.jpg" },
            { name: "MANGOES", price: 0.69, image: "5-health-benefits-of-mango-iStock-463651383.jpg" },
            { name: "WATERMELONS", price: 5.49, image: "fresh-ripe-watermelon-slices-on-wooden-table-royalty-free-image-1684966820.jpg" },
            { name: "PINEAPPLES", price: 3.99, image: "intro-1684948369.jpg" },
            { name: "PEACHES", price: 1.49, image: "SES-how-to-ripen-peaches-2216828-hero-01-d5362bc052394e9893883e788dd5ec8d.jpg" },
            { name: "KIWIFRUITS", price: 0.49, image: "download-1.jpg" },
            { name: "LEMONS", price: 0.69, image: "intro-1699033243.jpg" },
            { name: "DRAGONFRUIT", price: 1.99, image: "white-dragon-fruit-royalty-free-image-1594218654.jpg" },
            { name: "GUAVAS", price: 1.29, image: "guava©iStock.avif" },
            { name: "PAPAYAS", price: 3.49, image: "Papaya-cover-photo-1_600x.webp" },
            { name: "PLUMS", price: 0.69, image: "ten-secrets-of-plums-70896-1.jpg" },
            { name: "BLUEBERRIES", price: 0.99, image: "WhatsAppImage2020-09-07at1.26.19PM.jpg" }
        ];

        const snackList = document.getElementById('snack-list');

        function displaySnacks() {
            snacks.forEach(snack => {
                const snackDiv = document.createElement('div');
                snackDiv.classList.add('snack');

                const image = document.createElement('img');
                image.src = snack.image;
                image.alt = snack.name;

                const name = document.createElement('h3');
                name.textContent = snack.name;

                const price = document.createElement('p');
                price.textContent = `$${snack.price.toFixed(2)}`; /* Formatted price with a dollar sign*/

                snackDiv.appendChild(image);
                snackDiv.appendChild(name);
                snackDiv.appendChild(price);

                snackList.appendChild(snackDiv);
            });
        }

        displaySnacks(); // Populate the snack list
    </script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8; /* Light gray background */
        }
        
        header {
            background-color: #b8671b; /* Green header background */
            color: #fff; /* White text color */
            padding: 25px;
            border-bottom: 2px rgb(84, 59, 41)smoke; /* Darker green border bottom */
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .branding {
            display: flex;
            align-items: center;
        }

        .branding h1 {
            margin: 0;
            font-size: 1.5em;
        }

        .branding p {
            margin: 0;
            font-size: 0.8em;
            opacity: 0.7;
        }
        
        @keyframes moveLeftToRight {
            0% {
                transform: translateX(-100%); /* Initial position */
            }
            100% {
                transform: translateX(100%); /* Final position */
            }
        }
        
        .hero-section {
            height: 200px; /* Hero section height */
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/Users/mac/Downloads/Culinary_fruits_front_view.jpg'); /* Background image */
            background-size: cover;
            background-position: center;

            animation:cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .hero-text {
            text-align: center;
            color: #ccc;
            top: 40%;
            
        }
        
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.95); /* Semi-transparent white background */
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); /* Box shadow for container */
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            grid-gap: 20px;
            margin-top: 20px;
        }
        
        .snack {
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            background-color: #fff; /* White background */
            border-radius: 15px; /* Rounded corners */
            transition: transform 0.2s; /* Smooth transition */
        }
        .snack:hover {
            transform: translateY(-5px); /* Move up on hover */
            box-shadow: 0px 20px 20px rgba(0, 0, 0, 0.1); /* Shadow on hover */
        }
        
        .snack img {
            max-width: 100%;
            height: auto;
            border-radius: 10px; /* Rounded corners */
            
        }
        
        .snack h3 {
            margin-top: 10px; /* Spacing */
            font-size: 1.2em; /* Larger font size */
            color: #333; /* Darker text color */
        }
        
        .snack p {
            color: #16a085; /* Green price color */
            font-weight: bold; /* Bold font */
            margin-top: 5px; /* Spacing */
        }

        .social-icons {
            display: flex;
            align-items: center;
        }

        .social-icon {
            margin-left: 10px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .social-icon i {
            font-size: 1.5em;
            color: #fff;
        }
        #social-icon{
            border-spacing: 15px;
            border-bottom: 55px;
            background-color: '#2ecc71'; /* Green background for social icons */
        }
    </style>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const socialIcons = document.querySelectorAll('.social-icon');
            socialIcons.forEach(icon => {
                setTimeout(() => {
                    icon.style.opacity = '1';
                }, 1000);
            });
        });
    </script>
</body>
</html>
