<?php
include('config.php');
session_start();

if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $max_participants = $_POST['max_participants'];
    $status = $_POST['status'];
    $event_id = $_POST['event_id'];

    if($_FILES['image']['name']) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, "../uploads/$image_name");

        $query = "UPDATE events SET name = ?, date = ?, location = ?, description = ?, max_participants = ?, status = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssdsdi", $name, $date, $location, $description, $max_participants, $status, $image_name, $event_id);
    } else {
        $query = "UPDATE events SET name = ?, date = ?, location = ?, description = ?, max_participants = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssdsi", $name, $date, $location, $description, $max_participants, $status, $event_id);
    }

    if($stmt->execute()) {
        echo "Event updated successfully!";
    } else {
        echo "Failed to update event.";
    }
}

if(isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $query = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
}
?>

<h2>Edit Event</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
    <label>Event Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required><br>
    <label>Date</label>
    <input type="date" name="date" value="<?php echo $event['date']; ?>" required><br>
    <label>Location</label>
    <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required><br>
    <label>Description</label>
    <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea><br>
    <label>Max Participants</label>
    <input type="number" name="max_participants" value="<?php echo $event['max_participants']; ?>" required><br>
    <label>Status</label>
    <select name="status" required>
        <option value="open" <?php if($event['status'] == 'open') echo 'selected'; ?>>Open</option>
        <option value="closed" <?php if($event['status'] == 'closed') echo 'selected'; ?>>Closed</option>
        <option value="canceled" <?php if($event['status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
    </select><br>
    <label>Event Image</label>
    <input type="file" name="image"><br>
    <button type="submit">Save Changes</button>
</form>
