<?php
include('config.php');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$select_elex = mysqli_query($conn, "SELECT * FROM `participants` WHERE event = 'elexathon'") or die('query failed');
$elex_registrations = mysqli_num_rows($select_elex);

$select_mech = mysqli_query($conn, "SELECT * FROM `participants` WHERE event = 'mechathon'") or die('query failed');
$mech_registrations = mysqli_num_rows($select_mech);

$select_elex_attended = mysqli_query($conn, "SELECT * FROM `participants` WHERE event = 'elexathon' AND attendance = '1'") or die('query failed');
$elex_attended = mysqli_num_rows($select_elex);

$select_mech_attended = mysqli_query($conn, "SELECT * FROM `participants` WHERE event = 'mechathon' AND attendance = '1'") or die('query failed');
$mech_attended = mysqli_num_rows($select_mech);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crescendo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <?php include ('navbar.html') ?>
    <div class="container">
        <div class="row justify-content-center py-5">
            <div class="text-center mb-5">
                <h1>Dashboard</h1>
            </div>
            <div class="d-flex flex-column col-lg-6 text-center justify-content-center align-items-center">
                <h2>Elex-a-thon</h2>
                <div class="text-center col-lg-6 col-md-8 col-sm-10 col-10 mt-2">
                    <div class="card" style="height: 10rem;">
                        <div class="card-body mt-4">
                            <h5 class="card-title">Registered</h5>
                            <p class="card-text mt-2">
                                <?php
                                echo $elex_registrations;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="text-center col-lg-6 col-md-8 col-sm-10 col-10 mt-2">
                    <div class="card" style="height: 10rem;">
                        <div class="card-body mt-4">
                            <h5 class="card-title">Attended</h5>
                            <p class="card-text mt-2">
                                <?php
                                echo $elex_attended;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column col-lg-6 text-center justify-content-center align-items-center">
                <h2>Mech-a-thon</h2>
                <div class="text-center col-lg-6 col-md-8 col-sm-10 col-10 mt-2">
                    <div class="card" style="height: 10rem;">
                        <div class="card-body mt-4">
                            <h5 class="card-title">Registered</h5>
                            <p class="card-text mt-2">
                                <?php
                                echo $mech_registrations;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="text-center col-lg-6 col-md-8 col-sm-10 col-10 mt-2">
                    <div class="card" style="height: 10rem;">
                        <div class="card-body mt-4">
                            <h5 class="card-title">Attended</h5>
                            <p class="card-text mt-2">
                                <?php
                                echo $mech_attended;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>