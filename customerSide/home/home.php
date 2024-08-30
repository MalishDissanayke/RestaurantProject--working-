<?php include_once('../components/header.php')?>

<!-- Full Page Video Background -->
<div id="video-background" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden;">
    <video autoplay loop muted playsinline poster="your-poster-image.jpg" style="width: 100%; height: 100%; object-fit: cover;">
        <source src="../image/SteakOnGrillCloseup.mp4" type="video/mp4">
        <!-- Add additional source elements for other video formats if needed -->
    </video>
</div>

<!-- Main Content -->
<div style="position: relative; z-index: 1;">
    <!-- Hero Section with Text Overlay -->
    <section id="hero">
        <div class="hero container">
            <div>
                <h1 class="text-center" style="font-family:Copperplate; color:whitesmoke;"><strong>PASSANI'S</strong></h1>
                <h1 style="color:white;"><strong>DINING & BAR</strong></h1>
                <a href="#projects" type="button" class="cta">MENU</a>
            </div>
        </div>
    </section>
    <!-- End Hero Section -->

    <!-- Menu Section -->
    <section id="projects">
        <div class="projects container">
            <div class="projects-header">
                <h1 class="section-title" style="color: white;">Me<span>n</span>u</h1>
            </div>
            <select id="menu-selector" style="text-align:center; color: red; width: 200px;">
                <option value="all">ALL ITEMS</option>
                <option value="main-dishes">MAIN DISHES</option>
                <option value="side-dishes">SIDE DISHES</option>
                <option value="drinks">DRINKS</option>
            </select>

            <!-- Main Dishes Section -->
            <div class="menu-section" id="main-dishes" style="display: none;">
                <div class="menu-content">
                    <h1 style="text-align:center; color: white;">MAIN DISHES</h1>
                    <?php foreach ($mainDishes as $item): ?>
                        <p>
                            <span class="item-name" style="color: white;"><strong><?php echo $item['item_name']; ?></strong></span>
                            <span class="item-price" style="color: white;">RS<?php echo $item['item_price']; ?></span><br>
                            <span class="item_type" style="color: white;"><i><?php echo $item['item_type']; ?></i></span>
                            <hr>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Side Dishes Section -->
            <div class="menu-section" id="side-dishes" style="display: none;">
                <div class="menu-content">
                    <h1 style="text-align:center; color: white;">SIDE DISHES</h1>
                    <?php foreach ($sides as $item): ?>
                        <p>
                            <span class="item-name" style="color: white;"><strong><?php echo $item['item_name']; ?></strong></span>
                            <span class="item-price" style="color: white;">RS<?php echo $item['item_price']; ?></span><br>
                            <span class="item_type" style="color: white;"><i><?php echo $item['item_type']; ?></i></span>
                            <hr>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Drinks Section -->
            <div class="menu-section" id="drinks" style="display: none;">
                <div class="menu-content">
                    <h1 style="text-align:center; color: white;">DRINKS</h1>
                    <?php foreach ($drinks as $item): ?>
                        <p>
                            <span class="item-name" style="color: white;"><strong><?php echo $item['item_name']; ?></strong></span>
                            <span class="item-price" style="color: white;">RS<?php echo $item['item_price']; ?></span><br>
                            <span class="item_type" style="color: white;"><i><?php echo $item['item_type']; ?></i></span>
                            <hr>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- End Menu Section -->

    <!-- About Section -->
    <section id="about">
        <div class="about container">
            <div class="col-right">
                <h1 class="section-title" style="color: white;">About <span>Us</span></h1>
                <h2 style="color: white;">Johnny's DINING & BAR Company History:</h2>
                <p style="color: white;">Johnny's Dining & Bar is a well-established Western food establishment in the city's heart. Johnny's Dining & Bar has become a popular choice for customers looking to celebrate special occasions or simply enjoy a relaxing meal, with a focus on providing delicious meals and a friendly dining experience.</p>
                <p style="color: white;">Johnny's Dining & Bar, as a Western restaurant, offers a diverse menu that caters to a variety of tastes. The menu includes a wide range of options such as bar bites, salads, soups and a variety of main courses. Customers can savour succulent options such as steak and ribs, chicken, lamb, seafood, burgers and sandwiches, pasta, and a variety of delectable side dishes. The menu has been carefully curated to offer a balance of classic favourites and innovative creations, ensuring that every palate is satisfied.</p>
                <p style="color: white;">Johnny's Dining & Bar's ability to accommodate customers is one of its distinguishing features. Johnny's Dining & Bar strives to create an inviting and comfortable dining environment, whether guests prefer to walk in or make reservations in advance. The restaurant recognises the significance of creating memorable experiences, particularly for those celebrating special occasions. Johnny's Dining & Bar is a popular choice for families, couples, and groups of friends because of its attentive staff and welcoming atmosphere.</p>
                <p style="color: white;">Johnny's Dining & Bar has an inviting outdoor bar that is open seven days a week from 11:00 AM to 10:00 PM in addition to the indoor dining area. This outdoor space provides a relaxed setting for patrons to unwind and socialise while sipping on their favourite drinks and nibbling on bar bites. The bar serves a wide range of beverages, including cocktails, wines, beers and non-alcoholic options.</p>
            </div>
        </div>
    </section>
    <!-- End About Section -->

    <!-- Contact Section -->
    <section id="contact">
        <div class="contact container">
            <div>
                <h1 class="section-title" style="color: white;">Contact <span>info</span></h1>
            </div>
            <div class="contact-items">
                <div class="contact-item contact-item-bg">
                    <div class="contact-info">
                        <div class='icon'><img src="../image/icons8-phone-100.png" alt=""/></div>
                        <h1 style="color: white;">Phone</h1>
                        <h2><a href="tel:+94766642907" style="color: white;">+94 76 664 2907</a></h2>
                    </div>
                </div>
                <div class="contact-item contact-item-bg">
                    <div class="contact-info">
                        <div class='icon'><img src="../image/icons8-email-100.png" alt=""/></div>
                        <h1 style="color: white;">Email</h1>
                        <h2><a href="mailto:pasani@gmail.com" style="color: white;">pasani@gmail.com</a></h2>
                    </div>
                </div>
                <div class="contact-item contact-item-bg">
                    <div class="contact-info">
                        <div class='icon'><img src="../image/icons8-home-address-100.png" alt=""/></div>
                        <h1 style="color: white;">Address</h1>
                        <h2 style="color: white;">Somewhere in Sri lanka</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Contact Section -->
</div>

<?php include_once('../components/footer.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuSelector = document.getElementById('menu-selector');
        const menuSections = document.querySelectorAll('.menu-section');

        menuSelector.addEventListener('change', function() {
            const selectedValue = menuSelector.value;

            menuSections.forEach(section => {
                if (selectedValue === 'all' || section.id === selectedValue) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });

        // Optional: Trigger a change event on page load to show the initial state
        menuSelector.dispatchEvent(new Event('change'));
    });
</script>

<style>
    #video-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
    }

    .menu-section {
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
    }

    .menu-content {
        color: white;
    }

    .menu-section h1 {
        color: white;
    }
</style>
