<?php
require_once '../config.php';
session_start();

// Check if user is an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $fileName = basename($file['name']);
    $filePath = '../uploads/' . $fileName;

    // Move file to uploads directory
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Save image information to the database
        $sql = "INSERT INTO gallery_images (image_name, image_path) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ss', $fileName, $filePath);
            if (mysqli_stmt_execute($stmt)) {
                echo '<div class="alert alert-success">Image uploaded successfully.</div>';
            } else {
                echo '<div class="alert alert-danger">Error: ' . mysqli_error($link) . '</div>';
            }
        }
    } else {
        echo '<div class="alert alert-danger">Failed to upload file.</div>';
    }
}

}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "SELECT image_path FROM gallery_images WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $imagePath);
    mysqli_stmt_fetch($stmt);

    if (unlink($imagePath)) {
        $sql = "DELETE FROM gallery_images WHERE id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if (mysqli_stmt_execute($stmt)) {
            echo "Image deleted successfully.";
        } else {
            echo "Error: " . mysqli_error($link);
        }
    } else {
        echo "Failed to delete file.";
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery CRUD</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Gallery CRUD</h1>

    <!-- Image Upload Form -->
    <form action="GalleryCrud.php" method="post" enctype="multipart/form-data">
        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" required>
        <button type="submit">Upload</button>
    </form>

    <!-- Display Existing Images -->
    <h2>Existing Images</h2>
    <?php
   // Fetch and display images
   $sql = "SELECT id, image_name, image_path FROM gallery_images";
   if ($result = mysqli_query($link, $sql)) {
       if (mysqli_num_rows($result) > 0) {
           echo '<div class="gallery">';
           while ($row = mysqli_fetch_assoc($result)) {
               echo '<div class="gallery-item">';
               echo '<img src="' . $row['image_path'] . '" alt="' . htmlspecialchars($row['image_name']) . '">';
               echo '<a href="gallery-panel.php?delete=' . $row['id'] . '" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this image?\')">X</a>';
               echo '</div>';
           }
           echo '</div>';
       } else {
           echo '<div class="alert alert-danger"><em>No images found.</em></div>';
       }
   } else {
       echo '<div class="alert alert-danger">Oops! Something went wrong. Please try again later.</div>';
   }

    ?>
</body>
</html>
