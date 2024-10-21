<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['cancel_event_id'])) {
    $event_id = $_POST['cancel_event_id'];

    $cancel_sql = "DELETE FROM registrations WHERE user_id = '$user_id' AND event_id = '$event_id'";
    if (mysqli_query($conn, $cancel_sql)) {
        $event_sql = "SELECT current_participants FROM events WHERE id = '$event_id'";
        $event_result = mysqli_query($conn, $event_sql);
        $event = mysqli_fetch_assoc($event_result);

        if ($event && $event['current_participants'] > 0) {
            $new_participants = $event['current_participants'] - 1;
            if ($new_participants >= 0) {
                $update_participants_sql = "UPDATE events SET current_participants = '$new_participants' WHERE id = '$event_id'";
                if (mysqli_query($conn, $update_participants_sql)) {
                    echo "You have successfully canceled your registration.";
                    header("Location: index.php");
                    exit;
                } else {
                    echo "Error updating participants: " . mysqli_error($conn);
                }
            } else {
                echo "Error: Cannot reduce participants below 0.";
            }
        } else {
            echo "Error retrieving event participants.";
        }
    } else {
        echo "Error canceling registration: " . mysqli_error($conn);
    }
}
?>
