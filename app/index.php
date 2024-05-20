<?php
session_start();

// Initialize the to-do list array
$todoList = array(); // Data structure

// If the session contains a to-do list, load it
if (isset($_SESSION["todoList"])) {
    $todoList = $_SESSION["todoList"];
}

// User-defined function to add a task to the to-do list
function appendData($task, $todoList) {
    array_push($todoList, $task); // System-defined function
    return $todoList;
}

// User-defined function to delete a task from the to-do list
function deleteData($taskToDelete, $todoList) {
    foreach ($todoList as $index => $taskName) { // Using operators and data structure
        if ($taskName === $taskToDelete) { // Operator
            unset($todoList[$index]); // System-defined function
        }
    }
    return array_values($todoList); // Re-index array
}

// User-defined function to edit a task in the to-do list
function editData($oldTask, $newTask, $todoList) {
    foreach ($todoList as $index => $taskName) { // Using operators and data structure
        if ($taskName === $oldTask) { // Operator
            $todoList[$index] = $newTask;
        }
    }
    return $todoList;
}

// Handle form submission to add/edit a task
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Operator
    if (empty($_POST["task"])) {
        echo '<script>alert("Error: there is no data to add in array")</script>';
        exit;
    }
    if (isset($_POST['oldTask'])) {
        $todoList = editData($_POST['oldTask'], $_POST['task'], $todoList); // Using user-defined function
    } else {
        $todoList = appendData($_POST["task"], $todoList); // Using user-defined function
    }
    $_SESSION["todoList"] = $todoList; // Updating session
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to avoid form resubmission
    exit();
}

// Handle task deletion
if (isset($_GET['task'])) { // System-defined function
    $todoList = deleteData($_GET['task'], $todoList); // Using user-defined function
    $_SESSION["todoList"] = $todoList; // Updating session
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to avoid form resubmission
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do List</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center">To-Do <span>List</span></h1>
        <div class="main-card card">
            <div class="card-header">Add a New Task</div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" name="task" placeholder="Enter your task here" value="<?php if(isset($_GET['edit'])) echo htmlspecialchars($_GET['edit']); ?>">
                        <?php if(isset($_GET['edit'])): ?>
                            <input type="hidden" name="oldTask" value="<?php echo htmlspecialchars($_GET['edit']); ?>">
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <?php if(isset($_GET['edit'])): ?>
                            Update Task
                        <?php else: ?>
                            Add Task
                        <?php endif; ?>
                    </button>
                </form>
            </div>
        </div>

        <div class="tasks card mt-4">
            <div class="card-header text-light">Tasks</div>
            <ul class="list-group list-group-flush">
            <?php
                if (empty($todoList)) {
                    echo '<li class="list-group-item text-center">No tasks available.</li>';
                } else {
                    foreach ($todoList as $task) { // Using data structure
                        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                        echo htmlspecialchars($task);
                        echo '<div>';
                        echo '<a href="index.php?edit=' . urlencode($task) . '" class="btn btn-warning btn-sm mr-2">Edit</a>';
                        echo '<a href="index.php?task=' . urlencode($task) . '" class="btn btn-danger btn-sm">Delete</a>';
                        echo '</div>';
                        echo '</li>';
                    }
                }
            ?>
            </ul>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
