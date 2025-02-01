<?php

namespace App;

use Exception;

class AttendeeManager extends ProcessDataContainer
{
    public function registerAttendee($eventId, $name, $email)
    {
        try {
            $event = $this->getSingleData('events', "id = $eventId");
            if (!$event) {
                throw new Exception("Event not found.");
            }
            $existingAttendee = $this->getSingleData('attendees', "event_id = $eventId AND email = '$email'");
            if ($existingAttendee) {
                throw new Exception("You have already registered for this event.");
            }

            $registeredCount = $this->attendeeCount($eventId);
            if ($registeredCount >= $event['capacity']) {
                throw new Exception("Registration closed: Maximum capacity reached.");
            }
            $columns = '(event_id, name, email, registered_at)';
            $values = "($eventId, '$name', '$email', NOW())";

            if ($this->createData('attendees', $columns, $values)) {
                $_SESSION['success'] = "Registration Successfully.";
            } else {
                throw new Exception("Something went wrong!!");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    }
    public function attendeeCount($eventId)
    {
        $attendeeCount = $this->getData('attendees', "event_id = $eventId");
        return count($attendeeCount);
    }

    public function getEventAttendeeCounts($eventIds)
    {
        try {
            if (empty($eventIds)) {
                throw new Exception("Event IDs cannot be empty.");
            }
            $eventIdsList = implode(',', $eventIds);
            $result = $this->getData('attendees', "event_id IN ($eventIdsList) GROUP BY event_id", "event_id, COUNT(*) AS total_attendees");
            if ($result) {
                $attendeeCounts = [];
                foreach ($result as $row) {
                    $attendeeCounts[$row['event_id']] = $row['total_attendees'];
                }
                return $attendeeCounts;
            }
            return [];
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    }
}
