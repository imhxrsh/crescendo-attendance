<?php
include('config.php');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];

    switch ($action) {
        case 'hadfood':
            // Fetch current value
            $currfood = mysqli_query($conn, "SELECT food FROM `participants` WHERE id = '$id'") or die('Query failed');
            $row = mysqli_fetch_assoc($currfood);
            $currValue = $row['food'];

            // Increment value
            $newValue = $currValue + 1;

            // Update database with new value
            mysqli_query($conn, "UPDATE `participants` SET `food` = '$newValue' WHERE `id` = '$id'") or die('Query failed');
            break;

        case 'removefood':
            // Fetch current value
            $currfood = mysqli_query($conn, "SELECT food FROM `participants` WHERE id = '$id'") or die('Query failed');
            $row = mysqli_fetch_assoc($currfood);
            $currValue = $row['food'];

            // Decrement value
            $newValue = $currValue - 1;

            // Update database with new value
            mysqli_query($conn, "UPDATE `participants` SET `food` = '$newValue' WHERE `id` = '$id'") or die('Query failed');
            break;

        case 'wipe':
            mysqli_query($conn, "UPDATE `participants` SET `food` = NULL WHERE `id` = '$id'") or die('Query failed');
            break;

        default:
            echo "Invalid action.";
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crescendo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <?php include('navbar.html') ?>
    <div class="container">
        <div class="row justify-content-center py-5">
            <div class="text-center mb-5">
                <h1>Dashboard - Food</h1>
            </div>

            <div class="d-flex flex-column col-lg-10 col-md-10 col-12 text-center justify-content-center align-items-center m-2">
                <div class="text-center col-12">
                    <div class="card">
                        <div class="card-body mt-4">
                            <div class="d-flex row card-head justify-content-between">
                                <h5 class="col-9 card-title">List of Participants</h5>
                                <input type="text" class="col form-control me-2" id="elexsearchInput" placeholder="Search" aria-label="Search" aria-describedby="search">
                            </div>
                            <div class="table-responsive">
                                <p class="card-text mt-2">
                                <table class="table table-hover" id="elextable">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Event </th>
                                            <th scope="col">Count</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $select_users = mysqli_query($conn, "SELECT * FROM `participants` ORDER BY `id` ASC") or die('query failed');
                                    if (mysqli_num_rows($select_users) > 0) {
                                        while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                                    ?>
                                            <tbody>
                                                <tr <?php if ($fetch_users['attendance'] == '1') {
                                                        echo 'class="table-success"';
                                                    } elseif ($fetch_users['attendance'] == '0') {
                                                        echo 'class="table-danger"';
                                                    } else {
                                                        echo '';
                                                    } ?>>
                                                    <td><b><?php echo $fetch_users['id']; ?></b></th>
                                                    <td><?php echo ($fetch_users['name']); ?></td>
                                                    <td><?php echo ($fetch_users['event']); ?></td>
                                                    <td><?php echo ($fetch_users['food']); ?></td>
                                                    <td class="d-flex justify-content-center">
                                                        <form method="post" action="">
                                                            <input type="hidden" name="action" value="hadfood">
                                                            <input type="hidden" name="id" value="<?php echo $fetch_users['id']; ?>">
                                                            <button type="submit" style="color: #fff; background-color: transparent; border: none;"><i class="bi bi-arrow-up m-2"></i></button>
                                                        </form>
                                                        <form method="post" action="">
                                                            <input type="hidden" name="action" value="removefood">
                                                            <input type="hidden" name="id" value="<?php echo $fetch_users['id']; ?>">
                                                            <button type="submit" style="color: #fff; background-color: transparent; border: none;"><i class="bi bi-arrow-down m-2"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tbody>
                                    <?php
                                        }
                                    } else {
                                        echo '</table><div class="container"><div class="text-center">No Users Registered yet!</div></div>';
                                    }
                                    ?>

                                </table>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const searchInput = document.getElementById('elexsearchInput');
        const table = document.getElementById('elextable');
        const rows = table.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function() {
            const searchText = searchInput.value.toLowerCase();

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                let found = false;

                for (let j = 0; j < row.cells.length; j++) {
                    const cell = row.cells[j];
                    const content = cell.textContent || cell.innerText;

                    if (content.toLowerCase().includes(searchText)) {
                        found = true;
                        break;
                    }
                }

                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>