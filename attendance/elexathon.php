<?php
include('../config.php');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$select_elex_registrations = mysqli_query($conn, "SELECT * FROM `participants` WHERE event = 'elexathon'") or die('query failed');
$elex_registrations = mysqli_num_rows($select_elex_registrations);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    switch ($action) {
        case 'approve':
            mysqli_query($conn, "UPDATE `participants` SET `attendance` = '1' WHERE `id` = '$id'") or die('Query failed');
            break;

        case 'cancel':
            mysqli_query($conn, "UPDATE `participants` SET `attendance` = '0' WHERE `id` = '$id'") or die('Query failed');
            break;

        case 'wipe':
            mysqli_query($conn, "UPDATE `participants` SET `attendance` = NULL WHERE `id` = '$id'") or die('Query failed');
            break;
            
        default:
            echo "Invalid action.";
            break;
    }
}

session_start();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme=dark>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Elex-a-Thon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <?php include('../navbar.html') ?>
    <div class="container">
        <div class="row justify-content-center py-5">
            <div class="text-center mb-5">
                <h1>Dashboard - Elex-a-Thon</h1>
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
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $select_users = mysqli_query($conn, "SELECT * FROM `participants` WHERE event = 'elexathon' ORDER BY `id` ASC") or die('query failed');
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
                                                    <td>
                                                        <a href="?action=approve&id=<?php echo $fetch_users['id']; ?>" style="color: #fff;"><i class="bi bi-check-lg m-2"></i></a>
                                                        <a href="?action=cancel&id=<?php echo $fetch_users['id']; ?>" style="color: #fff;"><i class="bi bi-x-lg m-2"></i></a>
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