<?php include_once('../components/header.php')?>
<!-- Hero Section with Video Background and Text Overlay -->
<section id="hero" style="position: relative;">
    <video autoplay loop muted playsinline poster="your-poster-image.jpg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
        <source src="../image/SteakOnGrillCloseup.mp4" type="video/mp4">
        <!-- Add additional source elements for 
        1.  SteakOnGrillCloseup

        other video formats if needed -->
    </video>
    <div class="hero container" style="position: relative; z-index: 1;">
        <div>
            <h1><strong><h1 class="text-center" style="font-family:Copperplate; color:whitesmoke;">The Hungry Plate</h1><span></span></strong></h1>
            <h1><strong style="color:white;">DINING & BAR<span></span></strong></h1>
            <a href="../CustomerReservation/reservePage.php" type="button" class="cta">Reserve a Table!</a>
        </div>
    </div>
</section>
<!-- End Hero Section -->
  
  
  <?php
$category = $_GET['category'] ?? 'all';
?>
<!-- MENU SECTION -->
<section id="projects">
  <div class="projects container">

    <div class="projects-header">
      <h1 class="section-title">Me<span>n</span>u</h1>
    </div>

    <!-- MENU TABS (NO JS) -->
    <div class="menu-tabs">

      <input type="radio" name="menu" id="tab-all" checked>
      <input type="radio" name="menu" id="tab-main">
      <input type="radio" name="menu" id="tab-side">
      <input type="radio" name="menu" id="tab-drinks">

      <!-- BUTTONS -->
      <div class="menu-buttons">
        <label for="tab-all">ALL ITEMS</label>
        <label for="tab-main">MAIN DISHES</label>
        <label for="tab-side">SIDE DISHES</label>
        <label for="tab-drinks">DRINKS</label>
      </div>

      <!-- ALL ITEMS -->
      <div class="menu-content all">
        <div class="grid">

          <div class="menu-box">
            <h2>Main Dishes</h2>
            <?php foreach ($mainDishes as $item): ?>
              <p>
                <strong><?= $item['item_name']; ?></strong>
                <span>Php<?= $item['item_price']; ?></span><br>
                <i><?= $item['item_type']; ?></i>
              </p>
            <?php endforeach; ?>
          </div>

          <div class="menu-box">
            <h2>Side Dishes</h2>
            <?php foreach ($sides as $item): ?>
              <p>
                <strong><?= $item['item_name']; ?></strong>
                <span>Php<?= $item['item_price']; ?></span><br>
                <i><?= $item['item_type']; ?></i>
              </p>
            <?php endforeach; ?>
          </div>

          <div class="menu-box">
            <h2>Drinks</h2>
            <?php foreach ($drinks as $item): ?>
              <p>
                <strong><?= $item['item_name']; ?></strong>
                <span>Php<?= $item['item_price']; ?></span><br>
                <i><?= $item['item_type']; ?></i>
              </p>
            <?php endforeach; ?>
          </div>

        </div>
      </div>

      <!-- MAIN -->
      <div class="menu-content main">
        <div class="menu-box">
          <h2>Main Dishes</h2>
          <?php foreach ($mainDishes as $item): ?>
            <p>
              <strong><?= $item['item_name']; ?></strong>
              <span>Php<?= $item['item_price']; ?></span><br>
              <i><?= $item['item_type']; ?></i>
            </p>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- SIDE -->
      <div class="menu-content side">
        <div class="menu-box">
          <h2>Side Dishes</h2>
          <?php foreach ($sides as $item): ?>
            <p>
              <strong><?= $item['item_name']; ?></strong>
              <span>Php<?= $item['item_price']; ?></span><br>
              <i><?= $item['item_type']; ?></i>
            </p>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- DRINKS -->
      <div class="menu-content drinks">
        <div class="menu-box">
          <h2>Drinks</h2>
          <?php foreach ($drinks as $item): ?>
            <p>
              <strong><?= $item['item_name']; ?></strong>
              <span>Php<?= $item['item_price']; ?></span><br>
              <i><?= $item['item_type']; ?></i>
            </p>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<style>
.menu-tabs input { display: none; }

.menu-buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
  flex-wrap: wrap;
  margin: 20px 0;
}

.menu-buttons label {
  padding: 10px 14px;
  background: #1c1c1c;
  color: white;
  cursor: pointer;
  border-radius: 6px;
  font-size: 14px;
}

.menu-buttons label:hover {
  background: #444;
}

.menu-content {
  display: none;
  max-width: 1000px;
  margin: auto;

  max-height: 65vh;      /* controls how tall menu is */
  overflow-y: auto;      /* makes it scrollable */
  padding-right: 10px;   /* avoids scrollbar overlap */
}

/* GRID (ALL ITEMS = 3 columns) */
.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

/* BOX DESIGN */
.menu-box {
  background: #111;
  padding: 15px;
  border-radius: 10px;
  color: white;
}

.menu-box h2 {
  text-align: center;
  font-size: 18px;
  margin-bottom: 10px;
}

.menu-box p {
  font-size: 12px;
  color: #ddd;
  line-height: 1.5;
}

.menu-box strong {
  color: #fff;
}

.menu-box span {
  float: right;
  font-size: 12px;
  color: #ff3758;
}

.menu-box i {
  font-size: 12px;
  color: #aaa;
}

/* SHOW SECTIONS */
#tab-all:checked ~ .all { display: block; }
#tab-main:checked ~ .main { display: block; }
#tab-side:checked ~ .side { display: block; }
#tab-drinks:checked ~ .drinks { display: block; }

/* MOBILE */
@media (max-width: 768px) {
  .grid {
    grid-template-columns: 1fr;
  }

  .menu-content {
    max-height: 60vh;
  }
}
</style>


  
  <!-- About Section -->
<section id="about" ">
  <div class="about container">
    <div class="col-right">
        <h1 class="section-title" >About <span>Us</span></h1>
        <h2>The Hungry Plate Company History (Butuan City, Philippines)</h2>
 <p>The Hungry Plate is a proudly Filipino-themed restaurant located in the heart of Butuan City, Philippines. It has become a favorite dining spot for locals and visitors who want to experience authentic Filipino flavors in a warm and welcoming setting. The restaurant is dedicated to celebrating Filipino culture through food, hospitality, and memorable dining experiences.
 </p>
 <p>The Hungry Plate offers a diverse menu inspired by traditional Filipino cuisine with a modern twist. Guests can enjoy well-loved dishes such as adobo, sinigang, kare-kare, grilled seafood, and flavorful inihaw selections. The menu also includes Filipino-style bar bites, comforting soups, rice meals, and classic desserts like halo-halo and leche flan. Each dish is carefully prepared using local ingredients to highlight the rich flavors of Filipino cooking while adding creative touches that appeal to today’s diners.
 </p>
 <p>The Hungry Plate's ability to accommodate customers is one of its distinguishing features. The restaurant strives to create an inviting and comfortable dining environment, whether guests prefer to walk in or make reservations in advance. The restaurant recognises the significance of creating memorable experiences, particularly for those celebrating special occasions. The Hungry Plate is a popular choice for families, couples, and groups of friends because of its attentive staff and welcoming atmosphere.
 </p>
 <p>The Hungry Plate has an inviting outdoor bar that is open seven days a week from 11:00 AM to 10:00 PM in addition to the indoor dining area.This outdoor space provides a relaxed setting for patrons to unwind and socialise while sipping on their favourite drinks and nibbling on bar bites. The bar serves a wide range of beverages, including cocktails, wines, beers and non-alcoholic options.
 </p>
    
      </div>
    </div>
  </section>
  <!-- End About Section -->
  
  
 <!-- Contact Section -->
<section id="contact">
  <div class="contact container">
    <div>
      <h1 class="section-title">Contact <span>info</span></h1>
    </div>
    <div class="contact-items">
      <div class="contact-item contact-item-bg">
        <div class="contact-info">
          <div class='icon'><img src="../image/icons8-phone-100.png" alt=""/></div>
          <h1>Phone</h1>
          <h2>+63 917 123 4567</h2>
        </div>
      </div>
      
      <div class="contact-item contact-item-bg"> 
        <div class="contact-info">
          <div class='icon'><img src="../image/icons8-email-100.png" alt=""/></div>
          <h1>Email</h1>
          <h2>Thehungryplate@gmail.com</h2> 
        </div>
      </div>
      
      <div class="contact-item contact-item-bg">
        <div class="contact-info">
          <div class='icon'> <img src="../image/icons8-home-address-100.png" alt=""/></div>
          <h1>Address</h1>
          <h2>Montilla Boulevard, Barangay Limaha, Butuan City, Agusan del Norte, 8600</h2>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End Contact Section -->

<?php 
include_once('../components/footer.php');
?>