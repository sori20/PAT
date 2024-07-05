<?php
class Task {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function addTask($task_name, $description, $due_date) {
        $query = "INSERT INTO tasks (task_name, description, due_date) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sss", $task_name, $description, $due_date);
        $stmt->execute();
        return $stmt->insert_id; // Mengembalikan ID dari task yang baru ditambahkan
    }

    public function getTask($task_id) {
        $query = "SELECT * FROM tasks WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Mengembalikan hasil query sebagai array asosiatif
    }

    public function updateTask($task_id, $task_name, $description, $due_date) {
        $query = "UPDATE tasks SET task_name = ?, description = ?, due_date = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sssi", $task_name, $description, $due_date, $task_id);
        $stmt->execute();
        return $stmt->affected_rows > 0; // Mengembalikan true jika ada baris yang terpengaruh (berhasil di-update)
    }

    public function deleteTask($task_id) {
        $query = "DELETE FROM tasks WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        return $stmt->affected_rows > 0; // Mengembalikan true jika ada baris yang terpengaruh (berhasil dihapus)
    }

    public function getAllTasks() {
        $query = "SELECT * FROM tasks";
        $result = $this->connection->query($query);
        return $result->fetch_all(MYSQLI_ASSOC); // Mengembalikan semua hasil query sebagai array asosiatif
    }
}
?>
