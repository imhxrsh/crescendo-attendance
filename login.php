<?php
include 'config.php';
$error_message = "Login failed. Please check your email and password.";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT name, email, password FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($name, $email, $db_password);
    $stmt->fetch();

    if (password_verify($password, $db_password)) {
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["name"] = $name;

        header("Location: /");
        exit;
    } else {
        $error_message = "Login failed. Please check your email and password.";
        header("Location: /login?error=loginFailed");
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
    <title>Myriad - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <?php include 'navbar.html' ?>
    <?php if (isset($_GET["error"])) {
        if ($_GET["error"] == "notLoggedIn") {
            echo '<div class="container col-lg-5 col-12"><div class="alert alert-danger alert-dismissible fade show" role="alert">Please log in and then book again!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>';
        }
        if ($_GET["error"] == "loginFailed") {
            echo '<div class="container col-lg-5 col-12"><div class="alert alert-danger alert-dismissible fade show" role="alert">' . $error_message . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>';
        }

    }
    ?>
    <div class="register d-flex container justify-content-center align-items-center" style="height: 80vh;">
        <div class="d-flex row col-lg-8 col-12 justify-content-center align-items-center">
            <h1 class="text-center">Login</h1>
            <div class="col-lg-5 col-12">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="email" aria-describedby="email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <center><button type="submit" class="btn bg-gradient btn-secondary">Login</button></center>
                </form>
            </div>
        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>