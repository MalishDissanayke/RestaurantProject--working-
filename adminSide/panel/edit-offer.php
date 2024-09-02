<?php
session_start();
require_once '../config.php'; // Ensure this path is correct

// Check if the user is logged in and is an admin


// Get the offer ID from the URL
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    header("Location: offers-panel.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch the offer details
$sql = "SELECT * FROM offers WHERE id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        $offer = mysqli_fetch_assoc($result);
    } else {
        // Offer not found
        header("Location: offers-panel.php");
        exit;
    }
    mysqli_stmt_close($stmt);
}

// Handle form submission for updating the offer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_offer'])) {
    // Validate and sanitize input
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $subtitle = mysqli_real_escape_string($link, $_POST['subtitle']);
    $description = mysqli_real_escape_string($link, $_POST['description']);

    // Initialize image path
    $image_path = $offer['image_path'];

    // Handle image upload if a new image is provided
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
                    // Delete the old image file
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                    $image_path = $destination;
                } else {
                    $error_message = "Failed to upload image.";
                }
            } else {
                $error_message = "Image size should be less than 2MB.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    // Update the offer in the database
    $sql = "UPDATE offers SET title = ?, subtitle = ?, description = ?, image_path = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssssi', $title, $subtitle, $description, $image_path, $id);
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Offer updated successfully.";
            // Refresh the offer details
            $offer['title'] = $title;
            $offer['subtitle'] = $subtitle;
            $offer['description'] = $description;
            $offer['image_path'] = $image_path;
        } else {
            $error_message = "Database error: " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Offer - Admin Panel</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <!-- Include any additional CSS or JS here -->
    <style>
        .offer-form {
            max-width: 600px;
            margin: 0 auto;
        }
        .offer-form img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
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
    </style>
</head>
<body>
    <?php include '../inc/dashHeader.php';  ?> <!-- If you have a separate navbar file -->

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Offer</h1>

                <!-- Display Messages -->
                <?php if(isset($success_message)): ?>
                    <div class="message success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if(isset($error_message)): ?>
                    <div class="message error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Edit Offer Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i>
                        Update Offer
                    </div>
                    <div class="card-body">
                        <form action="edit-offer.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" class="offer-form">
                            <div class="form-group mb-3">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control" id="title" value="<?php echo htmlspecialchars($offer['title']); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="subtitle">Subtitle</label>
                                <input type="text" name="subtitle" class="form-control" id="subtitle" value="<?php echo htmlspecialchars($offer['subtitle']); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" rows="4" required><?php echo htmlspecialchars($offer['description']); ?></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="current_image">Current Image</label><br>
                                <img src="<?php echo htmlspecialchars($offer['image_path']); ?>" alt="<?php echo htmlspecialchars($offer['title']); ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="image">Change Image (optional)</label>
                                <input type="file" name="image" class="form-control" id="image" accept="image/*">
                                <small class="form-text text-muted">Leave blank to keep the current image.</small>
                            </div>
                            <button type="submit" name="update_offer" class="btn btn-primary">Update Offer</button>
                            <a href="offers-panel.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($link);
?>
