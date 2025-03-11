<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        // Pastikan kategori tidak duplikat
        $check_stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $check_stmt->bind_param("s", $category_name);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $category_name);

            if ($stmt->execute()) {
                header("Location: products.php");
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Kategori sudah ada!";
        }

        $check_stmt->close();
    }
}

$conn->close();
?>
