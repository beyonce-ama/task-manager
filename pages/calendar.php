<?php
include '../includes/db.php';
include '../config/auth.php';
checkSession();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Calendar</title>
    <link href="../assets/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"  style="color: white; font-size: 28px; font-weight: bold; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);" >Task Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="calendar.php">Calendar</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="tasks_by_priority.php">Tasks by Priority</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link " href="all_tasks.php">In Progress Tasks</a>
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="task-card p-4" style="background-color:rgb(103, 204, 235); border-radius: 25px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 style="font-size: 2rem; color: #fff; font-size: 30px; font-weight: bold; text-shadow: 3px 4px 15px rgba(0,0,0,0.3);">
                        Task Calendar</h2>
                        <button type="button" class="btn btn-success px-4 py-2" style="border-radius: 20px; font-size: 1rem; transition: background-color 0.3s;  font-size: 18px; font-weight: bold; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);"
                        data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            + Add New Task
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="calendar"></div>
    <script>
   document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('../config/fetch_tasks.php')  // Path to the PHP script
                .then(response => response.json())  // Parse the JSON response
                .then(data => successCallback(data)) // Pass the data to the calendar
                .catch(error => failureCallback(error)); // Handle any error
        },
        editable: true,
        eventDrop: function(info) {
            var newDate = info.event.startStr;
            var taskId = info.event.id;

            $.ajax({
                url: '../config/update_task.php',
                type: 'POST',
                data: { id: taskId, due_date: newDate },
                success: function(response) {
                    alert('Task due date updated!');
                },
                error: function() {
                    alert('Failed to update task.');
                }
            });
        }
    });
    calendar.render();
});

</script>


</body>
</html>
