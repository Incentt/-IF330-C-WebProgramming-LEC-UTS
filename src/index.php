<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM events";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_event_id'])) {
    $event_id = $_POST['cancel_event_id'];

    $cancel_sql = "DELETE FROM registrations WHERE user_id = '$user_id' AND event_id = '$event_id'";
    if (mysqli_query($conn, $cancel_sql)) {
        $update_participants_sql = "UPDATE events SET current_participants = current_participants - 1 WHERE id = '$event_id' AND current_participants > 0";
        mysqli_query($conn, $update_participants_sql);

        echo "You have successfully canceled your registration.";
    } else {
        echo "Error canceling registration: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_event_id'])) {
    $event_id = $_POST['register_event_id'];

    $check_event_sql = "SELECT current_participants, max_participants FROM events WHERE id = '$event_id'";
    $event_result = mysqli_query($conn, $check_event_sql);
    $event = mysqli_fetch_assoc($event_result);

    if ($event['current_participants'] < $event['max_participants']) {
        $register_sql = "INSERT INTO registrations (user_id, event_id) VALUES ('$user_id', '$event_id')";
        if (mysqli_query($conn, $register_sql)) {
            $update_participants_sql = "UPDATE events SET current_participants = current_participants + 1 WHERE id = '$event_id'";
            mysqli_query($conn, $update_participants_sql);

            echo "You have successfully registered for the event.";
        } else {
            echo "Error registering for event: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, this event has reached its maximum number of participants.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Events</title>
    <link href="./output.css" rel="stylesheet">

    <script>
        function confirmAction(action, eventId) {
            var message = (action === 'register') ? "Are you sure you want to register for this event?" : "Are you sure you want to cancel your registration for this event?";
            var result = confirm(message);
            
            if (result) {
                if (action === 'register') {
                    registerEvent(eventId);
                } else if (action === 'cancel') {
                    cancelEvent(eventId);
                }
            }
        }

        function registerEvent(eventId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('You have successfully registered!');
                    location.reload();
                }
            };
            xhr.send('register_event_id=' + eventId);
        }

        function cancelEvent(eventId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('You have successfully canceled your registration!');
                    location.reload();
                }
            };
            xhr.send('cancel_event_id=' + eventId);
        }
    </script>
</head>
<body>
    <h2>Available Events</h2>
    <a href="logout.php">Logout</a>
    
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php if (isset($row['name'], $row['date'], $row['time'], $row['location'], $row['description'], $row['banner'], $row['current_participants'], $row['max_participants'])): ?>
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p>Date: <?php echo htmlspecialchars($row['date']); ?></p>
            <p>Time: <?php echo htmlspecialchars($row['time']); ?></p>
            <p>Location: <?php echo htmlspecialchars($row['location']); ?></p>
            <p>Description: <?php echo htmlspecialchars($row['description']); ?></p>
            <?php if (!empty($row['banner'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($row['banner']); ?>" alt="Event Banner" width="200px">
            <?php else: ?>
                <p>No banner available for this event.</p>
            <?php endif; ?>
            <p>Participants: <span id="participants-<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['current_participants']); ?></span> / <?php echo htmlspecialchars($row['max_participants']); ?></p>

            <?php
            $reg_sql = "SELECT * FROM registrations WHERE user_id = '$user_id' AND event_id = '" . $row['id'] . "'";
            $reg_result = mysqli_query($conn, $reg_sql);

            if (mysqli_num_rows($reg_result) > 0): ?>
                <button onclick="confirmAction('cancel', <?php echo htmlspecialchars($row['id']); ?>)">Cancel Registration</button>
            <?php else: ?>
                <button onclick="confirmAction('register', <?php echo htmlspecialchars($row['id']); ?>)">Register</button>
            <?php endif; ?>
        <?php else: ?>
            <p>Event data is incomplete. Please contact the administrator.</p>
        <?php endif; ?>
    <?php endwhile; ?>
</body>
</html>
