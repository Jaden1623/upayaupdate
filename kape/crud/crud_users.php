<?php
// CREATE USER
function createUser($conn, $username, $password, $role, $email) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // always hash passwords!
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashedPassword, $role, $email);
    return $stmt->execute();
}


// READ USERS
function readUsers($conn) {
    $sql = "SELECT * FROM users";
    return $conn->query($sql);
}

// UPDATE USER
function updateUser($conn, $id, $username, $email, $password, $role) {
    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?");
    $stmt->bind_param("ssssi", $username, $email, $password, $role, $id);
    return $stmt->execute();
}


// DELETE USER
function deleteUser($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function getUserById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function addUser($conn, $username, $email, $password, $role) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);
    return $stmt->execute();
}
?>
