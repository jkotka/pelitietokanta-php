<?php
// ---------- FUNCTIONS ---------- //

require_once 'functions.php';
set_lang();

// ---------- DELETE IMAGE ---------- //

if (!empty($_GET['delete'])) {
    $title_id = (int)$_GET['delete'];

    // Set cover & thumbnail
    $cover = $covers . $title_id . '.jpg';
    $thumbnail = $thumbnails . $title_id . '_thumb.jpg';

    // Delete cover & thumbnail
    if (file_exists($cover)) {
        unlink($cover);
    }
    if (file_exists($thumbnail)) {
        unlink($thumbnail);
    }
    // Redirect back to title page
    header("Location:title.php?t=$title_id");
    exit;
}

// ---------- UPLOAD AND RESIZE IMAGE ---------- //

elseif (!empty($_GET['upload'])) {
    $title_id = (int)$_GET['upload'];

    // Check if image is uploaded
    if (!empty($_FILES['image']['tmp_name'])) {

        // Uploaded image
        $image = $_FILES['image']['tmp_name'];

        // Allowed extensions
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');

        // Get image size
        $size = $_FILES['image']['size'];

        // Get image extension
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        // Cover image
        $cover = $covers . $title_id . '.jpg';

        // Check if image extension is allowed and size is max 5MB
        if (in_array($ext, $allowed_ext) && $size <= 5242880) {

            // If cover already exists, delete it
            if (file_exists($cover)) {
                unlink($cover);
            }
            // Resize image (image, extension, width, resized image)
            resize_image($image, $ext, 350, $cover);
            
            // Create thumbnail
            $thumbnail = $thumbnails . $title_id . '_thumb.jpg';
            if (file_exists($thumbnail)) {
                unlink($thumbnail);
            }
            resize_image($image, $ext, 80, $thumbnail);

            // Redirect back to title page
            header("Location:title.php?t=$title_id");
            exit;

        // If type is wrong or image too large, exit
        } else {
            header("Location:title.php?t=$title_id&e=img_upload_error");
            exit;
        }
    } else {
        // If image is not set, exit
        header("Location:title.php?t=$title_id&e=img_upload_error");
        exit;
    }

// If upload or delete are not set, redirect
} else {
    header('Location:index.php');
    exit;
}
?>