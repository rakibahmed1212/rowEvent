<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\UserAuthentication;

$authData = new UserAuthentication();
if (!$authData->isAuthenticated()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        $authData->logout();
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard</h1>
            <form method="POST" action="">
                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            </form>
        </div>

        <div class="card shadow p-4">
            <h2>Welcome to the Dashboard!</h2>
            <div class="row">
                <div class="col-md-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Event</h5>
                            <p class="card-text">Access your Event.</p>
                            <a href="event.php" class="btn btn-primary">View Event</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>