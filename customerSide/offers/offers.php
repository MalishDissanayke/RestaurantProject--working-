<?php
require_once '../../adminSide/config.php'; // Ensure this path is correct
session_start(); // Ensure session is started



// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    echo '<div class="user-profile">';
    echo 'Welcome, ' . $_SESSION["member_name"] . '!';
    echo '<a href="../customerProfile/profile.php">Profile</a>';
    echo '</div>';
}

// Fetch offers from the database
$sql = "SELECT * FROM offers";
$result = mysqli_query($link, $sql);

if (!$result) {
    die("Error fetching offers: " . mysqli_error($link));
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

    <title>Offers</title>

    <!-- Inline CSS for Offers -->
    <style>
        /* Offers Styles */
        #offers {
            padding: 20px;
            background-color: #333;
        }

        #offers .section-title {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .offer-item {
            background-color: #444;
            border-radius: 8px;
            overflow: hidden;
            color: white;
            text-align: center;
            padding: 10px;
        }

        .offer-item img {
            width: 100%;
            height: auto;
            display: block;
            border-bottom: 2px solid #ddd;
        }

        .offer-item h2 {
            margin: 10px 0;
        }

        .offer-item h3 {
            margin: 5px 0;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <!-- Include the Header -->
    <?php include_once('../components/header.php'); ?>

    <!-- Offers Section -->
    <section id="offers">
        <div class="offers container">
            <h1 class="section-title">Offers</h1>
            <div class="offers-grid">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Correct the path to the images

                       $imagePath = 'http://localhost/RestaurantProject/adminSide/offers/' . htmlspecialchars($row['image_path']);



                        echo '<div class="offer-item">';
                        echo '<img src="' . $imagePath . '" alt="Offer Image">';
                        echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                        echo '<h3>' . htmlspecialchars($row['subtitle']) . '</h3>';
                        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No offers available at the moment.</p>';
                }
                ?>
            </div>
        </div>
    </section>
    <!-- End Offers Section -->

    <!-- Footer -->
    <?php include_once('../components/footer.php'); ?>
</body>
</html>

<?php
// Close the database connection
mysqli_close($link);
?>
