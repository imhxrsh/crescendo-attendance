<?php

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        $sql = "UPDATE `participants` SET `attendance` = '1' WHERE `id` = '$id' AND 'event' = 'mechathon'";
        if (mysqli_query($conn, $sql)) {
            echo '{
    "success": true,
    "message": "Attendance updated successfully."
}';
        } else {
            echo '{
    "success": false,
    "errpr": "Error updating attendance"
}';
        }
    } else {
        echo '{
    "success": false,
    "error": "Error: id parameter is required."
}';
    }
} else {
    echo '{
    "success": false,
    "error": "Error: Only POST requests are allowed."
}';
}

mysqli_close($conn);
