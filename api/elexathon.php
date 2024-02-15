<?php

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        // Check if the ID exists in the database
        $check_query = "SELECT * FROM `participants` WHERE `id` = '$id'";
        $result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($result) > 0) {
            // ID exists, proceed with updating attendance
            $update_sql = "UPDATE `participants` SET `attendance` = '1' WHERE `id` = '$id'";
            if (mysqli_query($conn, $update_sql)) {
                echo '{
    "success": true,
    "message": "Attendance updated successfully."
}';
            } else {
                echo '{
    "success": false,
    "error": "Error updating attendance."
}';
            }
        } else {
            // ID does not exist in the database
            echo '{
    "success": false,
    "error": "Error: ID does not exist in the database."
}';
        }
    } else {
        echo '{
    "success": false,
    "error": "Error: ID parameter is required."
}';
    }
} else {
    echo '{
    "success": false,
    "error": "Error: Only POST requests are allowed."
}';
}

mysqli_close($conn);
