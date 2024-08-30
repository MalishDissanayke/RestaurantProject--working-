<?php
require_once '../config.php';
session_start(); // Ensure session is started

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    echo '<div class="user-profile">';
    echo 'Welcome, ' . $_SESSION["member_name"] . '!';
    echo '<a href="../customerProfile/profile.php">Profile</a>';
    echo '</div>';
}

// Fetch images from the database
$sql = "SELECT image_path FROM gallery_images";
$result = mysqli_query($link, $sql);

if (!$result) {
    die("Error fetching images: " . mysqli_error($link));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <title>Gallery</title>

    <!-- Inline CSS for Gallery -->
    <style>
        /* Gallery Styles */
        #gallery {
            padding: 20px;
            background-color: #333;
        }

        #gallery .section-title {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <!-- Include the Header -->
    <?php include_once('../components/header.php'); ?>

    <!-- Gallery Section -->
    <section id="gallery">
        <div class="gallery container">
            <h1 class="section-title">Gallery</h1>
            <div class="gallery-grid">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Construct the path to the images
                        $imagePath = 'http://localhost/RestaurantProject/adminSide/uploads/' . htmlspecialchars($row['image_path']);

                        // Debugging: Output the image path
                        echo '<!-- Debugging: ' . $imagePath . ' -->';

                        echo '<div class="gallery-item">';
                        echo '<img src="' . $imagePath . '" alt="Gallery Image">';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No images found.</p>';
                }
                ?>
            </div>
        </div>
    </section>
    <!-- End Gallery Section -->

    <!-- Footer -->
    <?php include_once('../components/footer.php'); ?>
</body>
</html>
