<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    $check_sql = "SELECT * FROM registrations WHERE user_id = '$user_id' AND event_id = '$event_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "You are already registered for this event.";
        exit;
    }

    $event_sql = "SELECT * FROM events WHERE id = '$event_id'";
    $event_result = mysqli_query($conn, $event_sql);
    $event = mysqli_fetch_assoc($event_result);

    if ($event) {
        if ($event['current_participants'] < $event['max_participants']) {
            $register_sql = "INSERT INTO registrations (user_id, event_id) VALUES ('$user_id', '$event_id')";
            if (mysqli_query($conn, $register_sql)) {
                $new_participants = $event['current_participants'] + 1;
                if ($new_participants <= $event['max_participants']) {
                    $update_sql = "UPDATE events SET current_participants = '$new_participants' WHERE id = '$event_id'";
                    if (mysqli_query($conn, $update_sql)) {
                        header("Location: index.php");
                        exit;
                    } else {
                        echo "Error updating participants: " . mysqli_error($conn);
                    }
                } else {
                    echo "Sorry, this event has reached its maximum number of participants.";
                }
            } else {
                echo "Error registering for event: " . mysqli_error($conn);
            }
        } else {
            echo "Sorry, this event has reached its maximum number of participants.";
        }
    } else {
        echo "Event not found.";
    }
} else {
    echo "Invalid event ID.";
}
?>
