<?php
include '../includes/db.php';
include '../config/auth.php';
checkSession();

$user_id = $_SESSION['user_id'];

$completedTasksQuery = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? AND status = 'Completed'");
if (!$completedTasksQuery) {
    die('Error in query preparation: ' . $conn->error);
}
$completedTasksQuery->bind_param('i', $user_id);
$completedTasksQuery->execute();
$completedTasksResult = $completedTasksQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/style.css" rel="stylesheet">
    <title>Dashboard</title>
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
                        <a class="nav-link" href="all_tasks.php">In Progress Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="completed_tasks.php">Completed Tasks</a>
                    </li>
                  
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="return confirmLogout();">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="task-card p-4" style="background-color:rgb(91, 230, 179); border-radius: 25px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 style="font-size: 2rem; color: #fff; font-size: 30px; font-weight: bold; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);">Completed Tasks</h2>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3 " style="background-color:#E8E8E8; border-radius: 20px;">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if ($completedTasksResult->num_rows > 0): ?>
                <?php while ($task = $completedTasksResult->fetch_assoc()): ?>
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

                                <div class="dropdown float-end">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu">
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

    <script>
    function confirmLogout() {
        var result = confirm("Are you sure you want to log out?");
        if (result) {
            window.location.href = "../config/logout.php"; 
        } else {
            return false; 
        }
    }
</script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>