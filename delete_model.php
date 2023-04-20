<?php
require_once('db.php');

function delete_user($user_id) {
    global $pdo;

    $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
    $stmt->execute(['id' => $user_id]);
}
?>