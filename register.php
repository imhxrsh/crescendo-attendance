<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admin (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: /login");
        exit;
    } else {
        $error_message = "Registration failed. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRESCENDO - Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <?php include 'navbar.html' ?>

    <div class="register d-flex container justify-content-center align-items-center">
        <div class="d-flex row col-lg-8 col-12 justify-content-center align-items-center">
            <h1 class="text-center">Register</h1>
            <div class="col-lg-5 col-12">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name<span style="color: red;">*</span></label>
                        <input type="text" class="col mb-2 form-control" name="name" id="name" aria-describedby="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address<span style="color: red;">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" aria-describedby="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password<span style="color: red;">*</span></label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <p>A User already? <a href="/login">Login!</a></p>
                    <center><button type="submit" class="btn bg-gradient btn-secondary">Submit</button></center>
                </form>
            </div>
        </div>

    </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>