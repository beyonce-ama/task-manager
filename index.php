<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f4f4f9;
        }
        .hero-section {
            text-align: center;
            padding: 80px 20px;
            background: linear-gradient(45deg, #4e73df, #1cc88a);

            color: white;
            border-radius: 0 0 20px 20px;
        }
        .hero-section h1 {
            font-size: 2.8rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin-top: 10px;
        }
        .features {
            padding: 50px 20px;
        }
        .feature-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        .feature-box:hover {
            transform: translateY(-5px);
        }
        .feature-box i {
            font-size: 2.5rem;
            color: #4CAF50;
            margin-bottom: 15px;
        }
        .auth-buttons {
            margin-top: 20px;
        }
        .btn-custom {
            font-size: 1.2rem;
            padding: 10px 30px;
            border-radius: 30px;
            transition: 0.3s;
        }
        .btn-register {
            background-color: #FFC107;
            color: #333;
        }
        .btn-login {
            background-color: #4CAF50;
            color: white;
        }
        .btn-register:hover {
            background-color: #e0a800;
        }
        .btn-login:hover {
            background-color: #2E7D32;
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <div class="hero-section">
        <h1>Welcome to Task Manager</h1>
        <p>Effortlessly manage your tasks, stay organized, and boost your productivity.</p>
        <div class="auth-buttons">
            <a href="./pages/register.php" class="btn btn-custom btn-register">Register</a>
            <a href="./pages/login.php" class="btn btn-custom btn-login ms-3">Login</a>
        </div>
    </div>

    <div class="container features">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-tasks"></i>
                    <h4>Effortless Task Management</h4>
                    <p>Add, edit, and delete tasks with ease. No clutter, no confusion—just a smooth workflow to keep you focused.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-clock"></i>
                    <h4>Stay Ahead of Deadlines</h4>
                    <p>Set due dates and track your progress. No more last-minute panic—stay on top of your tasks like a pro!</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-check-circle"></i>
                    <h4>Satisfaction of Completion</h4>
                    <p>Nothing beats the joy of checking off a completed task. Stay productive and feel accomplished every day!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5 py-4 bg-dark text-white">
        <div class="container text-center">
            <p class="mb-2">This Task Management System is a project created for educational purposes as part of a DevOps learning initiative.</p>
            <h5 class="mb-3">Meet the Team</h5>
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <p><strong>Beyonce Ama</strong><br>Project Manager</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Monica Carreon</strong><br>Developer</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Romel Gamboa</strong><br>Developer</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Kayle Cedric Larin</strong><br>Developer</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Jolas Arpon</strong><br>Developer</p>
                </div>
            </div>
            <p class="mt-3">&copy; 2025 Task Management System. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>


</body>
</html>
