<?php
include('../config.php');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];

    switch ($action) {
        case 'hadfood':
            $currfood = mysqli_query($conn, "SELECT food FROM `participants` WHERE id = '$id'") or die('Query failed');
            $row = mysqli_fetch_assoc($currfood);
            $currValue = $row['food'];

            $newValue = $currValue + 1;

            mysqli_query($conn, "UPDATE `participants` SET `food` = '$newValue' WHERE `id` = '$id'") or die('Query failed');
            echo '{
    "success": true,
    "message": "Food updated successfully."
}';
            break;

        case 'removefood':
            $currfood = mysqli_query($conn, "SELECT food FROM `participants` WHERE id = '$id'") or die('Query failed');
            $row = mysqli_fetch_assoc($currfood);
            $currValue = $row['food'];

            $newValue = $currValue - 1;

            mysqli_query($conn, "UPDATE `participants` SET `food` = '$newValue' WHERE `id` = '$id'") or die('Query failed');
            echo '{
    "success": true,
    "message": "Food updated successfully."
}';
            break;

        case 'wipe':
            mysqli_query($conn, "UPDATE `participants` SET `food` = NULL WHERE `id` = '$id'") or die('Query failed');
            echo '{
    "success": true,
    "message": "Food wiped successfully."
}';
            break;

        default:
            echo "Invalid action.";
            break;
    }
}

?>