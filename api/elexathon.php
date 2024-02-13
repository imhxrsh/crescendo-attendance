<?php

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        $sql = "UPDATE `participants` SET `attendance` = '1' WHERE `id` = '$id'";
        if (mysqli_query($conn, $sql)) {
            echo "Attendance updated successfully.";
        } else {
            echo "Error updating attendance: " . mysqli_error($conn);
        }
    } else {
        echo "Error: 'id' parameter is required.";
    }
} else {
    echo "Error: Only POST requests are allowed.";
}

mysqli_close($conn);

?>
