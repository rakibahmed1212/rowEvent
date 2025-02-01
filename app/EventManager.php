<?php


namespace App;

use Exception;

class EventManager extends ProcessDataContainer
{

    public function createEvent($userId, $name, $description, $capacity)
    {
        $columns = '(user_id,name, description, capacity, created_at)';
        $values = "('$userId','$name', '$description', $capacity, NOW())";
        return $this->createData('events', $columns, $values);
    }

    public function getEvents($userId)
    {
        return $this->getData('events', "user_id = $userId");
    }
    public function getAllEvents()
    {
        return $this->getData('events');
    }

    public function getEvent($eventId)
    {
        try {
            if (!is_numeric($eventId)) {
                throw new Exception("Invalid");
            }
            $result = $this->getSingleData('events', "id = $eventId");
            if ($result) {
                return $result;
            }
            throw new Exception("Invalid Events");
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php");
        }
    }

    public function updateEvent($eventId, $name, $description, $capacity)
    {
        $updates = "name = '$name', description = '$description',capacity='$capacity', updated_at = NOW()";
        return $this->updateData('events', $updates, "id = $eventId");
    }

    public function deleteEvent($eventId, $userId)
    {
        return $this->deleteData('events', "id = $eventId AND user_id = $userId");
    }
    public function getEventAttendees($eventId)
    {
        return $this->getData('attendees', "event_id = $eventId");
    }
}
