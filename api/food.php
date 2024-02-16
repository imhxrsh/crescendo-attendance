<?php
include('../config.php');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];

    // Check if participant ID exists
    $participant_exists = mysqli_query($conn, "SELECT id FROM `participants` WHERE id = '$id'");
    if (mysqli_num_rows($participant_exists) === 0) {
        echo '{
    "status": false,
    "error": "Participant not found."
}';
        exit;
    }

    switch ($action) {
        case 'hadfood':
            $currfood = mysqli_query($conn, "SELECT * FROM `participants` WHERE id = '$id'") or die('Query failed');
            $row = mysqli_fetch_assoc($currfood);
            $currValue = $row['food'];

            $newValue = $currValue + 1;

            mysqli_query($conn, "UPDATE `participants` SET `food` = '$newValue' WHERE `id` = '$id'") or die('Query failed');
            echo '{
    "status": true,
    "message": "Food updated successfully.",
    "information": {
        "id": "' . $row['id'] . '",
        "name": "' . $row['name'] . '",
        "event": "' . $row['event'] . '",
        "food": ' . $newValue . '
    }
}';
            break;

        case 'removefood':
            $currfood = mysqli_query($conn, "SELECT * FROM `participants` WHERE id = '$id'") or die('Query failed');
            $row = mysqli_fetch_assoc($currfood);
            $currValue = $row['food'];

            if ($currValue > 0) {
                $newValue = $currValue - 1;

                mysqli_query($conn, "UPDATE `participants` SET `food` = '$newValue' WHERE `id` = '$id'") or die('Query failed');
                echo '{
    "status": true,
    "message": "Food updated successfully.",
    "information": {
        "id": "' . $row['id'] . '",
        "name": "' . $row['name'] . '",
        "event": "' . $row['event'] . '",
        "food": ' . $newValue . '
    }
}';
            } else {
                echo '{
    "status": false,
    "error": "Participant has no food to remove."
}';
            }
            break;

        case 'wipe':
            mysqli_query($conn, "UPDATE `participants` SET `food` = NULL WHERE `id` = '$id'") or die('Query failed');
            echo '{
    "status": true,
    "message": "Food wiped successfully.",
    "information": {
        "id": "' . $id . '",
        "name": "' . $row['name'] . '",
        "event": "' . $row['event'] . '",
        "food": null
    }
}';
            break;

        default:
            echo '{
    "status": false,
    "error": "Invalid action."
}';
            break;
    }
} else {
    echo '{
    "status": false,
    "error": "Invalid request method or action not specified."
}';
}

mysqli_close($conn);
?>
