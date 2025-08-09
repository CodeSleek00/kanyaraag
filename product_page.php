<?php
require_once 'db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $original_price = $_POST['original_price'];
    $discount_price = $_POST['discount_price'];
    $page = $_POST['page'];
    
    // Handle image upload
    $target_dir = "product_images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        $error = "File is not an image.";
    }
    
    // Check file size (5MB max)
    if ($_FILES["image"]["size"] > 5000000) {
        $error = "Sorry, your file is too large.";
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }
    
    if (empty($error)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO products (image_path, name, description, original_price, discount_price, page) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdds", $target_file, $name, $description, $original_price, $discount_price, $page);
            
            if ($stmt->execute()) {
                $success = "Product added successfully!";
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;
        }
        button { background: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #45a049; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Product</h1>
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <form action="product_page.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image" required>
            </div>
            
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="original_price">Original Price:</label>
                <input type="number" id="original_price" name="original_price" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="discount_price">Discount Price (if any):</label>
                <input type="number" id="discount_price" name="discount_price" step="0.01">
            </div>
            
            <div class="form-group">
                <label for="page">Display Page:</label>
                <select id="page" name="page" required>
                    <option value="women.php">Women's Page</option>
                    <option value="men.php">Men's Page</option>
                    <option value="kids.php">Kids' Page</option>
                    <option value="new_arrivals.php">New Arrivals</option>
                </select>
            </div>
            
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>