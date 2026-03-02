<?php
require_once "config.php";

$sql = "SELECT id, ime, prezime, email, created_at FROM users ORDER BY id DESC";
$result = $conn->query($sql);

$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>
