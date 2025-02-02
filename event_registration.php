<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\AttendeeManager;
use App\EventManager;

$attendeeManager = new AttendeeManager();
$eventManager = new EventManager();
$event = $eventManager->getEvent($_GET['event_id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_attendee'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $attendeeManager->registerAttendee($_GET['event_id'], $name, $email);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
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
        <h2>Register for <?= htmlspecialchars(isset($event['name']) ? $event['name'] : '') ?></h2>
        <form method="POST" action="">
            <input type="hidden" name="event_id" value="<?= $eventId ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <button type="submit" name="add_attendee" class="btn btn-success">Register</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
