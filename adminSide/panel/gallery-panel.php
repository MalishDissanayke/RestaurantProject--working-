<?php
session_start();
require_once '../config.php'; // Include database configuration

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $fileName = basename($file['name']);
    $filePath = '../uploads/' . $fileName;

    // Check if the image already exists in the database
    $sql_check = "SELECT id FROM gallery_images WHERE image_name = ?";
    if ($stmt_check = mysqli_prepare($link, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, 's', $fileName);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            echo '<div class="alert alert-danger">This image already exists.</div>';
        } else {
            // Move file to uploads directory
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Save image information to the database
                $sql = "INSERT INTO gallery_images (image_name, image_path) VALUES (?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, 'ss', $fileName, $filePath);
                    if (mysqli_stmt_execute($stmt)) {
                        echo '<div class="alert alert-success">Image uploaded successfully.</div>';
                    } else {
                        echo '<div class="alert alert-danger">Database Error: ' . mysqli_error($link) . '</div>';
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                echo '<div class="alert alert-danger">Failed to upload file.</div>';
            }
        }
        mysqli_stmt_close($stmt_check);
    }
}

// Handle image deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "SELECT image_path FROM gallery_images WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $imagePath);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($imagePath && file_exists($imagePath)) {
            if (unlink($imagePath)) {
                $sql = "DELETE FROM gallery_images WHERE id = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, 'i', $id);
                    if (mysqli_stmt_execute($stmt)) {
                        echo '<div class="alert alert-success">Image deleted successfully.</div>';
                    } else {
                        echo '<div class="alert alert-danger">Database Error: ' . mysqli_error($link) . '</div>';
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                echo '<div class="alert alert-danger">Failed to delete file from server.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">File not found.</div>';
        }
    }
}

// Fetch and display images
$sql = "SELECT id, image_name, image_path FROM gallery_images";
?>
<?php include '../inc/dashHeader.php'; ?>  <!-- Include your dashboard header -->

<style>
    .wrapper {
        width: 85%;
        padding-left: 200px;
        padding-top: 20px;
    }
    .gallery img {
        width: 150px;
        height: auto;
        margin: 10px;
    }
    .gallery-item {
        display: inline-block;
        position: relative;
    }
    .gallery-item .delete-button {
        position: absolute;
        top: 0;
        right: 0;
        background-color: red;
        color: white;
        padding: 5px;
        cursor: pointer;
    }
</style>

<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-5 mb-3">
                    <h2 class="pull-left">Gallery</h2>

                    <!-- Image Upload Form -->
                    <form method="POST" action="gallery-panel.php" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="file" name="image" id="image" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="gallery">';
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="gallery-item">';
                            echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['image_name']) . '">';
                            echo '<a href="gallery-panel.php?delete=' . $row['id'] . '" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this image?\')">X</a>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-danger"><em>No images found.</em></div>';
                    }
                    mysqli_free_result($result);
                } else {
                    echo '<div class="alert alert-danger">Oops! Something went wrong. Please try again later.</div>';
                }

                // Close connection
                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>  <!-- Include your dashboard footer -->
