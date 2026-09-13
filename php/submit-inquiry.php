<?php

require_once "db.php";

$name = $_POST["name"] ?? "";
$email = $_POST["email"] ?? "";
$phone = $_POST["phone"] ?? "";
$service = $_POST["service"] ?? "";
$message = $_POST["message"] ?? "";

$imagePath = "";

if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {

    $imageName = $_FILES["image"]["name"];
    $imageTmp = $_FILES["image"]["tmp_name"];

    $allowedTypes = ["jpg", "jpeg", "png", "webp"];
    $extension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedTypes)) {
        die("Only JPG, JPEG, PNG and WEBP images are allowed.");
    }

    $newImageName = uniqid("img_", true) . "." . $extension;

    $imagePath = "uploads/images/" . $newImageName;

    move_uploaded_file(
        $imageTmp,
        __DIR__ . "/" . $imagePath
    );
}

$sql = "INSERT INTO inquiries (Name, Email, Phone, Service, Message, Image)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssss",
    $name,
    $email,
    $phone,
    $service,
    $message,
    $imagePath
);

if ($stmt->execute()) {

    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inquiry Submitted</title>

        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background: #f0fdfa;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }

            .success-box {
                background: white;
                width: 90%;
                max-width: 500px;
                padding: 45px 30px;
                text-align: center;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.10);
            }

            .success-icon {
                font-size: 55px;
                margin-bottom: 15px;
            }

            h1 {
                color: #0f766e;
                margin-bottom: 15px;
            }

            p {
                color: #555;
                line-height: 1.6;
            }

            .back-btn {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 25px;
                background: #0f766e;
                color: white;
                text-decoration: none;
                border-radius: 10px;
            }

            .back-btn:hover {
                background: #115e59;
            }
        </style>
    </head>

    <body>

        <div class="success-box">

            <div class="success-icon">
                🎉
            </div>

            <h1>Thank You!</h1>

            <p>
                Your inquiry has been submitted successfully.
            </p>

            <p>
                Our team will get back to you soon.
            </p>

            <a href="http://127.0.0.1:8000" class="back-btn">
                Back to SparkleHome
            </a>

        </div>

    </body>
    </html>
    ';

} else {

    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>