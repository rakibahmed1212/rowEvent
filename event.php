<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\UserAuthentication;
use App\EventManager;

// Authentication Check
$authData = new UserAuthentication();
if (!$authData->isAuthenticated()) {
    header('Location: login.php');
    exit;
}

$eventManager = new EventManager();
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_event'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $capacity = $_POST['capacity'];
        $eventManager->createEvent($userId, $name, $description, $capacity);
    }

    if (isset($_POST['update_event'])) {
        $eventId = $_POST['event_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $capacity = $_POST['capacity'];
        $eventManager->updateEvent($eventId, $name, $description, $capacity);
    }

    if (isset($_POST['delete_event'])) {
        $eventId = $_POST['event_id'];
        $eventManager->deleteEvent($eventId, $userId);
    }
}


$events = $eventManager->getEvents($userId);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Event Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Event Dashboard</h1>
        <div class="mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal">Create New Event</button>
        </div>
        <!-- Create Event Modal -->
        <div class="modal fade" id="createEventModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="">
                        <div class="modal-header">
                            <h5 class="modal-title">Create Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Event Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Capacity</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="create_event" class="btn btn-primary">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Event Table -->
        <table id="eventTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['name']) ?></td>
                        <td><?= htmlspecialchars($event['description']) ?></td>
                        <td><?= $event['capacity'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editEvent(<?= $event['id'] ?>, '<?= htmlspecialchars($event['name']) ?>', '<?= htmlspecialchars($event['description']) ?>', '<?= htmlspecialchars($event['capacity']) ?>')">Edit</button>
                            <a href="download.php?event_id=<?= $event['id'] ?>" class="btn btn-success btn-sm">Download CSV</a>
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                <button type="submit" name="delete_event" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_event_id" name="event_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="edit_capacity" name="capacity" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update_event" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#eventTable').DataTable();
        });
    </script>
    <script>
        function editEvent(id, name, description, capacity) {
            document.getElementById('edit_event_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_capacity').value = capacity;
            new bootstrap.Modal(document.getElementById('editEventModal')).show();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>