<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\EventManager;

if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];
    $eventManager = new EventManager();

    $attendees = $eventManager->getEventAttendees($eventId);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendees_event_' . $eventId . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Name', 'Email', 'Registered At']);

    foreach ($attendees as $attendee) {
        fputcsv($output, [$attendee['name'], $attendee['email'], $attendee['registered_at']]);
    }

    fclose($output);
    exit;
}
