<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once('config.php');
require_once('Task.php');

$taskObj = new Task($connection);

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy(); // Hapus semua data sesi
    header("Location: login.php"); // Redirect ke halaman login setelah logout
    exit();
}

// Handle tambah task
if (isset($_POST['add_task'])) {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $taskObj->addTask($task_name, $description, $due_date);
    header("Location: index.php");
    exit();
}

// Handle edit task
if (isset($_POST['save_task'])) {
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $taskObj->updateTask($task_id, $task_name, $description, $due_date);
    header("Location: index.php");
    exit();
}

// Handle hapus task
if (isset($_POST['delete_task'])) {
    $task_id = $_POST['task_id'];

    $taskObj->deleteTask($task_id);
    header("Location: index.php");
    exit();
}

// Ambil semua task
$tasks = $taskObj->getAllTasks();

// Jika ada task yang akan di-edit
$edit_task = null;
if (isset($_POST['edit_task'])) {
    $edit_task = $taskObj->getTask($_POST['task_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TODO App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 mb-3">
                <form action="" method="POST">
                    <button type="submit" name="logout" class="btn btn-danger float-right">Logout</button>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Task List</h2>
                <ul class="list-group">
                    <?php foreach ($tasks as $task): ?>
                        <li class="list-group-item">
                            <strong><?php echo $task['task_name']; ?></strong>
                            <p><?php echo $task['description']; ?></p>
                            <small>Due Date: <?php echo $task['due_date']; ?></small>
                            <!-- Form untuk edit task -->
                            <form action="index.php" method="POST" class="float-right">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <button type="submit" name="edit_task" class="btn btn-sm btn-primary mr-2">Edit</button>
                            </form>
                            <!-- Form untuk hapus task -->
                            <form action="index.php" method="POST" class="float-right">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <button type="submit" name="delete_task" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </li>
                        <?php if ($edit_task && $edit_task['id'] == $task['id']): ?>
                            <li class="list-group-item">
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="task_id" value="<?php echo $edit_task['id']; ?>">
                                    <div class="form-group">
                                        <label for="task_name">Task Name</label>
                                        <input type="text" id="task_name" name="task_name" class="form-control" value="<?php echo $edit_task['task_name']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description" class="form-control" required><?php echo $edit_task['description']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="due_date">Due Date</label>
                                        <input type="date" id="due_date" name="due_date" class="form-control" value="<?php echo $edit_task['due_date']; ?>" required>
                                    </div>
                                    <button type="submit" name="save_task" class="btn btn-primary">Save Task</button>
                                </form>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-6">
                <h2>Add Task</h2>
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="task_name">Task Name</label>
                        <input type="text" id="task_name" name="task_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" id="due_date" name="due_date" class="form-control" required>
                    </div>
                    <button type="submit" name="add_task" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
