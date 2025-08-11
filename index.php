<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>कन्याRaag</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
  <link rel="stylesheet" href="style.css">
  <!-- Standard icons -->
<link rel="icon" type="image/jpg" sizes="32x32" href="photos/क.jpg">
<link rel="icon" type="image/jpg" sizes="16x16" href="photos/क.jpg">
<link rel="apple-touch-icon" sizes="180x180" href="photos/क.jpg">

</head>
<body>

<div class="sticky-header">
  <div class="breaking-news-container">
    <div class="news-items" id="newsItems">
      <span class="news-item active">Big Sale starts tomorrow – up to 70% off!</span>
      <span class="news-item">New arrivals just dropped in men's collection.</span>
      <span class="news-item">Free shipping on orders above ₹999.</span>
      <span class="news-item">Sign up and get ₹100 off on your first purchase!</span>
    </div>
  </div>

  <header>
    <button class="mobile-menu-btn" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>

    <div class="menu">
      <a href="#">Home</a>
      <a href="#">Women</a>
      <a href="#">Co-ord Set</a>
      <a href="#">Crop Top</a>
      <a href="#">Short Kurtis</a>

    </div>

    <div class="brand"><span class="kanya">कन्या</span>Raag</div>

    <div class="search-container">
      <span class="search-placeholder">Search Bar</span>
      <span class="search-rotate" id="rotateText">Lehenga</span>
      <input type="text" aria-label="Search products" />
    </div>
  </header>
</div>

<!-- Hero Section -->
<div class="hero">
  <div class="slides">
    <div class="slide"></div>
    <div class="slide"></div>
    <div class="slide"></div>
  </div>
  <div class="click-area click-left"></div>
  <div class="click-area click-right"></div>
  <div class="dots"></div>
</div>

<!-- Icons -->
<section class="icons-hero">
  <div class="icon-box"><i class="fi fi-tr-shipping-fast"></i><p>24 Hours Dispatch</p></div>
  <div class="icon-box"><i class="fi fi-tr-sewing-machine-alt"></i><p>Customise Design</p></div>
  <div class="icon-box"><i class="fi fi-tr-benefit-porcent"></i><p>Best Prices</p></div>
  <div class="icon-box"><i class="fi fi-tr-smile-plus"></i><p>24<small>X</small>7 Support</p></div>
</section>

<!-- Mobile Navigation Sidebar -->
<div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
<div class="mobile-nav" id="mobileNav">
  <div class="mobile-sale-banner">
    <h3>Summer Sale!</h3>
    <p>Up to 60% off on selected items. Limited time offer!</p>
  </div>
  <a href="#"><i class="fas fa-home"></i> Home</a>
  <a href="#"><i class="fas fa-female"></i> Women</a>
  <a href="#"><i class="fa-solid fa-person-dress-burst"></i>Co-ord Set</a>
  <a href="#"><img width="20" height="20" src="https://img.icons8.com/ios-filled/100/slip-dress.png" alt="slip-dress"/> Kurtis</a>
  <a href="#"><img width="20" height="20" src="https://img.icons8.com/external-victoruler-solid-victoruler/64/external-crop-top-clothes-and-outfit-victoruler-solid-victoruler.png" alt="external-crop-top-clothes-and-outfit-victoruler-solid-victoruler"/>Crop Top</a>
  <a href="#"><i class="fas fa-percentage"></i> Sale</a>
  <a href="#"><i class="fa-solid fa-truck"></i> Track Your Order</a>
  <a href="#"><i class="fas fa-user"></i> Customize</a>
</div>

<!-- Mobile Footer -->
<footer class="mobile-footer">
  <button id="mobileMenuBtn"><i class="fas fa-home"></i> Home</button>
  <a href="#"><i class="fas fa-search"></i> Search</a>
  <a href="#"><i class="fas fa-heart"></i> Suggestions</a>
  <a href="#"><i class="fas fa-shopping-bag"></i> Cart</a>
  <a href="#"><i class="fas fa-percentage"></i> Sale</a>
</footer>

<!-- External JS -->
<script src="script.js"></script>
</body>
</html>
