<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Handle file uploads with validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check if files were uploaded
if (!isset($_FILES['files']) || empty($_FILES['files']['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No files uploaded']);
    exit;
}

// Configuration
$uploadDir = __DIR__ . '/uploads/';
$maxFileSize = 500 * 1024 * 1024; // 500 MB for videos
$maxImageSize = 10 * 1024 * 1024; // 10 MB for images
$allowedVideoTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-matroska', 'video/webm', 'video/mpeg'];
$allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
$allowedExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'mpeg', 'mpg', 'jpg', 'jpeg', 'png', 'webp', 'gif'];

// Ensure upload directory exists
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create upload directory']);
        exit;
    }
}

// Handle multiple files
$uploadedUrls = [];
$errors = [];

// Support both single and multiple file uploads
$files = $_FILES['files'];
$fileCount = is_array($files['name']) ? count($files['name']) : 1;

for ($i = 0; $i < $fileCount; $i++) {
    // Get file info
    if (is_array($files['name'])) {
        $fileName = $files['name'][$i];
        $fileTmpName = $files['tmp_name'][$i];
        $fileSize = $files['size'][$i];
        $fileError = $files['error'][$i];
        $fileType = $files['type'][$i];
    } else {
        $fileName = $files['name'];
        $fileTmpName = $files['tmp_name'];
        $fileSize = $files['size'];
        $fileError = $files['error'];
        $fileType = $files['type'];
    }

    // Check for upload errors
    if ($fileError !== UPLOAD_ERR_OK) {
        $errors[] = "File '$fileName': Upload error code $fileError";
        continue;
    }

    // Get file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Validate file extension
    if (!in_array($fileExt, $allowedExtensions)) {
        $errors[] = "File '$fileName': Invalid file type. Allowed: " . implode(', ', $allowedExtensions);
        continue;
    }

    // Validate MIME type and size
    $isVideo = in_array($fileType, $allowedVideoTypes);
    $isImage = in_array($fileType, $allowedImageTypes);
    
    if (!$isVideo && !$isImage) {
        $errors[] = "File '$fileName': Invalid MIME type '$fileType'";
        continue;
    }

    // Check file size
    if ($isVideo && $fileSize > $maxFileSize) {
        $maxSizeMB = $maxFileSize / (1024 * 1024);
        $errors[] = "File '$fileName': Video file too large. Max size: {$maxSizeMB}MB";
        continue;
    }
    
    if ($isImage && $fileSize > $maxImageSize) {
        $maxSizeMB = $maxImageSize / (1024 * 1024);
        $errors[] = "File '$fileName': Image file too large. Max size: {$maxSizeMB}MB";
        continue;
    }

    // Generate unique filename to avoid collisions
    $timestamp = time();
    $randomStr = bin2hex(random_bytes(8));
    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($fileName, PATHINFO_FILENAME));
    $newFileName = "{$timestamp}_{$randomStr}_{$safeName}.{$fileExt}";
    $targetPath = $uploadDir . $newFileName;

    // Move uploaded file
    if (move_uploaded_file($fileTmpName, $targetPath)) {
        // Return relative URL path
        $fileUrl = '/api/uploads/' . $newFileName;
        $uploadedUrls[] = $fileUrl;
    } else {
        $errors[] = "File '$fileName': Failed to save file";
    }
}

// Return response
if (count($uploadedUrls) > 0) {
    $response = [
        'success' => true,
        'urls' => $uploadedUrls,
        'count' => count($uploadedUrls)
    ];
    
    if (count($errors) > 0) {
        $response['warnings'] = $errors;
    }
    
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No files were uploaded successfully',
        'details' => $errors
    ]);
}
