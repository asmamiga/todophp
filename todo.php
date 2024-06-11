<?php
$method = $_SERVER["REQUEST_METHOD"];
$conn = new PDO("mysql:host=localhost;dbname=todo", "root", "");

// Get all data
if ($method == "GET") {
    $id = @$_GET["id"];

    if ($id) {
        $query = $conn->prepare("SELECT id, task FROM tasks WHERE id=?");
        $query->execute([$id]);
        $rows = $query->fetchObject();
    }
    else {
        $query = $conn->query("SELECT id, task FROM tasks ORDER BY id DESC");
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($rows);
}
// Insert or Update data according to (id) value
else if ($method == "POST") {
    $id = @$_POST["id"];
    $task = @$_POST["task"];
    // Return error message if some values are empty
    if (!$task) {
        $data = [ "status" => false, "message" => "An error has occured!" ];
    }
    else {
        // Update operation
        if ($id) {
            $query = $conn->prepare("UPDATE tasks SET task=? WHERE id=?");
            $query->execute([$task, $id]);
            $data = [ "status" => true, "message" => "Post updated successfully!" ];
        }
        // Insert operation
        else {
            $query = $conn->prepare("INSERT INTO tasks (task) VALUES (?)");
            $query->execute([$task]);
            $data = [ "status" => true, "message" => "Post inserted successfully!" ];
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
// Delete data by (id)
else if ($method == "DELETE") {
    $id = @$_REQUEST["id"];
    // Return error message if id is empty
    if (!$id) {
        $data = [ "status" => false, "message" => "An error has occured!" ];
    }
    else {
        $query = $conn->prepare("DELETE FROM tasks WHERE id=?");
        $query->execute([$id]);
        $data = [ "status" => true, "message" => "Post deleted successfully!" ];
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
