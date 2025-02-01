<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

use App\EventManager;
use App\AttendeeManager;

$eventManager = new EventManager();
$events = $eventManager->getAllEvents();
$eventIds = array_map(function ($event) {
    return $event['id'];
}, $events);
$available = (new AttendeeManager())->getEventAttendeeCounts($eventIds);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="text-center mt-3">
        <a class="btn btn-primary" href="login.php">Login</a>
    </div>
    <div class="container mt-4">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Available Events</h2>
        <div class="row">
            <?php
            foreach ($events as $event):
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
                            <p><strong>Capacity:</strong> <?= $event['capacity'] ?> <strong>Available: </strong> <?php echo isset($available[$event['id']]) ? $event['capacity'] - $available[$event['id']] : $event['capacity']; ?></p>
                            <a href="event_registration.php?event_id=<?= $event['id'] ?>" class="btn btn-primary">Register Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>