<?php
session_start();
require "../database_confidence.php"; // Adjust path if needed

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login");
    exit;
}

// Check user rank
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT m3u_rank FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Redirect if user doesn't have m3u_rank
if (!$user || $user["m3u_rank"] != 1) {
    header("Location: ../"); // Redirect to home page
    exit;
}

// Delete old files (older than 30 minutes)
$download_folder = "downloads/";
if (is_dir($download_folder)) {
    foreach (glob($download_folder . "*.m3u") as $file) {
        if (filemtime($file) < time() - 1800) { // 1800 seconds = 30 minutes
            unlink($file);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = filter_var($_POST["url"], FILTER_SANITIZE_URL);
    $agreement = isset($_POST["agree"]);

    if ($agreement && filter_var($url, FILTER_VALIDATE_URL)) {
        $filename = basename(parse_url($url, PHP_URL_PATH));
        $filePath = $download_folder . $filename;

        // Create downloads folder if not exists
        if (!is_dir($download_folder)) {
            mkdir($download_folder, 0777, true);
        }

        // Use cURL to fetch file
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "OTT Navigator/1.0");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $fileContents = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($fileContents !== false) {
            file_put_contents($filePath, $fileContents);
            
            // Auto-download file
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Expires: 0");
            header("Cache-Control: must-revalidate");
            header("Pragma: public");
            header("Content-Length: " . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            echo "<p class='error'>Download failed. Error: $error</p>";
        }
    } else {
        echo "<p class='error'>Invalid URL or agreement not checked.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M3U File Downloader</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }
        input, button {
            padding: 10px;
            margin: 10px;
            width: 80%;
            border: none;
            border-radius: 5px;
        }
        input[type="url"] {
            background-color: #333;
            color: white;
        }
        button {
            background-color: #ff4500;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff5733;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Download M3U File</h2>
        <form method="post">
            <label>Enter URL:</label>
            <input type="url" name="url" required>
            <br>
            <input type="checkbox" name="agree" required> I agree to the terms and conditions.
            <br><br>
            <button type="submit">Download</button>
        </form>
    </div>
</body>
</html><?php
session_start();
require "../database_confidence.php"; // Adjust path if needed

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login");
    exit;
}

// Check user rank
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT m3u_rank FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Redirect if user doesn't have m3u_rank
if (!$user || $user["m3u_rank"] != 1) {
    header("Location: ../"); // Redirect to home page
    exit;
}

// Delete old files (older than 30 minutes)
$download_folder = "downloads/";
if (is_dir($download_folder)) {
    foreach (glob($download_folder . "*.m3u") as $file) {
        if (filemtime($file) < time() - 1800) { // 1800 seconds = 30 minutes
            unlink($file);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = filter_var($_POST["url"], FILTER_SANITIZE_URL);
    $agreement = isset($_POST["agree"]);

    if ($agreement && filter_var($url, FILTER_VALIDATE_URL)) {
        $filename = basename(parse_url($url, PHP_URL_PATH));
        $filePath = $download_folder . $filename;

        // Create downloads folder if not exists
        if (!is_dir($download_folder)) {
            mkdir($download_folder, 0777, true);
        }

        // Use cURL to fetch file
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "OTT Navigator/1.0");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $fileContents = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($fileContents !== false) {
            file_put_contents($filePath, $fileContents);
            
            // Auto-download file
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Expires: 0");
            header("Cache-Control: must-revalidate");
            header("Pragma: public");
            header("Content-Length: " . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            echo "<p class='error'>Download failed. Error: $error</p>";
        }
    } else {
        echo "<p class='error'>Invalid URL or agreement not checked.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M3U File Downloader</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }
        input, button {
            padding: 10px;
            margin: 10px;
            width: 80%;
            border: none;
            border-radius: 5px;
        }
        input[type="url"] {
            background-color: #333;
            color: white;
        }
        button {
            background-color: #ff4500;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff5733;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Download M3U File</h2>
        <form method="post">
            <label>Enter URL:</label>
            <input type="url" name="url" required>
            <br>
            <input type="checkbox" name="agree" required> I agree to the terms and conditions.
            <br><br>
            <button type="submit">Download</button>
        </form>
    </div>
</body>
</html>
