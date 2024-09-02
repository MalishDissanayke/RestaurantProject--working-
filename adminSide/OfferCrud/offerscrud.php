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

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $offerName = mysqli_real_escape_string($link, $_POST['offer_name']);
        $subtitle = mysqli_real_escape_string($link, $_POST['subtitle']);
        $description = mysqli_real_escape_string($link, $_POST['description']);

        $sql = "INSERT INTO offers (offer_name, subtitle, description, image_path) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ssss', $offerName, $subtitle, $description, $filePath);
            if (mysqli_stmt_execute($stmt)) {
                echo '<div class="alert alert-success">Offer uploaded successfully.</div>';
            } else {
                echo '<div class="alert alert-danger">Error: ' . mysqli_error($link) . '</div>';
            }
        }
    } else {
        echo '<div class="alert alert-danger">Failed to upload file.</div>';
    }
}

// Handle offer deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "SELECT image_path FROM offers WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $imagePath);
        mysqli_stmt_fetch($stmt);

        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                $sql = "DELETE FROM offers WHERE id = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, 'i', $id);
                    if (mysqli_stmt_execute($stmt)) {
                        echo '<div class="alert alert-success">Offer deleted successfully.</div>';
                    } else {
                        echo '<div class="alert alert-danger">Error: ' . mysqli_error($link) . '</div>';
                    }
                }
            } else {
                echo '<div class="alert alert-danger">Failed to delete file.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">File not found.</div>';
        }
    }
}

mysqli_close($link);
?>
