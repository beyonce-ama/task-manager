<?php
include '../includes/db.php';
include '../config/auth.php';
checkSession();

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM tasks WHERE user_id = ? AND status = 'In Progress' ORDER BY due_date ASC";  // Modify based on your table structure
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); 
$stmt->execute();
$inProgressTasksResult = $stmt->get_result();

if (!$inProgressTasksResult) {
    die("Error fetching tasks: " . $conn->error);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../assets/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="color: white; font-size: 28px; font-weight: bold; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);"  >Task Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="calendar.php">Calendar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tasks_by_priority.php">Tasks by Priority</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="all_tasks.php">In Progress Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="completed_tasks.php">Completed Tasks</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="return confirmLogout();">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="notification"></div>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="task-card p-4" style="background-color:rgb(250, 217, 132); border-radius: 25px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 style="font-size: 2rem; color: #fff; font-size: 30px; font-weight: bold; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);">In Progress Tasks</h2>
                        <button type="button" class="btn btn-success px-4 py-2" style="border-radius: 20px; font-size: 1rem; transition: background-color 0.3s;  font-size: 18px; font-weight: bold; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);"
                        data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            + Add New Task
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3 " style="background-color:#E8E8E8; border-radius: 20px;">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if ($inProgressTasksResult->num_rows > 0): ?>
                <?php while ($task = $inProgressTasksResult->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card task-card">
                            <div class="card-body" style="background-color: #f9f9f9; border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                <h5 class="card-title" style="font-size: 1.25rem; color: #333; font-weight: bold;"><?= htmlspecialchars($task['title']) ?></h5>
                                <p class="card-text" style="font-size: 1rem; color: #666; line-height: 1.5;"><?= htmlspecialchars($task['description']) ?></p>

                                <div class="row">
                                    <div class="col-6">
                                        <p style="font-weight: 500; color: #444;">
                                            <strong>Priority:</strong> 
                                            <span class="badge text-bg-primary"><?= htmlspecialchars($task['priority']) ?></span>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                         <p style="font-weight: 500; color: #444;">
                                             <strong>Status:</strong> 
                                            <span class="badge text-bg-info"><?= htmlspecialchars($task['status']) ?></span>
                                        </p>
                                    </div>
                                </div>
                                <p style="font-weight: 400; color: #444;">
                                    <strong>Due Date:</strong> <?= htmlspecialchars($task['due_date']) ?>
                                </p>

                                <a href="../config/mark_completed.php?id=<?= $task['id'] ?>" onclick="return confirm('Are you sure you have completed this task?');" class="btn btn-success btn-sm mb-3" style="border-radius: 20px; font-size: 1rem; padding: 8px 20px;">
                                    <i class="fas fa-check"></i> Mark as Completed
                                </a>
                                <div class="dropdown float-end">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editTaskModal"
                                                data-task-id="<?= $task['id'] ?>"
                                                data-title="<?= htmlspecialchars($task['title']) ?>"
                                                data-description="<?= htmlspecialchars($task['description']) ?>"
                                                data-priority="<?= $task['priority'] ?>"
                                                data-due-date="<?= $task['due_date'] ?>">
                                                Edit
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item" href="../config/delete_task.php?id=<?= $task['id'] ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No tasks in progress found.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg rounded-4 border-0">
                <div class="modal-header text-white" style="background: linear-gradient(45deg, #4e73df, #1cc88a); border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="modal-title fw-bold" id="addTaskLabel">Add New Task</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST" action="../config/add_task.php">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Task Title</label>
                            <input type="text" class="form-control custom-input" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control custom-input" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label fw-semibold">Priority</label>
                            <select class="form-select custom-input" id="priority" name="priority">
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label fw-semibold">Due Date</label>
                            <input type="date" class="form-control custom-input" id="due_date" name="due_date" required>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary fw-bold px-4">Add Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content shadow-lg rounded-4 border-0">
                <div class="modal-header text-white" style="background: linear-gradient(45deg, #ffc107, #f39c12); border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="modal-title" id="editTaskLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../config/edit_task.php">
                    <input type="hidden" id="task_id" name="task_id" value="">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Task Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label fw-semibold">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label fw-semibold">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" value="" required>
                    </div>
                    <input type="hidden" id="status" name="status" value="In Progress">
                    <div class="modal-footer border-0">
                         <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                         <button type="submit" class="btn btn-primary fw-bold px-4y">Update Task</button>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmLogout() {
        // Display the confirmation dialog
        var result = confirm("Are you sure you want to log out?");
        // If the user clicks "OK", proceed with the logout, otherwise do nothing
        if (result) {
            window.location.href = "../config/logout.php"; // Redirect to logout
        } else {
            return false; // Prevent the default action (stay on the page)
        }
    }
</script>
<script>
    const editTaskModal = document.getElementById('editTaskModal');

    editTaskModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const taskId = button.getAttribute('data-task-id');
        const title = button.getAttribute('data-title');
        const description = button.getAttribute('data-description');
        const priority = button.getAttribute('data-priority');
        const dueDate = button.getAttribute('data-due-date');

        const modalTitle = editTaskModal.querySelector('.modal-title');
        const taskIdInput = editTaskModal.querySelector('#task_id');
        const titleInput = editTaskModal.querySelector('#title');
        const descriptionInput = editTaskModal.querySelector('#description');
        const prioritySelect = editTaskModal.querySelector('#priority');
        const dueDateInput = editTaskModal.querySelector('#due_date');

        taskIdInput.value = taskId;
        titleInput.value = title;
        descriptionInput.value = description;
        prioritySelect.value = priority;
        dueDateInput.value = dueDate;
    });
</script>
<script>
        window.onload = function() {
            <?php if (isset($_SESSION['message'])): ?>
                var notification = document.getElementById('notification');
                var message = "<?php echo $_SESSION['message']; ?>";

                var messageDiv = document.createElement('div');
                messageDiv.innerText = message;
                messageDiv.classList.add('notification-item');
                notification.appendChild(messageDiv);

                setTimeout(function() {
                    notification.removeChild(messageDiv);
                }, 5000); 

                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
        };
</script>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>