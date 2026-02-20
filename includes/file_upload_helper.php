<?php

/**
 * Multiple File Upload Helper Function
 * Upload multiple files and return array of filenames
 * @param array $files $_FILES['field_name'] array
 * @param string $destination_dir Destination directory
 * @param array $allowed_types Allowed MIME types
 * @param int $max_files Maximum number of files allowed
 * @return array [bool success, array filenames or string error]
 */
function upload_multiple_files($files, $destination_dir, $allowed_types = ALLOWED_IMAGE_TYPES, $max_files = 5)
{
    // Check if files array is empty
    if (empty($files['name'][0])) {
        return [false, "No files uploaded"];
    }

    // Count files
    $file_count = count($files['name']);

    // Check max files limit
    if ($file_count > $max_files) {
        return [false, "Maximum $max_files files allowed"];
    }

    $uploaded_filenames = [];

    // Process each file
    for ($i = 0; $i < $file_count; $i++) {
        // Reconstruct file array for single file validation
        $file = [
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i],
            'size' => $files['size'][$i]
        ];

        // Upload file
        list($success, $filename_or_error) = upload_file($file, $destination_dir, $allowed_types);

        if (!$success) {
            // Delete already uploaded files on error
            foreach ($uploaded_filenames as $uploaded_file) {
                delete_file($destination_dir . $uploaded_file);
            }
            return [false, "Error uploading file " . ($i + 1) . ": " . $filename_or_error];
        }

        $uploaded_filenames[] = $filename_or_error;
    }

    return [true, $uploaded_filenames];
}
