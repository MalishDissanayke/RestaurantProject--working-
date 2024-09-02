<?php
session_start();
require_once '../config.php'; // Ensure this path is correct
include '../inc/dashHeader.php'; // Update this path if necessary

// Check if the user is logged in and is an admin
if (!isset($_SESSION['logged_account_id']) || !isset($_SESSION['logged_staff_name'])) {
    die("You must be logged in to access this page.");
}

// Handle form submission for adding a new offer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_offer'])) {
    // Validate and sanitize input
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $subtitle = mysqli_real_escape_string($link, $_POST['subtitle']);
    $description = mysqli_real_escape_string($link, $_POST['description']);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetmp = $_FILES['image']['tmp_name'];
        $filesize = $_FILES['image']['size'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Validate file extension
        if (in_array($ext, $allowed)) {
            // Validate file size (e.g., max 2MB)
            if ($filesize < 2 * 1024 * 1024) {
                $new_filename = uniqid() . "." . $ext;
                $destination = '../uploads/offers/' . $new_filename;

                // Create the uploads/offers directory if it doesn't exist
                if (!is_dir('../uploads/offers/')) {
                    mkdir('../uploads/offers/', 0777, true);
                }

                if (move_uploaded_file($filetmp, $destination)) {
                    // Insert into database
                    $sql = "INSERT INTO offers (title, subtitle, description, image_path) VALUES (?, ?, ?, ?)";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, 'ssss', $title, $subtitle, $description, $destination);
                        if (mysqli_stmt_execute($stmt)) {
                            $success_message = "Offer added successfully.";
                        } else {
                            $error_message = "Database error: " . mysqli_error($link);
                        }
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    $error_message = "Failed to upload image.";
                }
            } else {
                $error_message = "Image size should be less than 2MB.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        $error_message = "Please upload an image.";
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Fetch the image path to delete the file
    $sql = "SELECT image_path FROM offers WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $image_path);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Delete the image file
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete the offer from the database
        $sql = "DELETE FROM offers WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Offer deleted successfully.";
            } else {
                $error_message = "Error deleting offer: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Fetch all offers
$sql = "SELECT * FROM offers ORDER BY created_at DESC";
$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Offers - Admin Panel</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 56px; /* Adjust this if your navbar height changes */
        }
        .offer-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 5px;
        }
        .offer-item img {
            max-width: 150px;
            margin-right: 20px;
            border-radius: 5px;
        }
        .offer-details {
            flex: 1;
        }
        .offer-actions a {
            margin-right: 10px;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .container-fluid {
            padding: 15px;
        }
    </style>
</head>
<body>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Manage Offers</h1>

                <!-- Display Messages -->
                <?php if (isset($success_message)): ?>
                    <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <!-- Add Offer Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-plus me-1"></i>
                        Add New Offer
                    </div>
                    <div class="card-body">
                        <form action="offers-panel.php" method="post" enctype="multipart/form-data">
                            <div class="form-group mb-3">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control" id="title" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="subtitle">Subtitle</label>
                                <input type="text" name="subtitle" class="form-control" id="subtitle" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" rows="4" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="image">Offer Image</label>
                                <input type="file" name="image" class="form-control" id="image" accept="image/*" required>
                            </div>
                            <button type="submit" name="add_offer" class="btn btn-primary">Add Offer</button>
                        </form>
                    </div>
                </div>

                <!-- List of Offers -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-tags me-1"></i>
                        Existing Offers
                    </div>
                    <div class="card-body">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <div class="offers-list">
                                <?php while ($offer = mysqli_fetch_assoc($result)): ?>
                                    <div class="offer-item">
                                        <img src="<?php echo htmlspecialchars($offer['image_path']); ?>" alt="<?php echo htmlspecialchars($offer['title']); ?>">
                                        <div class="offer-details">
                                            <h3><?php echo htmlspecialchars($offer['title']); ?></h3>
                                            <h5><?php echo htmlspecialchars($offer['subtitle']); ?></h5>
                                            <p><?php echo nl2br(htmlspecialchars($offer['description'])); ?></p>
                                            <small>Created on: <?php echo date('F j, Y, g:i a', strtotime($offer['created_at'])); ?></small>
                                        </div>
                                        <div class="offer-actions">
                                            <a href="edit-offer.php?id=<?php echo $offer['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="offers-panel.php?delete=<?php echo $offer['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this offer?');">Delete</a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p>No offers found. Add a new offer to get started.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($link);
?>
