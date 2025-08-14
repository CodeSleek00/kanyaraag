<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>कन्याRaag</title>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-rounded/css/uicons-thin-rounded.css" />

  <!-- Styles -->
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="caegory.css" />

  <!-- Favicons -->
  <link rel="icon" type="image/jpg" sizes="32x32" href="photos/क.jpg" />
  <link rel="icon" type="image/jpg" sizes="16x16" href="photos/क.jpg" />
  <link rel="apple-touch-icon" sizes="180x180" href="photos/क.jpg" />
</head>

<body>
  <!-- Sticky Header -->
  <div class="sticky-header">
    <!-- Breaking News -->
    <div class="breaking-news-container" aria-live="polite">
      <div class="news-items" id="newsItems">
        <span class="news-item active">Big Sale starts tomorrow – up to 70% off!</span>
        <span class="news-item">New arrivals just dropped in men's collection.</span>
        <span class="news-item">Free shipping on orders above ₹999.</span>
        <span class="news-item">Sign up and get ₹100 off on your first purchase!</span>
      </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
      <!-- Mobile Menu Button -->
      <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Open menu">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Navigation Menu -->
      <nav class="menu" aria-label="Main navigation">
        <a href="#">Home</a>
        <a href="#">Women</a>
        <a href="#">Co-ord Set</a>
        <a href="#">Crop Top</a>
        <a href="#">Short Kurtis</a>
      </nav>

      <!-- Brand -->
      <div class="brand">
        <span class="kanya">कन्या</span>Raag
      </div>

      <!-- Search -->
      <div class="search-container">
        <label for="site-search" class="visually-hidden">Search products</label>
        <span class="search-placeholder">Search Bar</span>
        <span class="search-rotate" id="rotateText">Lehenga</span>
        <input id="site-search" type="text" aria-label="Search products" />
      </div>
    </header>
  </div>

  <!-- Hero Section -->
  <section class="hero" aria-label="Promotional slides">
    <div class="slides">
      <div class="slide"></div>
      <div class="slide"></div>
      <div class="slide"></div>
    </div>
    <div class="click-area click-left" role="button" aria-label="Previous slide"></div>
    <div class="click-area click-right" role="button" aria-label="Next slide"></div>
    <div class="dots" aria-hidden="true"></div>
  </section>

  <!-- Feature Icons -->
  <section class="icons-hero" aria-label="Service highlights">
    <div class="icon-box"><i class="fi fi-tr-shipping-fast"></i><p>24 Hours Dispatch</p></div>
    <div class="icon-box"><i class="fi fi-tr-sewing-machine-alt"></i><p>Customise Design</p></div>
    <div class="icon-box"><i class="fi fi-tr-benefit-porcent"></i><p>Best Prices</p></div>
    <div class="icon-box"><i class="fi fi-tr-smile-plus"></i><p>24<small>X</small>7 Support</p></div>
  </section>

  <!-- Mobile Navigation -->
  <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
  <nav class="mobile-nav" id="mobileNav" aria-label="Mobile navigation">
    <div class="mobile-sale-banner">
      <h3>Summer Sale!</h3>
      <p>Up to 60% off on selected items. Limited time offer!</p>
    </div>
    <a href="#"><i class="fas fa-home"></i> Home</a>
    <a href="#"><i class="fas fa-female"></i> Women</a>
    <a href="#"><i class="fa-solid fa-person-dress-burst"></i> Co-ord Set</a>
    <a href="#"><img width="20" height="20" src="https://img.icons8.com/ios-filled/100/slip-dress.png" alt="Kurtis icon"/> Kurtis</a>
    <a href="#"><img width="20" height="20" src="https://img.icons8.com/external-victoruler-solid-victoruler/64/external-crop-top-clothes-and-outfit-victoruler-solid-victoruler.png" alt="Crop Top icon"/> Crop Top</a>
    <a href="#"><i class="fas fa-percentage"></i> Sale</a>
    <a href="#"><i class="fa-solid fa-truck"></i> Track Your Order</a>
    <a href="#"><i class="fas fa-user"></i> Customize</a>
  </nav>

  <!-- Mobile Footer -->
  <footer class="mobile-footer" aria-label="Quick access menu">
    <a href="#"><i class="fas fa-home"></i> Home</a>
    <a href="#"><i class="fas fa-search"></i> Search</a>
    <a href="#"><i class="fas fa-heart"></i> Suggestions</a>
    <a href="#"><i class="fas fa-shopping-bag"></i> Cart</a>
    <a href="#"><i class="fas fa-percentage"></i> Sale</a>
  </footer>

  <!-- Category Cards -->
  <section class="category-cards" aria-label="Shop by category">
    <div class="card">
      <img src="hero model 2.jpeg" alt="24 Hr Dispatch category image" />
      <div class="card-content">
        <h3>24 Hr Dispatch</h3>
        <p>Quick fashion finds</p>
        <button>Shop Now</button>
      </div>
    </div>
    <div class="card">
      <img src="hero model 2.jpeg" alt="Wedding Wardrobe category image" />
      <div class="card-content">
        <h3>Wedding Wardrobe '25</h3>
        <p>Made for everyone, tailored for love</p>
        <button>Shop Now</button>
      </div>
    </div>
    <div class="card">
      <img src="hero model 2.jpeg" alt="Independence Specials category image" />
      <div class="card-content">
        <h3>Independence Specials</h3>
        <p>Freedom looks good on us</p>
        <button>Shop Now</button>
      </div>
    </div>
    <div class="card">
      <img src="hero model 2.jpeg" alt="Bestsellers category image" />
      <div class="card-content">
        <h3>Bestsellers</h3>
        <p>Elevate your closet game</p>
        <button>Shop Now</button>
      </div>
    </div>
  </section>

  <!-- Scripts -->
  <script src="script.js"></script>
</body>
</html>
