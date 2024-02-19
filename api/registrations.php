<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['event']) && ($_POST['event'] === 'ELEX-A-THON' || $_POST['event'] === 'MECH-A-THON' || $_POST['event'] === 'HACKATHON')) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $event = mysqli_real_escape_string($conn, $_POST['event']);

        $query = "SELECT * FROM `participants` WHERE `id` = '$id' AND `event` = '$event'";

        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);
            $name = $row['name'];
            $fetched_id = $row['id'];
            $fetched_event = $row['event'];

            $sql = "UPDATE `participants` SET `attendance` = '1' WHERE `id` = '$id';";
            if (mysqli_query($conn, $sql)) {
                echo '{
    "status": true,
    "message": "Attendance updated successfully.",
    "information": {
        "id": "' . $fetched_id . '",
        "name": "' . $name . '",
        "event": "' . $fetched_event . '"
    }
}';
            } else {
                echo '{
    "status": false,
    "error": "Error updating attendance"
}';
            }
        } else {
            echo '{
    "status": false,
    "error": "Error: ID not found in the database or not participating in the specified event"
}';
        }
    } else {
        echo '{
    "status": false,
    "error": "Error: id and event parameters are required and event parameter must be either ELEX-A-THON, MECH-A-THON, or HACKATHON."
}';
    }
} else {
    echo '{
    "status": false,
    "error": "Error: Only POST requests are allowed."
}';
}

mysqli_close($conn);
?>
