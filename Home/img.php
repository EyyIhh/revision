<?php
// Example of serving an image from outside the htdocs directory
$image_path = 'C:/path/to/your/external/uploads/' . $_GET['file']; // Get the file from the query string

// Check if the file exists
if (file_exists($image_path)) {
    // Set headers for image content
    header('Content-Type: image/jpeg'); // Adjust to the correct MIME type based on your image type
    readfile($image_path); // Output the file
} else {
    echo "File not found.";
}
?>
