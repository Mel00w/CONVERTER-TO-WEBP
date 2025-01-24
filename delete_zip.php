<?php

$zip_dir = 'img_zip/';
$zip_name = $zip_dir . 'converted_images.zip';

if (file_exists($zip_name)) {
    unlink($zip_name);
    echo "ZIP file deleted.";
} else {
    echo "No ZIP file to delete.";
}
